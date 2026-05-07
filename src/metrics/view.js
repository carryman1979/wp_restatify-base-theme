const DEFAULT_DURATION_MS = 1500;
const MIN_DURATION_MS = 200;
const MAX_DURATION_MS = 10000;

function clampDuration(value) {
    const numeric = Number(value);

    if (!Number.isFinite(numeric)) {
        return DEFAULT_DURATION_MS;
    }

    return Math.max(MIN_DURATION_MS, Math.min(MAX_DURATION_MS, Math.round(numeric)));
}

function parseAnimatedNumber(rawText) {
    const text = String(rawText || '').trim();
    const match = text.match(/[+\-−–—]?\d+(?:[.,]\d+)?/);

    if (!match || typeof match.index !== 'number') {
        return null;
    }

    const token = match[0];
    const tokenStart = match.index;
    const tokenEnd = tokenStart + token.length;
    const normalized = token.replace(/[−–—]/g, '-').replace(',', '.');
    const numericValue = Number(normalized);

    if (!Number.isFinite(numericValue)) {
        return null;
    }

    const decimalPart = token.split(/[.,]/)[1] || '';

    return {
        sourceText: text,
        prefix: text.slice(0, tokenStart),
        suffix: text.slice(tokenEnd),
        decimals: decimalPart.length,
        target: numericValue,
        explicitPlus: token.startsWith('+')
    };
}

function formatAnimatedValue(currentValue, parsed) {
    const absolute = Math.abs(currentValue);
    const valueText = parsed.decimals > 0 ? absolute.toFixed(parsed.decimals) : String(Math.round(absolute));

    let signedText = valueText;

    if (currentValue < 0) {
        signedText = `-${valueText}`;
    } else if (parsed.explicitPlus) {
        signedText = `+${valueText}`;
    }

    return `${parsed.prefix}${signedText}${parsed.suffix}`;
}

function isFullyInViewport(element) {
    const rect = element.getBoundingClientRect();
    const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= viewportHeight &&
        rect.right <= viewportWidth
    );
}

function animateMetricsOnce(block) {
    const mode = (block.dataset.animationMode || 'count-up').trim();

    if (mode !== 'count-up') {
        return;
    }

    if (block.dataset.metricsAnimated === '1') {
        return;
    }

    const duration = clampDuration(block.dataset.animationDuration);
    const candidates = Array.from(block.querySelectorAll('.item-number strong, .item-number'));
    const targets = candidates.filter((node, index, all) => all.indexOf(node) === index);

    if (targets.length === 0) {
        return;
    }

    const metrics = targets
        .map((node) => {
            const parsed = parseAnimatedNumber(node.textContent);

            if (!parsed) {
                return null;
            }

            return { node, parsed };
        })
        .filter(Boolean);

    if (metrics.length === 0) {
        return;
    }

    metrics.forEach(({ node, parsed }) => {
        node.textContent = formatAnimatedValue(0, parsed);
    });

    let started = false;
    let rafCheckId = 0;

    const stopWatching = () => {
        if (rafCheckId) {
            window.cancelAnimationFrame(rafCheckId);
            rafCheckId = 0;
        }

        window.removeEventListener('scroll', scheduleCheck, { passive: true });
        window.removeEventListener('resize', scheduleCheck);
    };

    const startAnimation = () => {
        if (started) {
            return;
        }

        started = true;
        block.dataset.metricsAnimated = '1';
        stopWatching();

        const startTime = performance.now();

        const tick = (now) => {
            const progress = Math.min(1, (now - startTime) / duration);
            const eased = 1 - Math.pow(1 - progress, 3);

            metrics.forEach(({ node, parsed }) => {
                node.textContent = formatAnimatedValue(parsed.target * eased, parsed);
            });

            if (progress < 1) {
                window.requestAnimationFrame(tick);
                return;
            }

            metrics.forEach(({ node, parsed }) => {
                node.textContent = parsed.sourceText;
            });
        };

        window.requestAnimationFrame(tick);
    };

    const checkVisibilityAndStart = () => {
        rafCheckId = 0;

        if (started) {
            return;
        }

        const allVisible = targets.every(isFullyInViewport);

        if (allVisible) {
            startAnimation();
        }
    };

    function scheduleCheck() {
        if (rafCheckId || started) {
            return;
        }

        rafCheckId = window.requestAnimationFrame(checkVisibilityAndStart);
    }

    window.addEventListener('scroll', scheduleCheck, { passive: true });
    window.addEventListener('resize', scheduleCheck);
    scheduleCheck();
}

function initMetricsAnimations() {
    document.querySelectorAll('.restatify-metrics-block').forEach(animateMetricsOnce);
}

if (document.readyState === 'loading') {
    window.addEventListener('DOMContentLoaded', initMetricsAnimations, { once: true });
} else {
    initMetricsAnimations();
}
