import Component from '../../Component';
import Lesson, {
	LESSONS_WATCHED_KEY,
	LessonDataFragment,
	LessonData,
} from './lesson';
import Playlist, { PlaylistItem, PlaylistState } from './playlist';
import { loadYTPlayerAPI } from '../youtube';

export default class LessonsVideoPlayer extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.lessons-video-player';

	// Defines sub components that needs to be queried as part of this component
	static members = {
		lesson: Lesson,
		playlist: Playlist,
	};

	// Will be called upon component instantiation
	async afterInit() {
		// Initialize playlist
		this.members?.playlist.onStateChange(
			this.onPlaylistItemStateChange.bind(this)
		);

		this.members?.lesson.on('videoPlayed', this.onVideoPlayed.bind(this));
		this.members?.lesson.on('videoEnded', this.onVideoEnded.bind(this));

		// Initialize lesson player
		const firstItem = this.members?.playlist.getItemByIndex(0);

		this.loadLesson(firstItem);
	}

	onPlaylistItemStateChange(playlistState: PlaylistState) {
		const { currentItem } = playlistState;
		this.loadLesson(currentItem);
	}

	async loadLesson(lessonData: LessonData) {
		if (!lessonData.videoId) {
			const itemElement = this.members?.playlist.getItemById(
				lessonData.lessonId,
				true
			);
			const updatedData =
				await this.members?.playlist.populateItemDetails(itemElement);
			this.members?.lesson.loadLessonData(updatedData);
		} else {
			this.members?.lesson.loadLessonData(lessonData);
		}
	}

	onVideoPlayed({ detail }: CustomEventInit) {
		const { videoId, videoType } = detail;

		const lessonsWatched =
			JSON.parse(
				window.localStorage.getItem(LESSONS_WATCHED_KEY) || '[]'
			) || [];

		const matchedSavedLesson = lessonsWatched.find(
			({ videoType: type, videoId: id }: LessonDataFragment) =>
				type === videoType && id === videoId
		);

		// If lesson is not watched yet, save
		if (!matchedSavedLesson) {
			lessonsWatched.push({ videoType, videoId });
			window.localStorage.setItem(
				LESSONS_WATCHED_KEY,
				JSON.stringify(lessonsWatched)
			);
		}

		this.members?.playlist.checkPlayedItems();
	}

	onVideoEnded() {
		const { lesson } = this.members?.lesson.state;

		if (!lesson) {
			return null;
		}

		const { videoId, videoType } = lesson;
		const currentItemIndex = this.members?.playlist
			.getItems()
			.findIndex((item: HTMLElement) => {
				return (
					item.dataset.videoType === videoType &&
					item.dataset.videoId === videoId
				);
			});

		// Play next item
		const nextItem = this.members?.playlist.getItemByIndex(
			currentItemIndex + 1
		);

		if (nextItem) {
			this.members?.lesson.loadLessonData(nextItem, true);
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
LessonsVideoPlayer.init();
