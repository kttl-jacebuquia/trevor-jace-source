let youtubeScriptInjected = false;

export const getYoutubeVideoData = async (youtubeVideoURL) => {
	if (!isYoutubeVideo(youtubeVideoURL)) {
		return null;
	}

	const [, youtubeID] =
		youtubeVideoURL.match(/(?:(?:\.be|embed|\/v)\/|v=)([^?#\/&]+)/i) || [];

	if (youtubeID) {
		return {
			video_id: youtubeID,
			thumbnail_url: `https://img.youtube.com/vi/${youtubeID}/mqdefault.jpg`,
			poster_url: `https://img.youtube.com/vi/${youtubeID}/maxresdefault.jpg`,
		};
	}

	return null;
};

export const isYoutubeVideo = async (youtubeVideoURL) =>
	/^https?:\/\/(www\.)?youtu(\.be|be\.com)\//i.test(youtubeVideoURL);

export const loadYTPlayerAPI = function () {
	return new Promise((resolve) => {
		if ( window.YT && window.YT.loaded ) {
			return resolve();
		}

		// Check until YT is loaded
		const loaderInterval = setInterval(() => {
			if ( window.YT ) {
				clearInterval(loaderInterval);
				window.YT.ready(resolve);
			}
		}, 100);

		if ( !youtubeScriptInjected ) {
			youtubeScriptInjected = true;

			// Inject YT script
			const tag = document.createElement('script');
			tag.src = '//www.youtube.com/iframe_api';
			const firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		}
	});
};
