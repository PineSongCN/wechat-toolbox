module.exports = {
    root: true,
    parserOptions: {
        parser: 'babel-eslint',
        sourceType: 'module'
    },
    env: {
        browser: true,
        node: true,
        es6: true
    },
    extends: ['plugin:vue/recommended', '@vue/prettier'],

    // add your custom rules here
    //it is base on https://github.com/vuejs/eslint-config-vue
    rules: {
        // 'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-console': 'off',
        'no-unused-vars': 0,
        indent: [0, 4],
        quotes: ['error', 'single'],
        'prettier/prettier': [0, { tabWidth: 4 }],
        'no-unused-vars': 0,
        'vue/no-v-html': 0,
        'vue/no-unused-vars': 0,
        'vue/no-unused-components': 0,
        'vue/no-use-v-if-with-v-for': [0],
        'vue/no-mutating-props': 0
    }
};
