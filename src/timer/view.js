const UNIT_CONFIG = [
    { key: 'years', singular: 'Jahr', plural: 'Jahre' },
    { key: 'months', singular: 'Monat', plural: 'Monate' },
    { key: 'days', singular: 'Tag', plural: 'Tage' },
    { key: 'hours', singular: 'Stunde', plural: 'Stunden' },
    { key: 'minutes', singular: 'Minute', plural: 'Minuten' },
    { key: 'seconds', singular: 'Sekunde', plural: 'Sekunden' }
];

function toInt(value) {
    const parsed = Number(value);

    if (!Number.isFinite(parsed)) {
        return 0;
    }

    return Math.max(0, Math.floor(parsed));
}

function advanceCursor(cursor, unitKey) {
    const next = new Date(cursor.getTime());

    if (unitKey === 'years') {
        next.setFullYear(next.getFullYear() + 1);
        return next;
    }

    if (unitKey === 'months') {
        next.setMonth(next.getMonth() + 1);
        return next;
    }

    if (unitKey === 'days') {
        next.setDate(next.getDate() + 1);
        return next;
    }

    if (unitKey === 'hours') {
        next.setHours(next.getHours() + 1);
        return next;
    }

    if (unitKey === 'minutes') {
        next.setMinutes(next.getMinutes() + 1);
        return next;
    }

    next.setSeconds(next.getSeconds() + 1);
    return next;
}

function getUnitLabel(unit, value) {
    return value === 1 ? unit.singular : unit.plural;
}

function getDisplayUnits(nowDate, targetDate) {
    if (!(targetDate instanceof Date) || targetDate <= nowDate) {
        return [{ key: 'seconds', label: 'Sekunden', value: 0 }];
    }

    const cursor = new Date(nowDate.getTime());
    const values = {
        years: 0,
        months: 0,
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0
    };

    UNIT_CONFIG.forEach((unit) => {
        let next = advanceCursor(cursor, unit.key);

        while (next <= targetDate) {
            values[unit.key] += 1;
            cursor.setTime(next.getTime());
            next = advanceCursor(cursor, unit.key);
        }
    });

    const units = UNIT_CONFIG.map((unit) => ({
        key: unit.key,
        label: getUnitLabel(unit, values[unit.key]),
        value: values[unit.key]
    }));
    let startIndex = units.findIndex((unit, index) => unit.value > 0 && index < units.length - 1);

    if (startIndex < 0) {
        startIndex = units.length - 1;
    }

    return units.slice(startIndex);
}

function createTimerItem(unit) {
    const item = document.createElement('div');
    item.className = 'restatify-timer-item';
    item.dataset.unit = unit.key;

    const value = document.createElement('p');
    value.className = 'restatify-timer-value mbr-fonts-style display-2';
    value.dataset.role = 'value';

    const strong = document.createElement('strong');
    strong.textContent = String(unit.value);
    value.appendChild(strong);

    const label = document.createElement('p');
    label.className = 'restatify-timer-label mbr-fonts-style display-7';
    label.textContent = unit.label;

    item.appendChild(value);
    item.appendChild(label);

    return item;
}

function setGridReflowAnimation(grid) {
    grid.classList.remove('is-reflowing');
    void grid.offsetWidth;
    grid.classList.add('is-reflowing');

    window.setTimeout(() => {
        grid.classList.remove('is-reflowing');
    }, 420);
}

function renderCountdown(element, units, previousKeys) {
    const grid = element.querySelector('[data-role="grid"]');

    if (!grid) {
        return previousKeys;
    }

    const nextKeys = units.map((unit) => unit.key);
    const hasChanged = previousKeys.length !== nextKeys.length || previousKeys.some((key, index) => key !== nextKeys[index]);

    if (hasChanged) {
        setGridReflowAnimation(grid);
    }

    grid.innerHTML = '';

    units.forEach((unit) => {
        const item = createTimerItem(unit);
        if (hasChanged) {
            item.classList.add('is-entering');
            window.setTimeout(() => {
                item.classList.remove('is-entering');
            }, 380);
        }
        grid.appendChild(item);
    });

    return nextKeys;
}

function showInvalidState(element) {
    const grid = element.querySelector('[data-role="grid"]');
    if (grid) {
        grid.innerHTML = '';
    }

    let empty = element.querySelector('[data-role="empty"]');
    if (!empty) {
        empty = document.createElement('p');
        empty.className = 'restatify-timer-empty mbr-fonts-style display-7';
        empty.dataset.role = 'empty';
        empty.textContent = 'Bitte einen gueltigen Zielzeitpunkt waehlen.';
        element.appendChild(empty);
    }
}

function hideInvalidState(element) {
    const empty = element.querySelector('[data-role="empty"]');
    if (empty) {
        empty.remove();
    }
}

function initCountdown(element) {
    const targetMs = Number(element.dataset.targetMs || '');

    if (!Number.isFinite(targetMs) || targetMs <= 0) {
        showInvalidState(element);
        return;
    }

    hideInvalidState(element);

    let previousKeys = [];

    const tick = () => {
        const units = getDisplayUnits(new Date(), new Date(targetMs));
        previousKeys = renderCountdown(element, units, previousKeys);
    };

    tick();
    window.setInterval(tick, 1000);
}

function initAllCountdowns() {
    document.querySelectorAll('.restatify-countdown').forEach(initCountdown);
}

if (document.readyState === 'loading') {
    window.addEventListener('DOMContentLoaded', initAllCountdowns);
} else {
    initAllCountdowns();
}
