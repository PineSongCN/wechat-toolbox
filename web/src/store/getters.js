const getters = {
    token: state => state.user.token,
    userId: state => state.user.userId,
    nickName: state => state.user.nickName,
    userInfo: state => state.user.userInfo,
};
export default getters;
