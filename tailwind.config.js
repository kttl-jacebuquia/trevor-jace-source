const { outputSync, outputFileSync } = require('fs-extra');
const path = require('path');
const defaultTheme = require('tailwindcss/defaultTheme');

const px2rem = (px, root = 16) => {
	const remVal = px / root;
	return String(+remVal.toFixed(2)) + 'rem';
};

// Converts a hex color to RGBA with tailwind's opacity bg variable
const hexToTwRgba = (hex) => {
	var result = /^#?([0-9a-f\d]{2})([0-9a-f\d]{2})([0-9a-f\d]{2})$/i.exec(hex);
	return result
		? {
				r: parseInt(result[1], 16),
				g: parseInt(result[2], 16),
				b: parseInt(result[3], 16),
				a: 'var(--tw-bg-opacity)',
		  }
		: null;
};

// Expands rgba color segments into separate r, g, b, a values
const rgbaSegments = (rgba) => {
	const rgbaMatch = rgba.replace(/[^0-9,.]+/g, '');
	const [r, g, b, a = 1] = rgbaMatch.split(',');
	return { r, g, b, a };
};

/**
 * Builds object containing px 2 rem key-value for tailwind, e.g.:
 * {
 *   px5: px2rem(5)
 * }
 *
 * @param {Array} values List of numbers
 */
const px2remMap = (values = []) =>
	values.reduce(
		(all, currentValue) => ({
			...all,
			[`px${currentValue}`]: px2rem(currentValue),
		}),
		{}
	);

/**
 * Generates a list of divisible numbers
 *
 * @param {Int} divisor Divisor
 * @param {Int} maxItems Total number of items to generate
 * @param {Int} minValue Minimum value
 * @param {Int} maxValue Maximum value
 * @return Array
 */
const divisibles = (
	divisor = 1,
	maxItems = 10,
	minValue = 0,
	maxValue = 500
) => {
	const result = [];

	for (
		let index = 0;
		index < maxItems && (result.slice(-1)[0] || 0) <= maxValue;
		index++
	) {
		result.push(index * divisor + minValue);
	}

	return result;
};

const DARK_TEAL = '#003A48';

