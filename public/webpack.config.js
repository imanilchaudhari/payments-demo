var path = require('path');

module.exports = {
	context: path.resolve(__dirname, '.'),
	entry: {
		'bundle.js': [
			path.resolve(__dirname, './js/main.js'),
			path.resolve(__dirname, './js/site.js')
		]
	},
	output: {
		path: path.resolve(__dirname, 'dist'),
		filename: '[name]',
	},
	module: {
		rules: [
			{
				test: /\.css$/,
				use: [
					'style-loader',
					'css-loader'
				]
			}
		]
	}
};
