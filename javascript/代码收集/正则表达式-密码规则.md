# js密码规则正则表达式
```
passwordValid: function (password) {
            var passwordReg = new RegExp("^(\\d){6,12}$");
            var passwordLen = password.length;
            var regLen = passwordLen - 1;
            if (!passwordReg.test(password)) {
                $.error("新密码必须为6-12位数字", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            var passwordReg = new RegExp("^(0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){" + regLen + "}");
            if (passwordReg.test(password)) {
                $.error("密码不能为连续的数字", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            var passwordReg = new RegExp("^(9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){" + regLen + "}");
            if (passwordReg.test(password)) {
                $.error("密码不能为倒序的数字", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            var passwordReg = new RegExp("(\\d)\\1{2}");
            if (passwordReg.test(password)) {
                $.error("密码相邻3位不能为同一个数字", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            var passwordReg = new RegExp("(\\d)\\1(\\d)\\2|(\\d)(\\d)\\3\\4|(\\d)(\\d)(\\d)\\5\\6\\7");
            if (passwordReg.test(password)) {
                $.error("任意位置不能出现aabb、abab、abcabc格式数字", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            var investorsBirthday = $('#hidden-data').attr('investorsBirthday');
            if (investorsBirthday == password) {
                $.error("密码不能与生日相同", function () {
                    $('#newPassword').focus();
                });
                return false;
            }
            return true;
        }
      
```
        