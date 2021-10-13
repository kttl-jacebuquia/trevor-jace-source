let youtubeScriptInjected = false;

let creatingDummyPlayer = false;
let dummyPlayer: YT.Player;

interface WindowWithYT {
	YT?: {
		Player: (args: any) => any;
		loaded: number;
		ready: (args: any) => any;
	};
}

// Dummy youtube player to get certain video details
const getDummyYoutubePlayer = async () => {
	if (!dummyPlayer) {
		if (!creatingDummyPlayer) {
			creatingDummyPlayer = true;

			// Load YT Player API
			await loadYTPlayerAPI();

			// Create a hidden, dummy element to use as player ifram
			const dummyElement = Object.assign(document.createElement('div'), {
				hidden: true,
			});
			const dummyYTPlaceholder = document.createElement('div');
			dummyElement.appendChild(dummyYTPlaceholder);
			document.body.appendChild(dummyElement);

			await new Promise((onReady) => {
				dummyPlayer = new YT.Player(dummyYTPlaceholder, {
					videoId: '',
					events: { onReady },
				});
			});
		}
		await new Promise<void>((resolve) => {
			const checkerInterval = setInterval(() => {
				if (dummyPlayer) {
					clearInterval(checkerInterval);
					resolve();
				}
			}, 100);
		});
	}

	return dummyPlayer;
};

export const getYoutubeVideoData = async (youtubeVideoURL) => {
	if (!isYoutubeVideo(youtubeVideoURL)) {
		return null;
	}

	const [, youtubeID] =
		youtubeVideoURL.match(/(?:(?:\.be|embed|\/v)\/|v=)([^?#\/&]+)/i) || [];

	if (youtubeID) {
		const youtubePlayer = await getDummyYoutubePlayer();

		console.log({ youtubePlayer });

		await new Promise((resolve) => {
			youtubePlayer.addEventListener(
				'onStateChange',
				({ data, ...event }) => {
					console.log({ data, event });
				}
			);
			resolve();
			// youtubePlayer.loadVideoById(youtubeID);
		});

		return {
			video_id: youtubeID,
			thumbnail_url: `https://img.youtube.com/vi/${youtubeID}/mqdefault.jpg`,
			poster_url: `https://img.youtube.com/vi/${youtubeID}/maxresdefault.jpg`,
		};
	}

	return null;
};

export const isYoutubeVideo = (youtubeVideoURL: string) =>
	/^https?:\/\/(www\.)?youtu(\.be|be\.com)\//i.test(youtubeVideoURL);

export const loadYTPlayerAPI = function () {
	return new Promise((resolve) => {
		const _window = window as WindowWithYT;

		if (_window.YT?.loaded) {
			return (resolve as () => any)();
		}

		// Check until YT is loaded
		const loaderInterval = setInterval(() => {
			if (_window.YT) {
				clearInterval(loaderInterval);
				_window.YT.ready(resolve);
			}
		}, 100);

		if (!youtubeScriptInjected) {
			youtubeScriptInjected = true;

			// Inject YT script
			const tag = document.createElement('script');
			tag.src = '//www.youtube.com/iframe_api';
			const firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		}
	});
};
