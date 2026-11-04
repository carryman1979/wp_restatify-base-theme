const assert = require('node:assert/strict');
const { spawnSync } = require('node:child_process');
const { createRequire } = require('node:module');
const { createServer } = require('node:http');
const path = require('node:path');
const { test } = require('node:test');
const { pathToFileURL } = require('node:url');
const { gzipSync } = require('node:zlib');

const scriptsRequire = createRequire(require.resolve('@wordpress/scripts/package.json'));
const markdownRequire = createRequire(scriptsRequire.resolve('markdownlint-cli/package.json'));
const mathRequire = createRequire(markdownRequire.resolve('micromark-extension-math'));
const devServerRequire = createRequire(scriptsRequire.resolve('webpack-dev-server/package.json'));
const sockjsRequire = createRequire(devServerRequire.resolve('sockjs/package.json'));
const uuidPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/;

test('every locked copy of the remediated dependencies is patched and development-only', () => {
    const { packages } = require('../package-lock.json');
    const { satisfies } = scriptsRequire('semver');
    const minimums = {
        'body-parser': '>=1.20.9 <2',
        'fast-uri': '>=3.1.9',
        'follow-redirects': '>=1.16.1',
        'launch-editor': '>=2.14.2',
        'markdown-it': '>=14.3.3',
        qs: '>=6.16.1',
        'smol-toml': '>=1.9.0',
        katex: '>=0.18.2',
        'js-yaml': '<5.0.0 || >=5.4.1',
        'postcss-selector-parser': '>=7.1.6',
        'serialize-javascript': '>=7.1.3',
        uuid: '>=11.1.1',
        zod: '>=4.6.6 <5',
    };
    for (const [name, range] of Object.entries(minimums)) {
        const copies = Object.entries(packages).filter(([location]) =>
            location.endsWith(`/node_modules/${name}`) || location === `node_modules/${name}`
        );
        assert.ok(copies.length > 0, `${name} must remain covered`);
        for (const [location, metadata] of copies) {
            assert.ok(satisfies(metadata.version, range), `${location}: ${metadata.version}`);
            assert.equal(metadata.dev, true, `${location} must not become a runtime dependency`);
        }
    }
});

test('Express body parsing preserves JSON, forms, compression and enforced limits', async () => {
    const expressRequire = createRequire(devServerRequire.resolve('express/package.json'));
    const parser = expressRequire('body-parser');
    for (const method of ['json', 'urlencoded', 'text', 'raw']) {
        assert.throws(() => parser[method]({ limit: 'invalid', extended: true }), /limit.*invalid/i);
    }

    const app = devServerRequire('express')();
    app.use(parser.json({ limit: '64b' }));
    app.use(parser.urlencoded({ extended: true, limit: '64b' }));
    app.post('/', (request, response) => response.json(request.body));
    app.use((error, request, response, next) => {
        response.status(error.status || 500).json({ type: error.type });
    });
    const server = createServer(app);
    try {
        await new Promise((resolve, reject) => {
            server.once('error', reject);
            server.listen(0, '127.0.0.1', resolve);
        });
        const url = `http://127.0.0.1:${server.address().port}/`;
        const post = (body, headers) => fetch(url, {
            method: 'POST', body, headers, signal: AbortSignal.timeout(5000),
        });
        for (const headers of [
            { 'content-type': 'application/json' },
            { 'content-type': 'application/json', 'content-encoding': 'gzip' },
        ]) {
            const json = JSON.stringify({ item: 'one' });
            const response = await post(headers['content-encoding'] ? gzipSync(json) : json, headers);
            assert.equal(response.status, 200);
            assert.deepEqual(await response.json(), { item: 'one' });
        }
        const form = await post('item[0]=one&item[1]=two', {
            'content-type': 'application/x-www-form-urlencoded',
        });
        assert.equal(form.status, 200);
        assert.deepEqual(await form.json(), { item: ['one', 'two'] });
        const oversized = await post(gzipSync(JSON.stringify({ item: 'x'.repeat(65) })), {
            'content-type': 'application/json', 'content-encoding': 'gzip',
        });
        assert.equal(oversized.status, 413);
        assert.equal((await oversized.json()).type, 'entity.too.large');
        const malformed = await post(Buffer.from('not gzip'), {
            'content-type': 'application/json', 'content-encoding': 'gzip',
        });
        assert.equal(malformed.status, 400);
        await malformed.json();
        const following = await post('{"item":"still running"}', { 'content-type': 'application/json' });
        assert.equal(following.status, 200);
        assert.deepEqual(await following.json(), { item: 'still running' });
    } finally {
        await new Promise((resolve, reject) => server.close((error) => error ? reject(error) : resolve()));
    }
});

