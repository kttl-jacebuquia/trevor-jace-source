import Component from '../../Component';
import Lesson from './lesson';
import Playlist from './playlist';

export default class LessonsVideoPlayer extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.lessons-video-player';

	// Defines sub components that needs to be queried as part of this component
	static members = {
		lesson: Lesson,
		playlist: Playlist,
	};

	// Will be called upon component instantiation
	afterInit() {
		// Initialize playlist
		this.members.playlist.onStateChange(
			this.onPlaylistItemStateChange.bind(this)
		);

		// Initialize lesson player
		const firstItem = this.members.playlist.getItemByIndex(0);
		this.loadLesson(firstItem);
	}

	onPlaylistItemStateChange(playlistState) {
		const { currentItem } = playlistState;
		this.loadLesson(currentItem);
	}

	async loadLesson(lessonData) {
		if (!lessonData.videoId) {
			const itemElement = this.members.playlist.getItemById(
				lessonData.lessonId,
				true
			);
			const updatedData = await this.members.playlist.populateItemDetails(
				itemElement
			);
			this.members.lesson.loadLessonData(updatedData);
		} else {
			this.members.lesson.loadLessonData(lessonData);
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
LessonsVideoPlayer.init();
