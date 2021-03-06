import OverflowFade from '../overflow-fade';
import { isVimeoVideo, getVimeoVideoData } from '../vimeo';
import {
	isYoutubeVideo,
	getYoutubeVideoData,
	loadYTPlayerAPI,
} from '../youtube';
import { LESSONS_WATCHED_KEY } from './lesson';

export type VideoType = 'youtube' | 'vimeo' | null;

export interface PlaylistItem extends DOMStringMap {
	src: string;
}

export interface PlaylistState {
	currentItem: PlaylistItem;
}

export interface PlaylistItemData {
	videoId: string;
	videoType: VideoType;
}

export default class Playlist extends OverflowFade {
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

	constructor(element: HTMLElement, config: object) {
		super(element, config);
	}

	// Will be called upon component instantiation
	async afterInit() {
		for (const item of this.children?.items as HTMLElement[]) {
			await this.populateItemDetails(item);
		}

		this.checkPlayedItems();
	}

	getItemByIndex(index: number, returnElement = false) {
		const matchedItem = (this.children?.items as HTMLElement[])[index];

		if (!matchedItem) {
			return null;
		}

		return returnElement ? matchedItem : matchedItem.dataset;
	}

	getItemById(itemId?: string, returnElement = false) {
		const matchedItem = (this.children?.items as HTMLElement[]).find(
			(item: HTMLElement) => item.dataset.lessonId === itemId
		);

		if (!matchedItem) {
			return null;
		}

		return returnElement ? matchedItem : matchedItem.dataset;
	}

	getItems() {
		return (this.children?.items as HTMLElement[]) || [];
	}

	onItemClick(e: Event & { currentTarget: HTMLElement }) {
		e.preventDefault();

		// Extract item data from data-* attributes
		const currentItem = e.currentTarget?.dataset;

		// Update state
		this.setState({ currentItem });

		// Emit event
		this.emit('videoSelected', {
			videoType: currentItem.videoType,
			videoId: currentItem.videoId,
		});
	}

	// Fetches the video's poster/thumbnail if none is given in the CMS
	async populateItemDetails(itemElement: HTMLElement) {
		// YT is not actually supported yet
		const videoType = this.getVideoType(itemElement.dataset.src || '');

		switch (videoType) {
			case 'vimeo':
				return await this.populateVimeoItemDetails(itemElement);
			case 'youtube':
				return await this.populateYoutubeItemDetails(itemElement);
		}
	}

	getVideoType(videoURL: string): VideoType {
		switch (true) {
			case isVimeoVideo(videoURL):
				return 'vimeo';
			case isYoutubeVideo(videoURL):
				return 'youtube';
			default:
				return null;
		}
	}

	async populateVimeoItemDetails(itemElement: HTMLElement) {
		const { poster, src, title } = itemElement.dataset;
		const videoData = await getVimeoVideoData(src || '');

		if (videoData) {
			itemElement.dataset.videoType = 'vimeo';
			itemElement.dataset.videoId = videoData.video_id;
			itemElement.dataset.duration = this.formatDurationSeconds(
				videoData.duration
			);

			const datetime = this.formatDurationWords(
				itemElement.dataset.duration
			);
			const durationElement = itemElement.querySelector(
				'.lessons-video-player__playlist-item-duration'
			) as HTMLElement;
			durationElement.textContent = itemElement.dataset.duration;
			durationElement.setAttribute(
				'aria-label',
				`Duration for ${title}: ${datetime}`
			);

			if (!poster) {
				itemElement.dataset.thumbnail = videoData.thumbnail_url;
				itemElement.dataset.poster = videoData.thumbnail_url;

				const itemImage = itemElement.querySelector(
					'img'
				) as HTMLImageElement;
				itemImage.src = videoData.thumbnail_url;
			}
		}

		return itemElement.dataset;
	}

	async populateYoutubeItemDetails(itemElement: HTMLElement) {
		const { poster, src } = itemElement.dataset;
		const videoData = await getYoutubeVideoData(src || '');

		if (videoData) {
			await loadYTPlayerAPI();

			itemElement.dataset.videoType = 'youtube';
			itemElement.dataset.videoId = videoData.video_id;
			itemElement.dataset.duration = this.formatDurationSeconds(
				videoData.duration
			);

			const durationElement = itemElement.querySelector(
				'.lessons-video-player__playlist-item-duration'
			) as HTMLElement;
			durationElement.textContent = itemElement.dataset.duration;

			if (!poster) {
				itemElement.dataset.thumbnail = videoData.thumbnail_url;
				itemElement.dataset.poster = videoData.poster_url;

				const itemImage = itemElement.querySelector(
					'img'
				) as HTMLImageElement;
				itemImage.src = videoData.thumbnail_url;
			}
		}

		return itemElement.dataset;
	}

	formatDurationSeconds(durationSeconds: number): string {
		const minutes = Math.floor(durationSeconds / 60);
		const seconds = Math.floor(durationSeconds % 60);

		return `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
	}

	formatDurationWords(durationString: string): string {
		const durationWords = [];
		const durationSegments = durationString.split(':');

		durationSegments.reverse();

		const [seconds, minutes, hours] = durationSegments;

		if (parseInt(hours) > 0) {
			const h = parseInt(hours);
			durationWords.push(`${h}hour${h > 1 ? 's' : ''}`);
		}

		if (parseInt(minutes) > 0) {
			const m = parseInt(minutes);
			durationWords.push(`${m}minutes${m > 1 ? 's' : ''}`);
		}

		if (parseInt(seconds) > 0) {
			const s = parseInt(seconds);
			durationWords.push(`${s}seconds${s > 1 ? 's' : ''}`);
		}

		return durationWords.join(', ');
	}

	checkPlayedItems() {
		const lessonsWatched =
			JSON.parse(
				window.localStorage.getItem(LESSONS_WATCHED_KEY) || '[]'
			) || [];

		(this.children?.items as HTMLElement[]).forEach((item) => {
			const { ...itemData } = item.dataset as PlaylistItem;
			const isWatched = lessonsWatched.some(
				(lessonWatched: PlaylistItem) =>
					lessonWatched.videoId === itemData.videoId &&
					lessonWatched.videoType === itemData.videoType
			);

			// Apply played state if it is
			if (isWatched) {
				item.dataset.watched = 'true';
			}
		});
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate({ currentItem }: PlaylistState) {
		(this.children?.items as HTMLElement[]).forEach((itemElement) => {
			const willActivate = itemElement.dataset.src === currentItem.src;
			itemElement.classList.toggle(this.activeItemClass, willActivate);
		});
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
// Playlist.init();