test('Zod rejects oversized collections before visiting elements and preserves valid transforms', () => {
    const { z } = scriptsRequire('zod');
    for (const kind of ['array', 'set', 'map']) {
        let visits = 0;
        const item = z.string().transform((value) => {
            visits++;
            return value.toUpperCase();
        });
        const schema = kind === 'array' ? z.array(item).max(2)
            : kind === 'set' ? z.set(item).max(2) : z.map(z.string(), item).max(2);
        const collection = (values) => kind === 'array' ? values
            : kind === 'set' ? new Set(values) : new Map(values.map((value) => [value, value]));
        const rejected = schema.safeParse(collection(['one', 'two', 'three']));
        assert.equal(rejected.success, false);
        assert.equal(rejected.error.issues[0].code, 'too_big');
        assert.equal(visits, 0, `${kind} must enforce its cap before element transforms`);
        const valid = schema.parse(collection(['one', 'two']));
        assert.equal(visits, 2);
        assert.deepEqual(kind === 'array' ? valid : [...valid.values()], ['ONE', 'TWO']);
    }
});

test('patched qs and serialize-javascript retain expected behavior', () => {
    const qs = scriptsRequire('qs');
    assert.equal(qs.parse('item=one').item, 'one');
    const oddValue = Object.create(null);
    oddValue.toString = undefined;
    assert.doesNotThrow(() => qs.stringify({ item: [oddValue] }, { arrayFormat: 'comma' }));

    const serialize = scriptsRequire('serialize-javascript');
    const output = serialize(function closingTag() {
        return '</script>';
    });
    assert.doesNotMatch(output, /<\/script/i);
});

test('markdownlint configuration parsers preserve YAML and TOML configuration', () => {
    const yaml = markdownRequire('js-yaml');
    const toml = markdownRequire('smol-toml');
    const expected = { default: true, MD013: { line_length: 120 } };
    assert.deepEqual(yaml.load('default: true\nMD013:\n  line_length: 120\n'), expected);
    const config = toml.parse('default = true\n[MD013]\nline_length = 120\n');
    assert.equal(config.default, expected.default);
    assert.deepEqual({ ...config.MD013 }, expected.MD013);
    const flat = Array.from({ length: 4096 }, (_, index) => `key${index} = 1`).join('\n');
    assert.equal(Object.keys(toml.parse(flat)).length, 4096);
});

test('YAML merge budgets count empty source mappings', () => {
    const yaml = markdownRequire('js-yaml');
    assert.throws(
        () => yaml.load('arr: &arr [{}, {}, {}]\ntargets:\n  - <<: *arr\n  - <<: *arr\n', {
            schema: yaml.YAML11_SCHEMA,
            maxTotalMergeKeys: 4,
        }),
        /merge/i
    );
});

test('markdownlint CLI loads YAML and TOML configs and enforces their rules', () => {
    const cli = path.join(path.dirname(scriptsRequire.resolve('markdownlint-cli/package.json')), 'markdownlint.js');
    for (const extension of ['yaml', 'toml']) {
        const config = path.resolve(__dirname, '..', 'tests', 'dependencies', `markdownlint.${extension}`);
        const args = [cli, '--config', config, '--stdin'];
        const lint = (input) => spawnSync(process.execPath, args, { input, encoding: 'utf8', timeout: 10000 });
        const valid = lint('# Dependency smoke\n\nMath: $x^2$.\n');
        assert.equal(valid.status, 0, valid.error?.message || valid.stderr);
        const invalid = lint(`# Dependency smoke\n\n${'word '.repeat(30).trim()}\n`);
        assert.equal(invalid.status, 1, invalid.error?.message || invalid.stderr);
        assert.match(invalid.stderr, /MD013/);
    }
});

