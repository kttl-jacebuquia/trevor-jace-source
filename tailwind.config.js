const defaultTheme = require('tailwindcss/defaultTheme')

const px2rem = (px, root = 16) => {
	const remVal = px / root;
	return String(+remVal.toFixed(2)) + 'rem'
}

module.exports = {
	purge: {
		preserveHtmlElements: true,
		content: [
			'./src/theme/**/*.js',
			'./src/theme/**/*.scss',
			'./theme/*.php',
			'./theme/inc/**/*.php',
			'./theme/rc/**/*.php',
			'./theme/template-parts/**/*.php',
			'./plugin/templates/blocks/**/*.twig',
		]
	},
	prefix: '',
	important: false,
	separator: ':',
	theme: {
		screens: {
			'sm': px2rem(375),
			'md': px2rem(768),
			'lg': px2rem(1024),
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
				light: '#EDEDFB',
				lighter: '#DBD5FF',
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
				dark: '#003A48',
				tint: '#D9E2E4'
			}
		},
		// Custom
		postCard: {
			width: {
				DEFAULT: px2rem(319),
				md: px2rem(319),
				lg: px2rem(395),
				xl: px2rem(395),
			}
		},
		carousel: {
			w3Card: {
				minWidth: px2rem(500),
				maxWidth: px2rem(1275),
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
					lg: px2rem(100),
					xl: px2rem(100),
				},
			},
			borderRadius: {
				px10: px2rem(10)
			},
			height: {
				px737: px2rem(737),
				px600: px2rem(600),
				px490: px2rem(490),
			},
			fontSize: {
				px14: px2rem(14),
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
				px60: px2rem(60),
				px70: px2rem(70),
			},
			lineHeight: {
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
				px46: px2rem(46),
				px48: px2rem(48),
				px50: px2rem(50),
				px54: px2rem(54),
				px56: px2rem(56),
				px60: px2rem(60),
				px62: px2rem(62),
				px68: px2rem(68),
				px70: px2rem(70),
				px80: px2rem(80),
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
				'-1': '-1',
			},
			gap: {
				'sm': px2rem(12),
				'md': px2rem(28),
			},
			spacing: {
				px30: px2rem(30),
				px50: px2rem(50),
				px60: px2rem(60),
				px72: px2rem(72),
				px88: px2rem(88),
				px106: px2rem(106),
			}
		},
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
	],
}
