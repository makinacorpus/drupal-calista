const ExtractTextPlugin = require("extract-text-webpack-plugin");
const path = require('path');
const webpack = require('webpack');

const distDirectory = path.resolve(__dirname, 'js');
const extractLess = new ExtractTextPlugin({
  filename: "calista.css",
});

module.exports = {
  entry: './resources/index.js',

  devtool: 'source-map',

  plugins: [
//    new webpack.optimize.UglifyJsPlugin({
//      sourceMap: 0
//    }),
    new webpack.LoaderOptionsPlugin({
      options: {
        jshint: {
          esversion: 6
        }
      }
    }),
    extractLess
  ],

  module: {
    rules: [{
      test: /\.tsx?$/,
      exclude: /node_modules/,
      use: "ts-loader"
    }, {
      test: /\.js$/,
      enforce: "pre",
      exclude: /node_modules/,
      use: "jshint-loader"
    }, {
      test: /\.less$/,
      use: extractLess.extract({
        fallback: "style-loader",
        use: [{
          loader: "css-loader"
        }, {
          loader: "less-loader"
        }]
      })
    }]
  },

  resolve: {
    extensions: [".tsx", ".ts", ".js"]
  },

  output: {
    filename: 'calista.js',
    path: distDirectory
  }
};
