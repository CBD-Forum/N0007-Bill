var path = require('path')
var postcss = require('postcss')

exports.postfactory = function (opts) {
    return [
    	//css层级写法  https://github.com/postcss/postcss-nested
    	require('postcss-nested')(),
        //css浏览器兼容
		require('autoprefixer')({ browsers: ['last 2 versions'] }),
       
    ];
}
