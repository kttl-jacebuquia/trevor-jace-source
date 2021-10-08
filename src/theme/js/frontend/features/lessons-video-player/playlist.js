import Component from '../../Component';
import { isVimeoVideo, getVimeoVideoData } from '../vimeo';

export default class Playlist extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.lessons-video-player__playlist';

	// Defines children that needs to be queried as part of this component
	static children = {
		items: ['.lessons-video-player__playlist-item'], // Queries a an array of elements
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		items: {
			click: this.onItemClick,
		},
	};

	// Defines initial State
	state = {
		currentItem: null,
	};

	activeItemClass = 'lessons-video-player__playlist-item--active';

	// Will be called upon component instantiation
	afterInit() {
		this.children.items.forEach(item => {
			this.populateItemDetails(item);
		});
	}

	getItemByIndex(index, returnElement = false) {
		const matchedItem = this.children.items[index];

		if ( !matchedItem ) {
			return null;
		}

		return returnElement ? matchedItem : matchedItem.dataset;
	}

	getItemById(itemId, returnElement = false ) {
		const matchedItem = this.children.items.find(item => item.dataset.lessonId === itemId);

		if ( !matchedItem ) {
			return null;
		}

		return returnElement ? matchedItem : matchedItem.dataset;
	}

	onItemClick(e) {
		e.preventDefault();

		// Extract item data from data-* attributes
		const currentItem = e.currentTarget.dataset;

		// Update state
		this.setState({ currentItem });
	}

	// Fetches the video's poster/thumbnail if none is given in the CMS
	populateItemDetails(itemElement) {
		// YT is not actually supported yet
		const videoType = isVimeoVideo(itemElement.dataset.src) ? 'vimeo' : 'youtube';

		switch (videoType) {
			case 'vimeo':
				return this.populateVimeoItemDetails(itemElement);
		}
	}

	async populateVimeoItemDetails(itemElement) {
		const { poster, src } = itemElement.dataset;
		const videoData = await getVimeoVideoData(src);

		if ( videoData ) {
			itemElement.dataset.videoType = 'vimeo';
			itemElement.dataset.videoId = videoData.video_id;

			if ( !poster ) {
				itemElement.dataset.thumbnail = videoData.thumbnail_url;
				itemElement.dataset.poster = videoData.thumbnail_url;

				const itemImage = itemElement.querySelector('img');
				itemImage.src = videoData.thumbnail_url;
			}
		}

		return itemElement.dataset;
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate({ currentItem }) {
		this.children.items.forEach((itemElement) => {
			const willActivate = itemElement.dataset.src === currentItem.src;
			itemElement.classList.toggle(this.activeItemClass, willActivate);
		});
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
// Playlist.init();
