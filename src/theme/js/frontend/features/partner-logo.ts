const DYNAMIC_PARTNER_LOGO_SELECTOR = '.partner-logo--dynamic';

const dynamicPartnerLogos = document.querySelectorAll<HTMLImageElement>(
	DYNAMIC_PARTNER_LOGO_SELECTOR
);

const onImageLoaded = (image: HTMLImageElement) => {
	const { width, height } = image;
	image.setAttribute(
		'data-aspect-ratio-class',
		getAspectRatioClass(width, height)
	);
};

// Should match php class \TrevorWP\Theme\Helper\Tier::get_aspect_ratio_class
const getAspectRatioClass = (width: number, height: number): string => {
	const widthHeightRatio = width / height;

	switch (true) {
		case widthHeightRatio >= 0.75 && widthHeightRatio <= 1.34:
			return 'square';
		case widthHeightRatio < 0.75:
			return 'portrait';
		case widthHeightRatio > 1.34 && widthHeightRatio < 1.8:
			return 'landscape';
		case widthHeightRatio >= 1.8 && widthHeightRatio < 5:
			return 'landscape-wide';
		case widthHeightRatio >= 5:
			return 'wide';
		default:
			return '';
	}
};

for (const logoImage of dynamicPartnerLogos) {
	(async () => {
		const dummyImage = new window.Image();
		dummyImage.onload = () => onImageLoaded(logoImage);
		dummyImage.src = logoImage.src;
	})();
}
