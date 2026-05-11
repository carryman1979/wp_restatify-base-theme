import { getUrlFromLinkValue } from '../link-utils';

describe('getUrlFromLinkValue', () => {
    it('returns a string input unchanged', () => {
        expect(getUrlFromLinkValue('https://restatify.tech')).toBe('https://restatify.tech');
    });

    it('returns the url from object values', () => {
        expect(getUrlFromLinkValue({ url: '#restatify-booking' })).toBe('#restatify-booking');
    });

    it('returns empty string for unsupported values', () => {
        expect(getUrlFromLinkValue(null)).toBe('');
        expect(getUrlFromLinkValue({})).toBe('');
    });
});
