describe('single-post article navigation', () => {
    function mountArticleNavigation(articleCount) {
        const articleItems = Array.from(
            { length: articleCount },
            (_, index) => `<li data-restatify-article-item>${index + 1}</li>`
        ).join('');
        const pagination = articleCount > 10
            ? `
                <nav
                    data-restatify-article-pagination
                    data-page-size="10"
                    data-page-status="Page %1$d of %2$d"
                    hidden
                >
                    <button data-restatify-article-previous></button>
                    <span data-restatify-article-page-status></span>
                    <button data-restatify-article-next></button>
                </nav>
            `
            : '';

        document.body.innerHTML = `
            <button data-restatify-article-dialog-open></button>
            <dialog data-restatify-article-dialog>
                <button data-restatify-article-dialog-close></button>
                <ul>${articleItems}</ul>
                ${pagination}
            </dialog>
        `;

        const dialog = document.querySelector('[data-restatify-article-dialog]');
        dialog.showModal = jest.fn(() => dialog.setAttribute('open', ''));
        dialog.close = jest.fn(() => dialog.removeAttribute('open'));
    }

    beforeEach(() => {
        jest.resetModules();
        mountArticleNavigation(12);
    });

    function loadNavigation() {
        require('../../../assets/theme/js/article-navigation.js');
    }

    it('opens the article dialog from its trigger', () => {
        loadNavigation();
        document.querySelector('[data-restatify-article-dialog-open]').click();

        expect(document.querySelector('[data-restatify-article-dialog]').showModal).toHaveBeenCalledTimes(1);
    });

    it('closes the dialog and restores focus from its close button', () => {
        loadNavigation();
        const openButton = document.querySelector('[data-restatify-article-dialog-open]');
        const dialog = document.querySelector('[data-restatify-article-dialog]');
        const closeButton = document.querySelector('[data-restatify-article-dialog-close]');
        const focusSpy = jest.spyOn(openButton, 'focus');

        closeButton.click();

        expect(dialog.close).toHaveBeenCalledTimes(1);
        expect(focusSpy).toHaveBeenCalledTimes(1);
    });

    it('closes the dialog when its backdrop is clicked', () => {
        loadNavigation();
        const dialog = document.querySelector('[data-restatify-article-dialog]');

        dialog.dispatchEvent(new MouseEvent('click', { bubbles: true }));

        expect(dialog.close).toHaveBeenCalledTimes(1);
    });

    it('paginates the article list and disables navigation at the ends', () => {
        loadNavigation();
        const items = Array.from(document.querySelectorAll('[data-restatify-article-item]'));
        const previousButton = document.querySelector('[data-restatify-article-previous]');
        const nextButton = document.querySelector('[data-restatify-article-next]');
        const pageStatus = document.querySelector('[data-restatify-article-page-status]');

        expect(items.filter((item) => !item.hidden)).toHaveLength(10);
        expect(previousButton.disabled).toBe(true);
        expect(pageStatus.textContent).toBe('Page 1 of 2');

        nextButton.click();

        expect(items.filter((item) => !item.hidden)).toHaveLength(2);
        expect(items.slice(0, 10).every((item) => item.hidden)).toBe(true);
        expect(previousButton.disabled).toBe(false);
        expect(nextButton.disabled).toBe(true);
        expect(pageStatus.textContent).toBe('Page 2 of 2');

        previousButton.click();

        expect(items.filter((item) => !item.hidden)).toHaveLength(10);
    });

    it.each([0, 1, 9, 10])('shows all %i articles without pagination', (articleCount) => {
        mountArticleNavigation(articleCount);
        loadNavigation();

        const items = Array.from(document.querySelectorAll('[data-restatify-article-item]'));

        expect(items.filter((item) => !item.hidden)).toHaveLength(articleCount);
        expect(document.querySelector('[data-restatify-article-pagination]')).toBeNull();
    });

    it.each([
        [11, 1, 2],
        [20, 10, 2],
        [21, 1, 3],
    ])('paginates %i articles with %i visible on the last page', (articleCount, lastPageCount, pageCount) => {
        mountArticleNavigation(articleCount);
        loadNavigation();
        const nextButton = document.querySelector('[data-restatify-article-next]');
        Array.from({ length: pageCount - 1 }).forEach(() => nextButton.click());

        const items = Array.from(document.querySelectorAll('[data-restatify-article-item]'));
        const pageStatus = document.querySelector('[data-restatify-article-page-status]');

        expect(items.filter((item) => !item.hidden)).toHaveLength(lastPageCount);
        expect(pageStatus.textContent).toBe(`Page ${pageCount} of ${pageCount}`);
    });
});
