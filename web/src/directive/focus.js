const vueFocus = {};
vueFocus.install = (Vue) => {
    Vue.directive('focus', {
        inserted(el, binding) {
            if (binding.value) {
                const tagName =
                    typeof binding.value === 'string'
                        ? binding.value.trim()
                        : 'input';
                if (el.tagName.toLowerCase() !== tagName.toLowerCase()) {
                    el = el.querySelector(tagName);
                }
                if (el) {
                    el.focus();
                }
            }
        }
    });
};

export default vueFocus;
