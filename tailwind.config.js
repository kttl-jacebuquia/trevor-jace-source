const defaultTheme = require('tailwindcss/defaultTheme')

const px2rem = (px, root = 16) => {
	const remVal = px / root;
	return String(+remVal.toFixed(2)) + 'rem'
}

/**
 * Builds object containing px 2 rem key-value for tailwind, e.g.:
 * {
 *   px5: px2rem(5)
 * }
 * @param {Array} values List of numbers
 */
const px2remMap = (values = []) => values.reduce((all, currentValue) => ({
	...all,
	[`px${currentValue}`]: px2rem(currentValue)
}), {});

/**
 * Generates a list of divisible numbers
 * @param {Int} divisor Divisor
 * @param {Int} maxItems Total number of items to generate
 * @param {Int} minValue Minimum value
 * @param {Int} maxValue Maximum value
 * @returns Array
 */
const divisibles = (divisor = 1, maxItems = 10, minValue = 0, maxValue = 500) => {
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

module.exports = {
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
			'mobile': {'max': px2rem(767)},
			'md-max': {'max': px2rem(1023)},
			'lg-max': {'max': px2rem(1279)},
			'sm': px2rem(375),
			'md': px2rem(768),
			'lg': px2rem(1024),
			'lg2': px2rem(1280),
			'xl': px2rem(1440),
			'2xl': px2rem(1600),
			dark: {'raw': '(prefers-color-scheme: dark)'},
		},
		container: {
			screens: { // set the max-width per breakpoint
				'sm': '100%',
				'md': px2rem(768),
				'lg': px2rem(1024),
				'lg2': px2rem(1280),
				'xl': px2rem(1440),
				'2xl': px2rem(1600),
			}
		},
		colors: {
			transparent: 'transparent',
			current: 'currentColor',
			black: {
				DEFAULT: '#000',
			},
			white: {
				DEFAULT: '#fff'
			},
			gray: {
				DEFAULT: '#ccc',
				light: '#F3F3F7'
			},
			violet: {
				DEFAULT: '#6B5DF6',
				alt: '#7155FF',
				light: '#EDEDFB',
				lighter: '#DBD5FF',
				tint: '#897DF8'
			},
			orange: {
				DEFAULT: '#FF5A3D',
				light: '#FF674D', //fixme?
			},
			indigo: {
				DEFAULT: '#101066',
			},
			purple: {
				DEFAULT: '#40009A',
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
				tint: '#D9E2E4'
			},
			moss: {
				DEFAULT: '#E1E8E9',
				dark: '#08535A'
			},
			persian_blue: {
				DEFAULT: '#3626D9',
				lighter: 'rgba(54, 38, 217, 0.9)',
			},
			melrose: {
				DEFAULT: '#CAC5FC',
			}
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
			}
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
			}
		},
		header: {
			overflow: {
				xl: '-1.25rem'
			}
		},
		paths: {
			themeGradients: '/wp-content/themes/trevor/static/media/gradients',
		},
		staffCard: {
			width: {
				DEFAULT: px2rem(264),
				xl: px2rem(289),
			}
		},
		// Extend
		extend: {
			fontFamily: {
				caveat: ['Caveat', 'cursive', ...defaultTheme.fontFamily.serif],
				manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
			},
			container: {
				padding: {
					DEFAULT: px2rem(28),
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
				px100: px2rem(100),
			},
			borderWidth: {
				px1: px2rem(1),
				'px1.5': px2rem(1.5),
				px2: px2rem(2),
				px4: px2rem(4),
				px6: px2rem(6),
				px9: px2rem(9),
				px10: px2rem(10),
				px12: px2rem(12)
			},
			maxWidth: {
				unset: 'unset',
				px170: px2rem(170),
				px289: px2rem(289),
				px318: px2rem(318),
				px319: px2rem(319),
				px394: px2rem(394),
				px403: px2rem(403),
				px422: px2rem(422),
				px450: px2rem(450),
				px490: px2rem(490),
				px493: px2rem(493),
				px500: px2rem(500),
				px550: px2rem(550),
				px605: px2rem(605),
				px606: px2rem(606),
				px718: px2rem(718),
				px818: px2rem(818),
				px894: px2rem(894),
				px960: px2rem(960),
				px1028: px2rem(1028),
				px1080: px2rem(1080),
				px1240: px2rem(1240),
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
				px50: px2rem(50),
				px145: px2rem(145),
				px190: px2rem(190),
				px200: px2rem(200),
				px315: px2rem(315),
				px319: px2rem(319),
				px355: px2rem(355),
			},
			width: {
				px13: px2rem(13),
				px20: px2rem(20),
				px40: px2rem(40),
				px45: px2rem(45),
				px127: px2rem(127),
				px214: px2rem(214),
				px264: px2rem(264),
				px315: px2rem(315),
				px319: px2rem(319),
				px368: px2rem(368),
				px370: px2rem(370),
				px394: px2rem(394),
				px422: px2rem(422),
				px500: px2rem(500),
			},
			minHeight: px2remMap([
				...divisibles(2, 500, 0, 800),
				...divisibles(5, 500, 0, 800),
			]),
			height: {
				px13: px2rem(13),
				px20: px2rem(20),
				px34: px2rem(34),
				px42: px2rem(42),
				px46: px2rem(46),
				px45: px2rem(45),
				px48: px2rem(48),
				px52: px2rem(52),
				px54: px2rem(54),
				px68: px2rem(68),
				px72: px2rem(72),
				px78: px2rem(78),
				px84: px2rem(84),
				px100: px2rem(100),
				px104: px2rem(104),
				px106: px2rem(106),
				px128: px2rem(128),
				px133: px2rem(133),
				px138: px2rem(138),
				px140: px2rem(140),
				px160: px2rem(160),
				px200: px2rem(200),
				px240: px2rem(240),
				px250: px2rem(250),
				px302: px2rem(302),
				px328: px2rem(328),
				px375: px2rem(375),
				px412: px2rem(412),
				px436: px2rem(436),
				px440: px2rem(440),
				px445: px2rem(445),
				px490: px2rem(490),
				px503: px2rem(503),
				px534: px2rem(534),
				px546: px2rem(546),
				px600: px2rem(600),
				px706: px2rem(706),
				px737: px2rem(737),
				px820: px2rem(820),
				...px2remMap([
					292,
				])
			},
			fontSize: px2remMap([
				...divisibles(2, 500, 10, 100),
				...divisibles(5, 500, 10, 100),
			]),
			lineHeight: px2remMap([
				...divisibles(2, 500, 10, 200),
				...divisibles(5, 500, 10, 200),
			]),
			letterSpacing: {
				em005: '0.005em',
				em001: '0.01em',
				em002: '0.02em',
				em_005: '-0.005em',
				em_001: '-0.01em', // todo: convert to tailwind negative
				px05: px2rem(.5),
				px_015: px2rem(-1.5),
				px_02: px2rem(-2),
				px_05: px2rem(-.5),
			},
			zIndex: {
				'1': '1',
				'2': '2',
				'-1': '-1',
				21: 21,
				30: 30,
				31: 31
			},
			gap: {
				'sm': px2rem(12),
				'md': px2rem(28),
				... px2remMap(divisibles(10, 10, 0, 100))
			},
			spacing: {
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
					117,
					127,
					137,
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
							color: DARK_TEAL
						},
						'ul > li::before': {
							backgroundColor: DARK_TEAL
						}
					}
				}
			},
			boxShadow: {
				'indigo-md': '0px 6px 8px rgba(16, 16, 102, 0.08)',
				'indigo-lg': '0px 8px 6px rgba(16, 16, 102, 0.1)',
				'light': '0px 0px 16px rgba(0, 58, 72, 0.2)',
				'darkGreen': '0px 6px 12px 0px rgb(0, 58, 72, 0.1)',
				'radio' : 'inset 0 0 0 2px #003A48',
				none: 'none',
			},
			backgroundImage: theme => ({
				'gradient-default': `url('${theme('paths.themeGradients')}/default.png')`,
				'gradient-rc': `url('${theme('paths.themeGradients')}/rc.png')`,
				'gradient-darkgreen': `url('${theme('paths.themeGradients')}/darkgreen.png')`,
				'gradient-darkgreen-flip': `url('${theme('paths.themeGradients')}/darkgreen-flip.png')`,
				'gradient-trevorspace': `url('${theme('paths.themeGradients')}/trevorspace.png')`,
				'gradient-gethelp': `url('${theme('paths.themeGradients')}/gethelp.png')`,
			}),
			opacity: {
				'16': '0.16',
				'44': '0.44',
				'86': '0.86',
			}
		},
	},
	variants: {
		extend: {
			backgroundColor: ['checked']
		}
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
		require('@tailwindcss/line-clamp'),
	],
}
