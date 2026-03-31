export function getUrlFromLinkValue(value) {
	if (typeof value === 'string') {
		return value;
	}

	if (value && typeof value.url === 'string') {
		return value.url;
	}

	return '';
}
