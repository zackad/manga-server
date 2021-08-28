const path = require('path')
const Encore = require('@symfony/webpack-encore')

Encore.addEntry('app', './assets/index.tsx')
  .addAliases({
    App: path.resolve('assets/'),
  })
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enablePreactPreset()
  .enableTypeScriptLoader()
  .enableVersioning(Encore.isProduction())

module.exports = Encore.getWebpackConfig()
