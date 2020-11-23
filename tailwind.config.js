module.exports = {
	purge: {
		preserveHtmlElements: true,
		content: [
			'./src/theme/**/*.js',
			'./src/theme/**/*.scss',
			'./theme/*.php',
			'./theme/template-parts/**/*.php',
			'./theme/inc/**/*.php',
		]
	},
	target: 'relaxed',
	prefix: '',
	important: false,
	separator: ':',
	theme: {
		screens: {
			sm: '640px',
			md: '768px',
			lg: '1024px',
			xl: '1280px',
			dark: {'raw': '(prefers-color-scheme: dark)'},
		},
		colors: {
			transparent: 'transparent',
			// current: 'currentColor',

			black: '#000',
			gray: {
				DEFAULT: '#ccc'
			},
			white: '#fff',
			blue: {
				light: '#D7F0FD',
				DEFAULT: '#7ED1FF',
				dark: '#3D94E2',
			}
		},
		extend: {
			container: {
				padding: {
					DEFAULT: '1rem',
					md: '2rem',
				},
			},
		},
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
	],
}
