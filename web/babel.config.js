module.exports = {
    presets: ['@vue/cli-plugin-babel/preset'],
    env: {
        development: {
            plugins: ['dynamic-import-node']
        }
    },
    plugins: [
        [
            'component',
            {
                libraryName: 'element-ui',
                styleLibraryName: 'theme-chalk'
            }
        ],
        [
            'import',
            {
                libraryName: 'vant',
                libraryDirectory: 'es',
                style: true
            },
            'vant'
        ]
    ]
};
