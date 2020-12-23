const {subscribe, dispatch} = wp.data;

export default function saveLock(symbolId, validator) {
	// Track locking.
	let locked = false;

	return subscribe(() => {
		const {lockPostSaving, unlockPostSaving} = dispatch('core/editor');
		const {createWarningNotice, removeNotice} = dispatch('core/notices');

		let error = validator();

		if (error) {
			if (!locked) {
				locked = true;
				lockPostSaving(symbolId);

				createWarningNotice(error, {id: symbolId})
			}
		} else {
			if (locked) {
				locked = false;
				unlockPostSaving(symbolId);
				removeNotice(symbolId);
			}
		}
	});
}
