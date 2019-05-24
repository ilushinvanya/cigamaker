module.exports = {
    devServer: {
        port:80,
        proxy: {
            './php': {
                target: 'http://cigamaker.ru',
                changeOrigin: true
            }
        }
    },
    outputDir: '../build/public_html'
}