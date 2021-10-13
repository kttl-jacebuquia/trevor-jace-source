import VimeoPlayer from '@vimeo/player';
import Component from '../../Component';
import { loadYTPlayerAPI } from '../youtube';
import { VideoType } from './playlist';

export interface LessonDataFragment {
	videoType?: VideoType;
	videoId?: string;
}

export interface LessonData extends LessonDataFragment {
	lessonId?: string;
	number?: number | string;
	src?: string;
	poster?: string;
	title?: string;
	description?: string;
	downloadLabel?: string;
	downloadURL?: string;
}

export interface LessonState {
	lesson: LessonData;
	lessonId?: string;
	posterLoaded: boolean;
	iframeLoaded: boolean;
	playing: boolean;
}

export const LESSONS_WATCHED_KEY = 'TW_LESSONS_WATCHED';

export default class Lesson extends Component {
	youtubePlayer?: YT.Player;
	vimeoPlayer?: VimeoPlayer;
	children?: {
		poster: HTMLImageElement;
		player: HTMLElement;
		iframe: HTMLIFrameElement;
		play: HTMLButtonElement;
		title: HTMLElement;
		body: HTMLElement;
		download: HTMLButtonElement;
		vimeoPlaceholder: HTMLElement;
		youtubePlaceholder: HTMLElement;
	};

	// Defines the element selector which will initialize this component
	static selector = '.lessons-video-player__lesson';

	// Defines children that needs to be queried as part of this component
	static children = {
		player: '.lessons-video-player__player',
		poster: '.lessons-video-player__player-poster img',
		iframe: '.lessons-video-player__iframe',
		play: '.lessons-video-player__play',
		title: '.lessons-video-player__title',
		body: '.lessons-video-player__body',
		download: '.lessons-video-player__download-link',
		vimeoPlaceholder: '.lessons-video-player__vimeo-placeholder',
		youtubePlaceholder: '.lessons-video-player__youtube-iframe-replacement',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		player: { transitionend: this.onPlayerTransitionEnd.bind(this) },
		poster: { load: this.onPosterLoaded.bind(this) },
		play: { click: this.onPlayClick.bind(this) },
		iframe: { load: this.onVideoLoaded.bind(this) },
	};

	playerLoadingClassname = 'lessons-video-player__player--loading';
	playerPlayingClassname = 'lessons-video-player__player--playing';

	// Defines initial State
	state: LessonState = {
		lesson: {
			lessonId: '',
			number: '',
			src: '',
			poster: '',
			title: '',
			description: '',
			downloadLabel: '',
			downloadURL: '',
			videoType: null,
			videoId: '',
		},
		lessonId: '',
		posterLoaded: false,
		iframeLoaded: false,
		playing: false,
	};

	loadLessonData(lessonData: LessonData, willAutoplay: boolean = false) {
		if (lessonData.lessonId) {
			const lesson = this.sanitizeLessonData(lessonData);
			const lessonId = lessonData.lessonId;
			this.setState({
				lesson,
				lessonId,
				posterLoaded: false,
				iframeLoaded: false,
				playing: willAutoplay,
			});

			this.loadLessonVideo(lesson);
		}
	}

	// Ensures a valid lesson data being passed
	sanitizeLessonData(rawLessonData: LessonData) {
		const curatedLessonData = {};

		Object.keys(this.state.lesson).forEach((dataKey) => {
			curatedLessonData[dataKey] = rawLessonData[dataKey] || '';
		});

		return curatedLessonData;
	}

	onPlayerTransitionEnd(e) {
		if (e.currentTarget === e.target) {
			if (!this.state.posterLoaded) {
				(this.children?.poster as HTMLImageElement).src =
					this.state.lesson.poster || '';
			}
		}
	}

	onPosterLoaded() {
		this.setState({ posterLoaded: true });
	}

	onVideoLoaded() {
		const { playing } = this.state;
		this.setState({ iframeLoaded: true });

		if (playing) {
			this.playVideo();
		}
	}

