const defaultTheme = require('tailwindcss/defaultTheme')

const px2rem = (px, root = 16) => {
	const remVal = px / root;
	return String(+remVal.toFixed(2)) + 'rem'
}

const DARK_TEAL = '#003A48';

module.exports = {
	purge: {
		enabled: true,
		preserveHtmlElements: true,
		content: [
			'./src/theme/**/*.js',
			'./src/theme/**/*.scss',
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
				lg: px2rem(395),
				lg2: px2rem(355),
				xl: px2rem(395),
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
					lg: px2rem(50),
					lg2: px2rem(80),
					xl: px2rem(100),
					'2xl': px2rem(100),
				},
			},
			borderRadius: {
				px10: px2rem(10)
			},
			borderWidth: {
				px1: px2rem(1),
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
				px319: px2rem(319),
				px370: px2rem(370),
				px394: px2rem(394),
				px422: px2rem(422),
				px500: px2rem(500),
			},
			minHeight: {
				px108: px2rem(108),
				px128: px2rem(128),
				px724: px2rem(724),
			},
			height: {
				px13: px2rem(13),
				px20: px2rem(20),
				px34: px2rem(34),
				px45: px2rem(45),
				px48: px2rem(48),
				px52: px2rem(52),
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
				px240: px2rem(240),
				px302: px2rem(302),
				px375: px2rem(375),
				px412: px2rem(412),
				px436: px2rem(436),
				px445: px2rem(445),
				px490: px2rem(490),
				px503: px2rem(503),
				px534: px2rem(534),
				px546: px2rem(546),
				px600: px2rem(600),
				px706: px2rem(706),
				px737: px2rem(737),
			},
			fontSize: {
				px12: px2rem(12),
				px14: px2rem(14),
				px16: px2rem(16),
				px18: px2rem(18),
				px20: px2rem(20),
				px22: px2rem(22),
				px24: px2rem(24),
				px25: px2rem(25),
				px26: px2rem(26),
				px28: px2rem(28),
				px30: px2rem(30),
				px32: px2rem(32),
				px34: px2rem(34),
				px36: px2rem(36),
				px38: px2rem(38),
				px40: px2rem(40),
				px42: px2rem(42),
				px44: px2rem(44),
				px45: px2rem(45),
				px46: px2rem(46),
				px48: px2rem(48),
				px50: px2rem(50),
				px52: px2rem(52),
				px56: px2rem(56),
				px60: px2rem(60),
				px64: px2rem(64),
				px70: px2rem(70),
				px80: px2rem(80),
				px82: px2rem(82),
				px90: px2rem(90),
			},
			lineHeight: {
				px16: px2rem(16),
				px18: px2rem(18),
				px20: px2rem(20),
				px22: px2rem(22),
				px24: px2rem(24),
				px26: px2rem(26),
				px28: px2rem(28),
				px30: px2rem(30),
				px32: px2rem(32),
				px34: px2rem(34),
				px36: px2rem(36),
				px38: px2rem(38),
				px40: px2rem(40),
				px42: px2rem(42),
				px44: px2rem(44),
				px46: px2rem(46),
				px48: px2rem(48),
				px50: px2rem(50),
				px52: px2rem(52),
				px54: px2rem(54),
				px56: px2rem(56),
				px60: px2rem(60),
				px62: px2rem(62),
				px66: px2rem(66),
				px68: px2rem(68),
				px70: px2rem(70),
				px80: px2rem(80),
				px92: px2rem(92),
				px100: px2rem(100),
			},
			letterSpacing: {
				em005: '0.005em',
				em001: '0.01em',
				em002: '0.02em',
				em_001: '-0.01em', // todo: convert to tailwind negative
				px05: px2rem(.5),
				px_015: px2rem(-1.5),
				px_02: px2rem(-2),
			},
			zIndex: {
				'1': '1',
				'2': '2',
				'-1': '-1',
				30: 30,
				31: 31
			},
			gap: {
				'sm': px2rem(12),
				'md': px2rem(28),
			},
			spacing: {
				full: '100%',
				double: '100%',
				pxn5: px2rem(-5),
				pxn25: px2rem(-25),
				pxn28: px2rem(-28),
				pxn2: px2rem(-2),
				px2: px2rem(2),
				px4: px2rem(4),
				px5: px2rem(5),
				px6: px2rem(6),
				px7: px2rem(7),
				px8: px2rem(8),
				px9: px2rem(9),
				px10: px2rem(10),
				px12: px2rem(12),
				px14: px2rem(14),
				px15: px2rem(15),
				px16: px2rem(16),
				px17: px2rem(17),
				px18: px2rem(18),
				px20: px2rem(20),
				px22: px2rem(22),
				px23: px2rem(23),
				px24: px2rem(24),
				px26: px2rem(26),
				px27: px2rem(27),
				px28: px2rem(28),
				px30: px2rem(30),
				px32: px2rem(32),
				px34: px2rem(34),
				px35: px2rem(35),
				px37: px2rem(37),
				px38: px2rem(38),
				px40: px2rem(40),
				px41: px2rem(41),
				px42: px2rem(42),
				px43: px2rem(43),
				px45: px2rem(45),
				px48: px2rem(48),
				px50: px2rem(50),
				px55: px2rem(55),
				px58: px2rem(58),
				px59: px2rem(59),
				px60: px2rem(60),
				px64: px2rem(64),
				px65: px2rem(65),
				px68: px2rem(68),
				px70: px2rem(70),
				px72: px2rem(72),
				px74: px2rem(74),
				px76: px2rem(76),
				px80: px2rem(80),
				px84: px2rem(84),
				px86: px2rem(86),
				px88: px2rem(88),
				px90: px2rem(90),
				px92: px2rem(92),
				px100: px2rem(100),
				px102: px2rem(102),
				px104: px2rem(104),
				px106: px2rem(106),
				px108: px2rem(108),
				px110: px2rem(110),
				px117: px2rem(117),
				px120: px2rem(120),
				px130: px2rem(130),
				px127: px2rem(127),
				px135: px2rem(135),
				px137: px2rem(137),
				px140: px2rem(140),
				px145: px2rem(145),
				px150: px2rem(150),
				px154: px2rem(154),
				px160: px2rem(160),
				px170: px2rem(170),
				px168: px2rem(168),
				px188: px2rem(188),
				px190: px2rem(190),
				px200: px2rem(200),
				px209: px2rem(209),
				px210: px2rem(210),
				px292: px2rem(292),
				px311: px2rem(311),
				px319: px2rem(319),
				px355: px2rem(355),
				px395: px2rem(395),
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
