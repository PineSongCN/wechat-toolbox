'use strict';
const path = require('path');
// const defaultSettings = require('./src/settings.js');

function resolve(dir) {
    return path.join(__dirname, dir);
}

const name = '念念不忘 必有回响';

const port = process.env.port || process.env.npm_config_port || 6789; // dev port
const BundleAnalyzerPlugin =
    require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
module.exports = {
    publicPath: '/',
    outputDir: 'dist',
    assetsDir: 'static',
    lintOnSave: process.env.NODE_ENV === 'development',
    productionSourceMap: false,
    devServer: {
        port: port,
        open: true,
        overlay: {
            warnings: false,
            errors: true
        },
        proxy: {
            '/proxy-base': {
                target: 'http://local.toolbox.mian.yqt.life/api',
                changeOrigin: true,
                pathRewrite: {
                    '^/proxy-base': ''
                }
            }
        }
    },
    configureWebpack: {
        name: name,
        resolve: {
            alias: {
                '@': resolve('src')
            }
        },
        plugins: [new BundleAnalyzerPlugin()]
    },
    css: {
        loaderOptions: {
            less: {
                // 若 less-loader 版本小于 6.0，请移除 lessOptions 这一级，直接配置选项。
                lessOptions: {
                    modifyVars: {
                        // 直接覆盖变量
                        blue: '#f1b8af',
                        '@blue': '#f1b8af'
                        // 或者可以通过 less 文件覆盖（文件路径为绝对路径）
                        // hack: `true; @import "your-less-file-path.less";`
                    }
                }
            }
        }
    },
    chainWebpack(config) {
        config.plugin('preload').tap(() => [
            {
                rel: 'preload',
                fileBlacklist: [
                    /\.map$/,
                    /hot-update\.js$/,
                    /runtime\..*\.js$/
                ],
                include: 'initial'
            }
        ]);

        config.plugins.delete('prefetch');

        config.module.rule('svg').exclude.add(resolve('src/icons')).end();
        config.module
            .rule('icons')
            .test(/\.svg$/)
            .include.add(resolve('src/icons'))
            .end()
            .use('svg-sprite-loader')
            .loader('svg-sprite-loader')
            .options({
                symbolId: 'icon-[name]'
            })
            .end();

        config.when(process.env.NODE_ENV !== 'development', (config) => {
            config
                .plugin('ScriptExtHtmlWebpackPlugin')
                .after('html')
                .use('script-ext-html-webpack-plugin', [
                    {
                        // `runtime` must same as runtimeChunk name. default is `runtime`
                        inline: /runtime\..*\.js$/
                    }
                ])
                .end();
            config.optimization.splitChunks({
                chunks: 'all',
                minSize: 100000,
                maxSize: 500000,
                cacheGroups: {
                    libs: {
                        name: 'chunk-libs',
                        test: /[\\/]node_modules[\\/]/,
                        priority: 10,
                        chunks: 'initial' // only package third parties that are initially dependent
                    },
                    elementUI: {
                        name: 'chunk-elementUI', // split elementUI into a single package
                        priority: 20, // the weight needs to be larger than libs and app or it will be packaged into libs or app
                        test: /[\\/]node_modules[\\/]_?element-ui(.*)/ // in order to adapt to cnpm
                    },
                    commons: {
                        name: 'chunk-commons',
                        test: resolve('src/components'), // can customize your rules
                        minChunks: 3, //  minimum common number
                        priority: 5,
                        reuseExistingChunk: true
                    }
                }
            });
            config.optimization.runtimeChunk('single');
        });
        // if (process.env.NODE_ENV === 'production') {
        //     if (process.env.ANALYZ_ENV) {
        //         // console.log('config.plugins',config.plugins)
        //         config.plugins.analyze = new BundleAnalyzerPlugin()
        //         // config.plugins.push(new BundleAnalyzerPlugin());
        //     }
        // }
    }
};