const config = {
	purge: {
		preserveHtmlElements: true,
		content: [
			'./src/theme/**/*.js',
			'./src/theme/**/*.scss',
			'./theme/data/tailwind.safelist.txt',
			'./theme/**/*.php',
			'./plugin/inc/**/*.php',
			'./plugin/templates/blocks/**/*.twig',
		],
	},
	prefix: '',
	important: false,
	separator: ':',
	theme: {
		screens: {
			'below-mobile': { max: px2rem(376) },
			mobile: { max: px2rem(767) },
			'mobile-only': { min: px2rem(375), max: px2rem(767) },
			'mobile-up': { min: px2rem(375) },
			'md-minmax': { min: px2rem(768), max: px2rem(1023) },
			'md-max': { max: px2rem(1023) },
			'mobile-md': { min: px2rem(375), max: px2rem(1023) },
			'lg-max': { max: px2rem(1279) },
			'lg-only': { min: px2rem(1023), max: px2rem(1279) },
			sm: px2rem(375),
			md: px2rem(768),
			lg: px2rem(1024),
			lg2: px2rem(1280),
			xl: px2rem(1440),
			'2xl': px2rem(1600),
			dark: { raw: '(prefers-color-scheme: dark)' },
		},
		container: {
			screens: {
				// set the max-width per breakpoint
				sm: px2rem(375),
				md: px2rem(768),
				lg: px2rem(1024),
				lg2: px2rem(1280),
				xl: px2rem(1440),
			},
		},
		colors: {
			transparent: 'transparent',
			current: 'currentColor',
			black: {
				DEFAULT: '#000',
			},
			white: {
				DEFAULT: '#fff',
			},
			gray: {
				DEFAULT: '#ccc',
				light: '#F3F3F7',
			},
			violet: {
				DEFAULT: '#6B5DF6',
				alt: '#7155FF',
				light: '#EDEDFB',
				lighter: '#DBD5FF',
				tint: '#897DF8',
			},
			orange: {
				DEFAULT: '#FF5A3D',
				light: '#FF674D',
				lighter: '#FF6950',
			},
			indigo: {
				DEFAULT: '#101066',
			},
			purple: {
				DEFAULT: '#40009A',
				dark: '#040463',
			},
			cobalt_blue: {
				DEFAULT: '#00479C',
			},
			blue_green: {
				DEFAULT: '#005E67',
			},
			canary: {
				DEFAULT: '#FFD215',
			},
			gold: {
				DEFAULT: '#F9C058',
			},
			highlight: {
				DEFAULT: '#FDE6BC',
			},
			teal: {
				lighter: 'rgba(0, 58, 72, 0.2)',
				dark: DARK_TEAL,
				tint: '#D9E2E4',
			},
			moss: {
				DEFAULT: '#E1E8E9',
				dark: '#08535A',
			},
			persian_blue: {
				DEFAULT: '#3626D9',
				lighter: 'rgba(54, 38, 217, 0.9)',
			},
			melrose: {
				DEFAULT: '#CAC5FC',
			},
		},
		// Custom
		postCard: {
			width: {
				DEFAULT: px2rem(319),
				md: px2rem(319),
				lg: px2rem(335),
				lg2: px2rem(328),
				xl: px2rem(368),
				'2xl': px2rem(368),
			},
		},
		carousel: {
			w3Card: {
				minWidth: px2rem(415),
				maxWidth: px2rem(1275),
			},
			bigImg: {
				height: px2rem(319),
				heightMD: px2rem(409),
				heightLG: px2rem(500),
			},
		},
		header: {
			overflow: {
				xl: '-1.25rem',
			},
		},
		paths: {
			themeMedia: '/wp-content/themes/trevor/static/media',
			themeGradients: '/wp-content/themes/trevor/static/media/gradients',
		},
		staffCard: {
			width: {
				DEFAULT: px2rem(264),
				xl: px2rem(289),
			},
		},
		// Extend
		extend: {
			fontFamily: {
				caveat: ['Caveat', 'cursive', ...defaultTheme.fontFamily.serif],
				manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
			},
			container: {
				padding: {
					DEFAULT: `${100 * (28 / 375)}vw`,
					'mobile-up': px2rem(28),
					sm: px2rem(28),
					md: px2rem(50),
					lg: px2rem(90),
					lg2: px2rem(120),
					xl: px2rem(140),
					'2xl': px2rem(140),
				},
			},
			borderRadius: {
				px4: px2rem(4),
				px10: px2rem(10),
				px18: px2rem(18),
				px100: px2rem(100),
			},
			borderWidth: {
				px1: px2rem(1),
				'px1.5': px2rem(1.5),
				px2: px2rem(2),
				px4: px2rem(4),
				px6: px2rem(6),
				px8: px2rem(8),
				px9: px2rem(9),
				px10: px2rem(10),
				px12: px2rem(12),
			},
			maxWidth: {
				unset: 'unset',
				...px2remMap([
					122, 130, 170, 248, 289, 276, 318, 319, 394, 403, 422, 450,
					490, 493, 500, 550, 605, 606, 718, 733, 818, 894, 960, 1028,
					1080, 1240,
				]),
				'7/10': '70%',
				'4/5': '80%',
				'3/5': '60%',
			},
			maxHeight: {
				none: 'none',
				px62: px2rem(62),
				px100: px2rem(100),
				px120: px2rem(120),
				px160: px2rem(160),
			},
			minWidth: {
				...px2remMap([
					...divisibles(2, 500, 0, 500),
					...divisibles(5, 500, 0, 500),
					187,
					319,
				]),
			},
			width: px2remMap([
				13, 14, 20, 40, 45, 127, 214, 264, 315, 319, 368, 370, 394, 422,
				500, 568, 577, 606, 733,
			]),
			minHeight: px2remMap([
				...divisibles(2, 500, 0, 800),
				...divisibles(5, 500, 0, 800),
			]),
			height: {
				...px2remMap([
					6, 13, 14, 16, 20, 26, 34, 42, 45, 46, 48, 52, 54, 68, 72,
					78, 84, 91, 98, 102, 104, 106, 112, 128, 133, 138, 140, 160,
					200, 240, 250, 292, 302, 328, 375, 412, 430, 436, 440, 445,
					490, 503, 510, 534, 546, 550, 600, 670, 706, 737, 750, 820,
				]),
			},
			fontSize: px2remMap([
				...divisibles(2, 500, 10, 100),
				...divisibles(5, 500, 10, 100),
				11,
				13,
				154,
				180,
				182,
				200,
				220,
				230,
				242,
				270,
			]),
			lineHeight: px2remMap([
				...divisibles(2, 500, 10, 200),
				...divisibles(5, 500, 10, 200),
				6,
				19,
				246,
				273,
			]),
			letterSpacing: {
				em005: '0.005em',
				em001: '0.01em',
				em002: '0.02em',
				em003: '0.03em',
				em_003: '-0.003em',
				em_005: '-0.005em',
				em_001: '-0.01em', // todo: convert to tailwind negative
				px025: px2rem(0.25),
				px05: px2rem(0.5),
				px_015: px2rem(-1.5),
				px_01: px2rem(-1),
				px_02: px2rem(-2),
				px_05: px2rem(-0.5),
				px6: px2rem(6),
				px10: px2rem(10),
			},
			zIndex: {
				1: '1',
				2: '2',
				'-1': '-1',
				21: 21,
				30: 30,
				31: 31,
				9999: 9999,
			},
			gap: {
				sm: px2rem(12),
				md: px2rem(28),
				lg: px2rem(28),
				lg2: px2rem(28),
				xl: px2rem(28),
				...px2remMap(divisibles(10, 10, 0, 100)),
			},
			spacing: {
				'container-padding-default': `${100 * (28 / 375)}vw`, // visual 28px, usefull for zoomed in layout
				full: '100%',
				vwfull: '100vw',
				pxn5: px2rem(-5),
				pxn25: px2rem(-25),
				pxn28: px2rem(-28),
				pxn2: px2rem(-2),
				...px2remMap([
					...divisibles(2, 500, 0, 500),
					...divisibles(3, 500, 0, 500),
					...divisibles(5, 500, 0, 500),
					...divisibles(7, 500, 0, 500),
					...divisibles(11, 500, 0, 500),
					...divisibles(13, 500, 0, 500),
					17,
					23,
					27,
					29,
					37,
					41,
					43,
					59,
					103,
					117,
					127,
					137,
					173,
					187,
					239,
					247,
					279,
					293,
					311,
					319,
					395,
					590,
					780,
					800,
					822,
					907,
					1600,
				]),
			},
			typography: {
				'teal-dark': {
					css: {
						color: DARK_TEAL,
						h3: {
							color: DARK_TEAL,
						},
						'ul > li::before': {
							backgroundColor: DARK_TEAL,
						},
					},
				},
			},
			boxShadow: {
				'indigo-md': '0px 6px 8px rgba(16, 16, 102, 0.08)',
				'indigo-lg': '0px 8px 6px rgba(16, 16, 102, 0.1)',
				light: '0px 0px 16px rgba(0, 58, 72, 0.2)',
				darkGreen: '0px 6px 12px 0px rgb(0, 58, 72, 0.1)',
				radio: 'inset 0 0 0 2px #003A48',
				none: 'none',
			},
			backgroundImage: (theme) => ({
				'gradient-default': `url('${theme(
					'paths.themeGradients'
				)}/default.png')`,
				'gradient-rc': `url('${theme('paths.themeGradients')}/rc.png')`,
				'gradient-darkgreen': `url('${theme(
					'paths.themeGradients'
				)}/darkgreen.png')`,
				'gradient-darkgreen-flip': `url('${theme(
					'paths.themeGradients'
				)}/darkgreen-flip.png')`,
				'gradient-trevorspace': `url('${theme(
					'paths.themeGradients'
				)}/trevorspace.png')`,
				'gradient-gethelp': `url('${theme(
					'paths.themeGradients'
				)}/gethelp.png')`,
				'gradient-orange': `url('${theme(
					'paths.themeGradients'
				)}/orange.png')`,
				'gradient-crisis-services': `url('${theme(
					'paths.themeGradients'
				)}/crisis-services.png')`,
				'breathing-exercise-mobile': `url('${theme(
					'paths.themeMedia'
				)}/breathing-exercise/mobile.svg')`,
				'breathing-exercise-tablet': `url('${theme(
					'paths.themeMedia'
				)}/breathing-exercise/tablet.svg')`,
				'breathing-exercise-desktop': `url('${theme(
					'paths.themeMedia'
				)}/breathing-exercise/desktop.svg')`,
				'gradient-violet': `url('${theme(
					'paths.themeGradients'
				)}/violet.svg')`,
				'gradient-article-recirculation': `url('${theme(
					'paths.themeGradients'
				)}/article-recirculation.jpg')`,
				'gradient-advocate': `url('${theme(
					'paths.themeGradients'
				)}/advocate.svg')`,
				'gradient-pride-promo': `url('${theme(
					'paths.themeGradients'
				)}/pride_promo.png')`,
			}),
			opacity: {
				16: '0.16',
				22: '0.22',
				44: '0.44',
				86: '0.86',
			},
		},
	},
	variants: {
		extend: {
			backgroundColor: ['checked'],
		},
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
		require('@tailwindcss/line-clamp'),
	],
};

