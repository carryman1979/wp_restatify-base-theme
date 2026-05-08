function parsePairs(rawValue) {
    if (!rawValue) {
        return [];
    }

    try {
        const parsed = JSON.parse(rawValue);

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed.filter((pair) => Array.isArray(pair) && pair[0] && pair[1]);
    } catch {
        return [];
    }
}

function findSiteLogoSource() {
    const logoSelectors = [
        '.wp-block-site-logo img',
        '.custom-logo-link img',
        'img.custom-logo',
        '.site-logo img'
    ];

    for (const selector of logoSelectors) {
        const img = document.querySelector(selector);

        if (img && img.src) {
            return img.src;
        }
    }

    return '';
}

function randomPairIndex(pairs, currentIndex) {
    if (pairs.length < 2) {
        return currentIndex;
    }

    let nextIndex = currentIndex;

    while (nextIndex === currentIndex) {
        nextIndex = Math.floor(Math.random() * pairs.length);
    }

    return nextIndex;
}

function fitWordSize(wordElement, reserveRatio) {
    const minFontPx = 13;
    const maxReductionSteps = 6;

    wordElement.style.removeProperty('font-size');

    const availableWidth = wordElement.clientWidth;

    if (!availableWidth) {
        return;
    }

    const targetWidth = availableWidth * reserveRatio;
    const baseSize = parseFloat(window.getComputedStyle(wordElement).fontSize) || 16;
    const firstScale = wordElement.scrollWidth > 0 ? Math.min(1, targetWidth / wordElement.scrollWidth) : 1;
    let nextSize = Math.max(minFontPx, baseSize * firstScale * 0.985);

    wordElement.style.fontSize = `${nextSize.toFixed(2)}px`;

    for (let step = 0; step < maxReductionSteps; step += 1) {
        const overflow = wordElement.scrollWidth - targetWidth;

        if (overflow <= 1) {
            break;
        }

        const reductionFactor = Math.max(0.86, Math.min(0.985, targetWidth / (targetWidth + overflow + 2)));
        const reducedSize = Math.max(minFontPx, nextSize * reductionFactor);

        if (Math.abs(reducedSize - nextSize) < 0.15) {
            break;
        }

        nextSize = reducedSize;
        wordElement.style.fontSize = `${nextSize.toFixed(2)}px`;
    }
}

function fitPairWords(leftWord, rightWord) {
    fitWordSize(leftWord, 0.9);
    fitWordSize(rightWord, 0.88);
}

function initOracleStage(stage) {
    const pairs = parsePairs(stage.dataset.wordPairs || '');

    if (!pairs.length) {
        return;
    }

    const pairFrame = stage.querySelector('[data-role="pair-frame"]');
    const leftWord = stage.querySelector('[data-role="word-left"]');
    const rightWord = stage.querySelector('[data-role="word-right"]');

    if (!pairFrame || !leftWord || !rightWord) {
        return;
    }

    const logoOverlay = stage.querySelector('[data-role="logo-overlay"]');
    const logoImage = stage.querySelector('[data-role="logo-image"]');
    const logoFallback = stage.querySelector('[data-role="logo-fallback"]');
    const fallbackLogo = stage.dataset.logoFallback || '';
    const randomMode = stage.dataset.randomMode === '1';
    const cycleMs = Math.max(1600, Number(stage.dataset.cycleMs) || 4200);
    const driftRange = Math.max(2, Math.min(24, Number(stage.dataset.driftRange) || 14));
    const logoIntervalMs = Math.max(30000, Number(stage.dataset.logoIntervalMs) || 300000);
    const logoVisibleMs = Math.max(800, Number(stage.dataset.logoVisibleMs) || 3500);
    const rightDelayMs = Math.min(1200, Math.max(320, Math.round(cycleMs * 0.34)));
    const fadeOutMs = 260;

    let pairIndex = Math.floor(Math.random() * pairs.length);
    let rightTimer = null;
    let logoHideTimer = null;
    let resizeRaf = null;

    const randomOffset = () => {
        const x = ((Math.random() * 2) - 1) * driftRange;
        const y = ((Math.random() * 2) - 1) * (driftRange * 0.65);
        pairFrame.style.setProperty('--rs-oracle-offset-x', `${x.toFixed(2)}%`);
        pairFrame.style.setProperty('--rs-oracle-offset-y', `${y.toFixed(2)}%`);
    };

    const showPair = () => {
        const pair = pairs[pairIndex];
        randomOffset();
        leftWord.textContent = pair[0];
        rightWord.textContent = pair[1];
        fitPairWords(leftWord, rightWord);
        leftWord.classList.add('is-visible');
        rightWord.classList.remove('is-visible');

        if (rightTimer) {
            window.clearTimeout(rightTimer);
        }

        rightTimer = window.setTimeout(() => {
            rightWord.classList.add('is-visible');
        }, rightDelayMs);

        if (randomMode) {
            pairIndex = randomPairIndex(pairs, pairIndex);
        } else {
            pairIndex = (pairIndex + 1) % pairs.length;
        }
    };

    const handleResize = () => {
        if (resizeRaf) {
            window.cancelAnimationFrame(resizeRaf);
        }

        resizeRaf = window.requestAnimationFrame(() => {
            fitPairWords(leftWord, rightWord);
        });
    };

    const hidePair = () => {
        leftWord.classList.remove('is-visible');
        rightWord.classList.remove('is-visible');
    };

    const cyclePair = () => {
        hidePair();

        window.setTimeout(() => {
            showPair();
        }, fadeOutMs);
    };

    const showLogo = () => {
        if (!logoOverlay || !logoImage || !logoFallback) {
            return;
        }

        const siteLogo = findSiteLogoSource();
        const logoSrc = siteLogo || fallbackLogo;

        if (logoSrc) {
            logoImage.src = logoSrc;
            logoImage.style.display = '';
            logoFallback.style.display = 'none';
        } else {
            logoImage.removeAttribute('src');
            logoImage.style.display = 'none';
            logoFallback.style.display = '';
        }

        logoOverlay.classList.add('is-visible');

        if (logoHideTimer) {
            window.clearTimeout(logoHideTimer);
        }

        logoHideTimer = window.setTimeout(() => {
            logoOverlay.classList.remove('is-visible');
        }, logoVisibleMs);
    };

    const scheduleNextLogo = () => {
        window.setTimeout(() => {
            showLogo();
            scheduleNextLogo();
        }, logoIntervalMs);
    };

    showLogo();
    handleResize();
    window.addEventListener('resize', handleResize);
    window.setTimeout(() => {
        showPair();
        window.setInterval(cyclePair, cycleMs);
    }, logoVisibleMs + 140);

    scheduleNextLogo();
}

function initAllOracleStages() {
    document.querySelectorAll('.restatify-oracle-stage').forEach(initOracleStage);
}

if (document.readyState === 'loading') {
    window.addEventListener('DOMContentLoaded', initAllOracleStages);
} else {
    initAllOracleStages();
}
