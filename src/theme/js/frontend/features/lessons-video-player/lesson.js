import VimeoPlayer from '@vimeo/player';
import Component from '../../Component';
import { isVimeoVideo, getVimeoVideoData } from '../vimeo';

export default class Lesson extends Component {
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
		download: '.lessons-video-player__download',

		vimeoPlaceholder: '.lessons-video-player__vimeo-placeholder',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		poster: { load: this.onPosterLoaded.bind(this) },
		play: { click: this.onPlayClick.bind(this) },
		iframe: { load: this.onVideoLoaded.bind(this) },
	};

	playerLoadingClassname = 'lessons-video-player__player--loading';
	playerPlayingClassname = 'lessons-video-player__player--playing';

	// Defines initial State
	state = {
		lesson: {
			lessonId: '',
			src: '',
			poster: '',
			title: '',
			description: '',
			downloadLabel: '',
			downloadURL: '',
			videoType: '',
			videoId: '',
		},
		lessonId: '',
		posterLoaded: false,
		iframeLoaded: false,
		playing: false,
	};

	loadLessonData(lessonData) {
		if (lessonData.lessonId) {
			const lesson = this.sanitizeLessonData(lessonData);
			const lessonId = lessonData.lessonId;
			this.setState({
				lesson,
				lessonId,
				posterLoaded: false,
				iframeLoaded: false,
				playing: false,
			});

			this.loadLessonVideo(lesson);
		}
	}

	// Ensures a valid lesson data being passed
	sanitizeLessonData(rawLessonData) {
		const curatedLessonData = {};

		Object.keys(this.state.lesson).forEach((dataKey) => {
			curatedLessonData[dataKey] = rawLessonData[dataKey] || '';
		});

		return curatedLessonData;
	}

	onPosterLoaded() {
		this.setState({ posterLoaded: true });
	}

	onVideoLoaded() {
		this.setState({ iframeLoaded: true });
	}

	async loadLessonVideo(lesson) {
		// Supports vimeo video for now
		switch ( lesson.videoType ) {
			case 'vimeo':
				this.loadVimeoVideo(lesson.videoId);
				break;
		}
	}

	getVideoType(videoURL) {
		// YT is not actually supported yet
		return isVimeoVideo(videoURL) ? 'vimeo' : 'youtube';
	}

	async loadVimeoVideo(videoID) {
		if (!videoID) {
			return;
		}

		if (!this.vimeoPlayer) {
			this.vimeoPlayer = new VimeoPlayer(this.children.vimeoPlaceholder, {
				id: videoID,
			});
			this.vimeoPlayer.on('loaded', this.onVideoLoaded.bind(this));
			this.vimeoPlayer.on('play', this.onVideoPlay.bind(this));
		} else {
			this.vimeoPlayer.loadVideo(videoID);
		}
	}

	onPlayClick(e) {
		e.preventDefault();
		if (this.vimeoPlayer) {
			this.vimeoPlayer.play();
		}
	}

	onVideoPlay() {
		this.setState({ playing: true });
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		const { lesson, lessonId, iframeLoaded, posterLoaded, playing } =
			this.state;

		// If current lesson has changed
		if (this.children.player.dataset.lessonId !== lessonId) {
			this.children.player.dataset.lessonId = lessonId;
			this.children.poster.src = lesson.poster;
			this.children.title.innerText = lesson.title;
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

		if (this.state.iframeLoaded) {
			console.log('iframe loaded state');
		}

		if (this.state.posterLoaded) {
			console.log('poster loaded');
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
Lesson.init();
