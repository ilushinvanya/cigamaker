module.exports = {
    devServer: {
        port:80,
        proxy: {
            './php': {
                target: 'http://cigamaker.ru',
                // changeOrigin: true,
                changeOrigin: true
            },
            // '^/php': {
            //     target: 'http://cigamaker.ru',
            //     changeOrigin: true
            // }
        }
    },
    outputDir: '../build/public_html',
    // pwa: {
    //     name: 'My App',
    //     themeColor: '#4DBA87',
    //     msTileColor: '#000000',
    //     appleMobileWebAppCapable: 'yes',
    //     appleMobileWebAppStatusBarStyle: 'black',
    //
    //     // configure the workbox plugin
    //     workboxPluginMode: 'InjectManifest',
    //     workboxOptions: {
    //         // swSrc is required in InjectManifest mode.
    //         swSrc: 'dev/sw.js',
    //         // ...other Workbox options...
    //     }
    // }
}