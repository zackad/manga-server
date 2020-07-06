const Encore = require('@symfony/webpack-encore')

Encore.addEntry('app', './assets/index.js')
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enablePreactPreset()
  .enableReactPreset()
  .enablePostCssLoader()
  .enableVersioning(Encore.isProduction())

module.exports = Encore.getWebpackConfig()
