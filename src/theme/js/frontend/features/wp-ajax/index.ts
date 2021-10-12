/* eslint-disable camelcase */
export type T_FetchMethod = 'GET' | 'POST';

export interface I_FetchParams {
	method: T_FetchMethod;
	headers?: { [key: string]: string };
	body?: string;
}

export interface I_WPAjaxArgs {
	action: string;
	params?: { [key: string]: any };
	data?: { [key: string]: any };
}

const AJAX_ENDPOINT = '/wp-admin/admin-ajax.php';

const objectToParams = (object) =>
	Object.entries(object)
		.map((entry) => entry.join('='))
		.join('&');

/**
 *
 * @param {string} param.action WP ajax action
 * @param {object} param.params Get params to append into the ajax url
 * @param {object} param.data POST data
 * @returns
 */
export const WPAjax = async ({
	action = '',
	params = {},
	data = {},
}: I_WPAjaxArgs) => {
	if (!action) {
		return Promise.resolve(null);
	}

	const url = [
		AJAX_ENDPOINT,
		objectToParams({
			action,
			...params,
		}),
	].join('?');

	const method = Object.keys(data).length ? 'POST' : 'GET';

	const headers = {
		'content-type': 'application/x-www-form-urlencoded',
	};

	const fetchParams: I_FetchParams = { method, headers };

	if (Object.keys(data).length) {
		fetchParams.body = objectToParams(data);
	}

	const response = await fetch(url, fetchParams);
	return await response.json();
};
