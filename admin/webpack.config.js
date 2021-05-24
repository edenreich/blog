const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('src/static')
    .setPublicPath('/')
    .addEntry('app', './src/assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableReactPreset()
    .autoProvidejQuery()
    .configureFilenames({
        js: '[name].js?[chunkhash]',
        css: '[name].css?[contenthash]',
    })
    .addPlugin(new webpack.IgnorePlugin(/^\.\/node_modules\/admin-lte\/plugins\/moment\/locale$/))
;

module.exports = Encore.getWebpackConfig();