	playVideo() {
		const { lesson } = this.state;

		switch (lesson.videoType) {
			case 'youtube':
				this.youtubePlayer?.playVideo();
				break;
			case 'vimeo':
				this.vimeoPlayer?.play();
		}
	}

	async loadLessonVideo(lesson: LessonData) {
		// Supports vimeo video for now
		switch (lesson.videoType) {
			case 'vimeo':
				this.loadVimeoVideo(lesson.videoId || '');
				break;
			case 'youtube':
				this.loadYoutubeVideo(lesson.videoId || '');
				break;
		}
	}

	async loadVimeoVideo(videoID: string | number) {
		if (!videoID) {
			return;
		}

		if (!this.vimeoPlayer) {
			this.vimeoPlayer = new VimeoPlayer(
				this.children?.vimeoPlaceholder as HTMLElement,
				{
					id: videoID,
				}
			);
			this.vimeoPlayer.on('loaded', this.onVideoLoaded.bind(this));
			this.vimeoPlayer.on('play', this.onVideoPlay.bind(this));
			this.vimeoPlayer.on('ended', this.onVideoEnd.bind(this));
		} else {
			this.vimeoPlayer
				.loadVideo(videoID as number)
				.then(this.onVideoLoaded.bind(this));
		}
	}

	async loadYoutubeVideo(videoID: string) {
		if (!videoID) {
			return;
		}

		await loadYTPlayerAPI();

		if (!this.youtubePlayer) {
			this.youtubePlayer = new window.YT.Player(
				this.children?.youtubePlaceholder as HTMLElement,
				{
					videoId: videoID,
					events: {
						onReady: () => {
							this.youtubePlayer?.cueVideoById(videoID);
						},
					},
				}
			);
			this.youtubePlayer.addEventListener(
				'onStateChange',
				this.onYoutubeVideoStateChange.bind(this)
			);
		} else {
			this.youtubePlayer.cueVideoById(videoID);
		}
	}

	onPlayClick(e: Event) {
		e.preventDefault();

		switch (this.state.lesson.videoType) {
			case 'vimeo':
				this.vimeoPlayer && this.vimeoPlayer.play();
				this.youtubePlayer?.stopVideo && this.youtubePlayer.stopVideo();
				break;
			case 'youtube':
				this.youtubePlayer && this.youtubePlayer.playVideo();
				this.vimeoPlayer?.pause && this.vimeoPlayer.pause();
				break;
		}

		this.setState({ playing: true });
	}

	onYoutubeVideoStateChange({ data }: YT.PlayerEvent & { data: number }) {
		switch (data) {
			case -1:
				this.onVideoLoaded();
				break;
			case YT.PlayerState.ENDED:
				this.onVideoEnd();
				break;
			case YT.PlayerState.PLAYING:
				this.onVideoPlay();
				break;
		}
	}

	onVideoPlay() {
		const { lesson } = this.state;
		this.emit('videoPlayed', {
			videoType: lesson.videoType,
			videoId: lesson.videoId,
		});
	}

	onVideoEnd() {
		const { lesson } = this.state;
		this.emit('videoEnded', {
			videoType: lesson.videoType,
			videoId: lesson.videoId,
		});
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		const { lesson, lessonId, iframeLoaded, posterLoaded, playing } =
			this.state;

		// If current lesson has changed
		if (this.children.player.dataset.lessonId !== lessonId) {
			this.children.player.dataset.videoType = lesson.videoType;
			this.children.player.dataset.lessonId = lessonId;
			this.children.title.innerText = lesson.title;
			this.children.title.dataset.number = lesson.number;
			this.children.body.innerText = lesson.description;
			this.children.download.innerText = lesson.downloadLabel;
			this.children.download.href = lesson.downloadURL;

			// Hide empty elements
			[
				this.children.title,
				this.children.body,
				this.children.download,
			].forEach((element) =>
				element.classList.toggle(
					'hidden',
					element.textContent.trim() ? false : true
				)
			);
		}

		this.children.player.classList.toggle(
			this.playerLoadingClassname,
			iframeLoaded && posterLoaded ? false : true
		);

		this.children.player.classList.toggle(
			this.playerPlayingClassname,
			playing
		);
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
Lesson.init();
