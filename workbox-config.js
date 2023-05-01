module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{css,ico,png,js,json}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};