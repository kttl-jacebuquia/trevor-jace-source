const VIMEO_OEMBED_URL = 'https://vimeo.com/api/oembed.json';

export const getVimeoVideoData = async (videoURL) => {
	try {
		const response = await fetch(`${VIMEO_OEMBED_URL}?url=${videoURL}`);
		const data = await response.json()
		return data || null;
	} catch (err) {
		console.warn(err);
		return null;
	}
}

export const isVimeoVideo = (videoURL) => (
	/^https?:\/\/.+vimeo\.com\/.+/i.test(videoURL)
)
