const path = require('path');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  target: 'web',
  mode: 'development',
  entry: {
    phasonry: {
      import: [
        path.resolve(__dirname, 'client', 'script-index.js'),
        path.resolve(__dirname, 'client', 'style-index.js')
      ]
    }
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    clean: true,
    filename: '[name].[chunkhash].bundle.js'
  },
  module: {
    rules: [
      {
        include: path.resolve(__dirname, 'client', 'css'),
        test: /\.scss/i,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              esModule: false
            }
          },
          'postcss-loader',
          'sass-loader'
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin(),
    new ESLintPlugin()
  ]
};
