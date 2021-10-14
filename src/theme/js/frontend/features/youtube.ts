/* eslint-disable camelcase */
const INACTIVE_DUMMY_PLAYER_TIMEOUT = 10000;

let youtubeScriptInjected = false;
let creatingDummyPlayer = false;
let dummyPlayer: YT.Player | null;
let dummyPlayerIdleTimer: number;
let dummyElement: HTMLElement | null;

interface WindowWithYT {
	YT?: {
		Player: (args: any) => any;
		loaded: number;
		ready: (args: any) => any;
	};
}

interface YTPlayerExtended extends YT.Player {
	getVideoData: () => {
		video_id: string;
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
			dummyElement = Object.assign(document.createElement('div'), {
				hidden: true,
			});
			const dummyYTPlaceholder = document.createElement('div');
			dummyElement.appendChild(dummyYTPlaceholder);
			document.body.appendChild(dummyElement);

			const player = new YT.Player(dummyYTPlaceholder, {
				videoId: '',
				events: {
					onReady: () => {
						dummyPlayer = player;
					},
				},
			});
		}
		await new Promise<void>((resolve) => {
			const checkerInterval = setInterval(() => {
				if (dummyPlayer) {
					clearInterval(checkerInterval);
					creatingDummyPlayer = false;
					resolve();
				}
			}, 100);
		});
	}

	resetInactiveDummyPlayerTimeout();

	return dummyPlayer;
};

const resetInactiveDummyPlayerTimeout = () => {
	clearTimeout(dummyPlayerIdleTimer);
	dummyPlayerIdleTimer = window.setTimeout(() => {
		dummyElement?.parentElement?.removeChild(dummyElement);
		dummyPlayer = null;
		dummyElement = null;
		clearTimeout(dummyPlayerIdleTimer);
	}, INACTIVE_DUMMY_PLAYER_TIMEOUT);
};

export const getYoutubeVideoData = async (youtubeVideoURL: string) => {
	if (!isYoutubeVideo(youtubeVideoURL)) {
		return null;
	}

	const [, youtubeID] =
		youtubeVideoURL.match(/(?:(?:\.be|embed|\/v)\/|v=)([^?#\/&]+)/i) || [];

	if (youtubeID) {
		const youtubePlayer = await getDummyYoutubePlayer();

		const duration: number = await new Promise<number>((resolve) => {
			youtubePlayer.addEventListener(
				'onStateChange',
				({
					data,
					target: player,
				}: YT.PlayerEvent & { data: number }) => {
					if (data === 5) {
						const { video_id } = (
							player as YTPlayerExtended
						).getVideoData();
						const _duration = player.getDuration();
						if (video_id === youtubeID) {
							resolve(_duration);
						}
					}
				}
			);
			youtubePlayer.cueVideoById(youtubeID, 1);
		});

		return {
			video_id: youtubeID,
			duration,
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
