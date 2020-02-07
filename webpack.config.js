const Encore = require('@symfony/webpack-encore')

Encore.addEntry('app', './assets/index.js')
  .setOutputPath('static')
  .setPublicPath('/static')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enablePreactPreset()
  .enablePostCssLoader()

module.exports = Encore.getWebpackConfig()
