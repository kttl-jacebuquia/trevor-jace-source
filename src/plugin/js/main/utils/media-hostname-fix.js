/**
 * If image hosts are different converts it to current host.
 *
 * @param img
 * @returns {{url}|*}
 */
export default function mediaHostnameFix(img) {
	if (!img || !img.url) {
		return img;
	}

	const url = new URL(img.url);

	if (url.host === window.location.host) {
		return img;
	}

	/* Changing only the host is not working... */
	url.hostname = window.location.hostname;
	url.protocol = window.location.protocol;
	url.port = window.location.port;
	img.url = url.toString();

	return img;
}
