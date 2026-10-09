describe('blog navigation editor/runtime contract', () => {
    it('registers both dynamic blocks with server-side preview and no saved markup', () => {
        jest.resetModules();
        const registerBlockType = jest.fn();
        const createElement = jest.fn();
        const serverSideRender = jest.fn();
        global.wp = {
            blocks: { registerBlockType },
            element: { createElement },
            i18n: { __: (text) => text },
            serverSideRender,
        };
        require('../../../assets/theme/js/blog-navigation-editor.js');
        expect(registerBlockType.mock.calls.map(([name]) => name)).toEqual([
            'restatify/blog-navigation', 'restatify/post-adjacent-navigation',
        ]);
        registerBlockType.mock.calls.forEach(([name, config]) => {
            expect(config.apiVersion).toBe(3);
            expect(config.save()).toBeNull();
            config.edit({ attributes: {} });
            expect(createElement).toHaveBeenCalledWith(serverSideRender, { block: name, attributes: {} });
        });
        delete global.wp;
    });
});
