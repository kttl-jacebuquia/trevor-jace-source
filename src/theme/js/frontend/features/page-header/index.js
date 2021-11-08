import Component from 'theme/js/frontend/Component';

export default class PageHeader extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.page-header';

	// Defines children that needs to be queried as part of this component
	static children = {
		video: 'video',
		playPause: '.page-header__playback',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		video: {
			play: this.onVideoPlay,
			pause: this.onVideoPause,
		},
		playPause: {
			click: this.togglePlayback,
		},
	};

	// Defines initial State
	state = {
		playing: false,
	}

	onVideoPlay() {
		this.setState({ playing: true });
	}

	onVideoPause() {
		this.setState({ playing: false });
	}

	togglePlayback() {
		if (this.state.playing) {
			this.children.video.pause();
		} else {
			this.children.video.play();
		}
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		const playOrPause = this.state.playing ? 'pause' : 'play';
		const ariaLabel = `Click to ${playOrPause} background video`;

		this.element.classList.toggle(
			'type-img-bg--playing',
			this.state.playing
		);
		this.children.playPause.setAttribute('aria-label', ariaLabel);
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
PageHeader.init();
