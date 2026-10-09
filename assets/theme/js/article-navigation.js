(() => {
    const dialog = document.querySelector('[data-restatify-article-dialog]');
    const openButton = document.querySelector('[data-restatify-article-dialog-open]');
    const closeButton = document.querySelector('[data-restatify-article-dialog-close]');
    const pagination = document.querySelector('[data-restatify-article-pagination]');

    if (!dialog || !openButton || !closeButton) {
        return;
    }

    if (pagination) {
        const items = Array.from(dialog.querySelectorAll('[data-restatify-article-item]'));
        const pageSize = Number.parseInt(pagination.dataset.pageSize, 10);
        const previousButton = pagination.querySelector('[data-restatify-article-previous]');
        const nextButton = pagination.querySelector('[data-restatify-article-next]');
        const pageStatus = pagination.querySelector('[data-restatify-article-page-status]');
        const pageCount = Math.ceil(items.length / pageSize);
        let currentPage = 1;

        const renderPage = () => {
            const firstItem = (currentPage - 1) * pageSize;

            items.forEach((item, index) => {
                item.hidden = index < firstItem || index >= firstItem + pageSize;
            });

            previousButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === pageCount;
            pageStatus.textContent = pagination.dataset.pageStatus
                .replace('%1$d', String(currentPage))
                .replace('%2$d', String(pageCount));
        };

        previousButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage -= 1;
                renderPage();
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentPage < pageCount) {
                currentPage += 1;
                renderPage();
            }
        });

        pagination.hidden = false;
        renderPage();
    }

    openButton.addEventListener('click', () => {
        dialog.showModal();
    });

    closeButton.addEventListener('click', () => {
        dialog.close();
        openButton.focus();
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
            openButton.focus();
        }
    });
})();
