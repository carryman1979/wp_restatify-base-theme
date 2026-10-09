(() => {
    const { registerBlockType } = wp.blocks;
    const { createElement } = wp.element;
    const { __ } = wp.i18n;
    const ServerSideRender = wp.serverSideRender;

    [
        {
            name: 'restatify/blog-navigation',
            title: __('Restatify Blog-Navigation', 'restatify-base'),
        },
        {
            name: 'restatify/post-adjacent-navigation',
            title: __('Restatify Artikel-Navigation', 'restatify-base'),
        },
    ].forEach(({ name, title }) => {
        registerBlockType(name, {
            apiVersion: 3,
            title,
            category: 'theme',
            edit: (props) => createElement(ServerSideRender, {
                block: name,
                attributes: props.attributes,
            }),
            save: () => null,
        });
    });
})();