// Create color maps to be rendered into an scss file
const themeColors = {};
const extendedColors = {};

// Add rgba variants to theme colors
Object.entries(config.theme.colors).forEach(([color, colorConfig]) => {
	if (typeof colorConfig === 'object') {
		// Cycle through the color variants
		Object.entries(colorConfig).forEach(([key, variantValue]) => {
			const rgbaObject = /rgb?a\([^)]+\)/.test(variantValue)
				? rgbaSegments(variantValue)
				: hexToTwRgba(variantValue);
			const variantKey = /default/i.test(key) ? '' : key.toLowerCase();

			// Add rgba variant, as well as red-blue-green segments
			// See theme.scss for sample usage
			if (rgbaObject) {
				const { r, g, b, a } = rgbaObject;
				const rgba = [r, g, b, a].join(', ');

				config.theme.colors[color][
					[variantKey, 'rgba'].filter(Boolean).join('-')
				] = `rgba(${rgba})`;
				extendedColors[
					[color, variantKey, 'rgba'].filter(Boolean).join('-')
				] = `rgba(${rgba})`;

				Object.entries({ r, g, b }).forEach(
					([colorSegment, segmentValue]) => {
						config.theme.colors[color][
							[variantKey, colorSegment].filter(Boolean).join('-')
						] = segmentValue;
						extendedColors[
							[color, variantKey, colorSegment]
								.filter(Boolean)
								.join('-')
						] = segmentValue;
					}
				);
			}

			const colorVariantKey =
				color + (variantKey ? `-${variantKey}` : '');
			themeColors[colorVariantKey] = variantValue;
			extendedColors[colorVariantKey] = variantValue;
		});
	} else {
		themeColors[color] = colorConfig;
		extendedColors[color] = colorConfig;
	}
});

// Render colors scss file
const scssColorsOutputPath = path.resolve(
	__dirname,
	'src/theme/css/colors/_colors.scss'
);
const outputContents = `
$colors: (
  ${Object.entries(extendedColors)
		.map(([key, value]) => `${key}: ${value}`)
		.join(',\n  ')}
);

$theme-colors: (
	${Object.entries(themeColors)
		.map(([key, value]) => `${key}: ${value}`)
		.join(',\n  ')}
);
`;
outputFileSync(scssColorsOutputPath, outputContents);

module.exports = config;
