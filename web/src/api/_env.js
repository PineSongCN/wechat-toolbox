let env;
let baseUrl;
// // env = 'development';
// env = 'development-url';
// // env = 'production';
// if (process.env.NODE_ENV === 'production') {
//     env = process.env.NODE_ENV;
// }
// switch (env) {
//     case 'development':
//         baseUrl = {
//             base: '/proxy-base'
//         };
//         break;
//     case 'production':
//     default:
//         baseUrl = {
//             base: process.env.VUE_APP_BASE_API
//         };
//         break;
// }
// console.log('process',process.env)
baseUrl = {
    base: '/proxy-base'
};
export default baseUrl;
