(function (config) {
    config['lock'] = true;
    config['background'] = "#000";
})(art.dialog.defaults);

// 表单验证
function check (input) {
    if (input.value == '' || input.value.length==0 || $.trim(input.value) == '') {
    	input.value='';
        inputError(input);
        input.focus();
        return false;
    } else {
    	input.style.border = '';
        return true;
    };
};

//验证select
function iselect (input) {
    if (input.value == '' || input.value=='0') {
        inputError(input);
        input.focus();
        return false;
    } else {
    	input.style.border = '';
        return true;
    };
};

// 输入错误提示
function  inputError(input) {
    clearTimeout(inputError.timer);
    var num = 0;
    var fn = function () {
        inputError.timer = setTimeout(function () {
            input.style.border = input.style.border === '' ? '1px solid #f00' : '';
            if (num === 5) {
                input.style.border = '1px solid #f00';
            } else {
                fn(num ++);
            };
        }, 150);
    };
    fn();
};