var path = require('path')
var webpack = require('webpack')
var CopyWebpackPlugin = require('copy-webpack-plugin')
const vueConfig = require('./vue-loader.config')

module.exports = {
  entry: './resources/assets/js/app.js',
  output: {
    path: path.resolve(__dirname, './public'),
    filename: 'js/app.js'
  },
  module: {
    loaders: [
      {
        test: /\.vue$/,
        loader: 'vue'
      },
      {
        test: /\.js$/,
        loader: 'babel',
        exclude: /node_modules/,
      },
      {
        test: /\.(png|jpg|gif|svg|woff2?|eot|ttf)(\?v=[0-9\.]+)?$/,
        loader: 'file',
        query: {
          limit: 10000,
          name: '[name].[ext]?[hash:7]'
        }
      }
    ]
  },
  plugins: [
    new webpack.LoaderOptionsPlugin({
      vue: vueConfig
    }),
    new webpack.IgnorePlugin(/pusher-js/),
  ],
  devServer: {
    historyApiFallback: true,
    noInfo: false,
    inline: true,
    host: "0.0.0.0",
    watchOptions: {
      poll: true
    }
  },
  devtool: '#eval-source-map'
}

if (process.env.NODE_ENV === 'production') {
  module.exports.devtool = '#source-map'
  // http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      },
    }),

    new webpack.LoaderOptionsPlugin({
      minimize: true
    }),

    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      compress: {
        warnings: false
      },
      output: {
        comments: false
      }
    }),

    // optimize module ids by occurrence count
    new webpack.optimize.OccurrenceOrderPlugin()
  ])
}