test('cssnano preserves selector semantics with the patched parser major', async () => {
    const postcss = scriptsRequire('postcss');
    const cssnano = scriptsRequire('cssnano');
    const source = String.raw`
        .card, .card, .card:hover { margin: calc(1px + 1px); color: #ffffff; }
        .price\:large[data-label="a b" i]:not(.disabled)::before { content: "x"; }
        .item:nth-child(2n + 1) { padding: 0px 0px 0px 0px; }
    `;
    const result = await postcss([cssnano()]).process(source, { from: undefined });
    assert.equal(result.warnings().length, 0);
    assert.match(result.css, /margin:2px/);
    assert.match(result.css, /color:#fff/);
    assert.match(result.css, /padding:0/);
    const selectors = result.root.nodes.map((node) => node.selector);
    assert.equal(selectors[0], '.card,.card:hover');
    assert.ok(selectors.some((selector) => selector.includes(String.raw`.price\:large`)));
    assert.ok(selectors.some((selector) => /:not\(\.disabled\)::?before/.test(selector)));
    assert.ok(selectors.some((selector) => selector.includes('[data-label="a b" i]')));
    assert.ok(selectors.some((selector) => selector.includes(':nth-child(odd)')));
});

test('css-loader CSS Modules keep local names, global selectors and exports', async () => {
    const postcss = scriptsRequire('postcss');
    const loaderRequire = createRequire(scriptsRequire.resolve('css-loader/package.json'));
    const local = loaderRequire('postcss-modules-local-by-default');
    const scope = loaderRequire('postcss-modules-scope');
    const result = await postcss([
        local(),
        scope({ generateScopedName: (name) => `fixture__${name}` }),
    ]).process('.button:is(.active, :global(.global))::before { content: "x"; }', {
        from: undefined,
    });
    assert.equal(result.warnings().length, 0);
    assert.match(result.css, /\.fixture__button:is\(\.fixture__active,\s*\.global\)::before/);
    assert.match(result.css, /button:\s*fixture__button/);
    assert.match(result.css, /active:\s*fixture__active/);
});

test('micromark still renders inline and display math through the patched KaTeX', async () => {
    const { micromark } = await import(pathToFileURL(markdownRequire.resolve('micromark')).href);
    const { math, mathHtml } = await import(pathToFileURL(markdownRequire.resolve('micromark-extension-math')).href);
    const html = micromark('$x^2$\n\n$$\nx + y\n$$', {
        extensions: [math()],
        htmlExtensions: [mathHtml()],
    });
    assert.match(html, /class="math math-inline"/);
    assert.match(html, /class="math math-display"/);
    assert.match(html, /class="katex"/);
    assert.match(html, /class="katex-display"/);
});

test('KaTeX ignores inherited trust but honors explicitly trusted links', () => {
    const katex = mathRequire('katex');
    const expression = String.raw`\href{https://example.invalid/}{link}`;
    const inherited = katex.renderToString(expression, Object.create({ trust: true }));
    assert.doesNotMatch(inherited, /href=/);
    assert.match(katex.renderToString(expression, { trust: true }), /href="https:\/\/example\.invalid\/"/);
    const previous = Object.getOwnPropertyDescriptor(Object.prototype, 'trust');
    try {
        Object.defineProperty(Object.prototype, 'trust', { value: true, configurable: true, writable: true });
        assert.doesNotMatch(katex.renderToString(expression), /href=/);
    } finally {
        if (previous) {
            Object.defineProperty(Object.prototype, 'trust', previous);
        } else {
            delete Object.prototype.trust;
        }
    }
});

test('SockJS can require UUID v4 and UUID rejects undersized output buffers', () => {
    const uuid = sockjsRequire('uuid');
    assert.match(uuid.v4(), uuidPattern);
    const namespace = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    assert.throws(() => uuid.v3('x', namespace, new Uint8Array(8), 4), RangeError);
    assert.throws(() => uuid.v5('x', namespace, new Uint8Array(8), 4), RangeError);
    assert.throws(() => uuid.v6({}, new Uint8Array(8), 4), RangeError);
});

test('webpack-dev-server starts and completes a real SockJS session handshake', { timeout: 20000 }, async () => {
    const webpack = scriptsRequire('webpack');
    const DevServer = scriptsRequire('webpack-dev-server');
    const compiler = webpack({
        mode: 'development',
        entry: {},
        output: { path: path.resolve(__dirname, '..', 'build', 'dependency-smoke') },
        infrastructureLogging: { level: 'error' },
    });
    const server = new DevServer({
        host: '127.0.0.1',
        port: 0,
        static: false,
        hot: false,
        liveReload: false,
        setupExitSignals: false,
        client: { webSocketTransport: 'sockjs' },
        webSocketServer: 'sockjs',
        devMiddleware: { stats: 'errors-only' },
    }, compiler);
    try {
        await server.start();
        const base = `http://127.0.0.1:${server.server.address().port}`;
        const info = await fetch(`${base}/ws/info`, { signal: AbortSignal.timeout(5000) });
        assert.equal(info.status, 200);
        assert.equal((await info.json()).websocket, true);
        const handshake = await fetch(`${base}/ws/000/dependency-smoke/xhr`, {
            method: 'POST',
            signal: AbortSignal.timeout(5000),
        });
        assert.equal(handshake.status, 200);
        assert.equal((await handshake.text()).trim(), 'o');
        assert.equal(server.webSocketServer.clients.length, 1);
        assert.match(server.webSocketServer.clients[0].id, uuidPattern);
    } finally {
        await server.stop();
        await new Promise((resolve, reject) => compiler.close((error) => error ? reject(error) : resolve()));
    }
});
