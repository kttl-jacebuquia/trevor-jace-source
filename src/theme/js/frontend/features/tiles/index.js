import $ from 'jquery';

export default function showAllTiles ($showAllBtn) {

	if ($showAllBtn.length) {
		$showAllBtn.on('click', function(e) {
			e.preventDefault();
			const tileContainerId = this.dataset.tileContainer;
			const tileContainer = document.getElementById(tileContainerId);

			if (tileContainer.children.length) {
				for (const tile of tileContainer.children) {
					tile.classList.remove('hidden');
				}
			}
			this.parentElement.classList.add('hidden')
		});
	}
}
