const Encore = require('@symfony/webpack-encore');
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]'
    })
    .copyFiles({
        from: './node_modules/@fortawesome/fontawesome-free/webfonts/',
        to: 'fonts/[name].[ext]'
    })
    .copyFiles([
        { from: './node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false },
        { from: './node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]' },
        { from: './node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]' },
        { from: './node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]' },
        { from: './node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]' },
        { from: './node_modules/ckeditor4/vendor', to: 'ckeditor/vendor/[path][name].[ext]' }
    ])
    .enableStimulusBridge('./assets/controllers.json')
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
    .autoProvidejQuery()
    ;

module.exports = Encore.getWebpackConfig();
