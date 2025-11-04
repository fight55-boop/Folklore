// login.js - 更新版本

// 选项卡切换功能
document.getElementById('登录选项卡').addEventListener('click', function () {
    this.classList.add('激活');
    document.getElementById('注册选项卡').classList.remove('激活');
    document.getElementById('登录表单').style.display = 'block';
    document.getElementById('注册表单').style.display = 'none';
    document.getElementById('用户名').value = '';
    document.getElementById('密码').value = '';
});

document.getElementById('注册选项卡').addEventListener('click', function () {
    this.classList.add('激活');
    document.getElementById('登录选项卡').classList.remove('激活');
    document.getElementById('注册表单').style.display = 'block';
    document.getElementById('登录表单').style.display = 'none';
    document.getElementById('注册用户名').value = '';
    document.getElementById('注册密码').value = '';
    document.getElementById('确认密码').value = '';
});

// 登录表单提交
document.getElementById('登录表单').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById("用户名").value;
    const password = document.getElementById("密码").value;
    const remember = document.getElementById("记住").checked;

    // 发送登录请求
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `用户名=${encodeURIComponent(username)}&密码=${encodeURIComponent(password)}&记住=${remember ? '1' : '0'}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.setUserId(data.user_id);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('网络错误，请稍后重试');
        });
});

// 注册表单提交
document.getElementById('注册表单').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById("注册用户名").value;
    const password = document.getElementById("注册密码").value;
    const confirmPassword = document.getElementById("确认密码").value;

    const result = validatePassword(password);

    if (!result.isValid) {
        alert(result.errors.join('\n'));
        return;
    }

    if (password !== confirmPassword) {
        alert('两次输入的密码不一致');
        return;
    }

    // 发送注册请求
    fetch('register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `注册用户名=${encodeURIComponent(username)}&注册密码=${encodeURIComponent(password)}&确认密码=${encodeURIComponent(confirmPassword)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // 注册成功后切换到登录选项卡
                document.getElementById('登录选项卡').click();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('网络错误: ' + error.message);
        });
});

function validatePassword(password) {
    const rules = {
        minLength: 8,
        maxLength: 20
    };

    const result = {
        isValid: true,
        errors: [],
        details: {
            length: false
        },
    };

    if (password.length < rules.minLength) {
        result.isValid = false;
        result.errors.push(`密码长度至少为${rules.minLength}个字符`);
    } else if (password.length > rules.maxLength) {
        result.isValid = false;
        result.errors.push(`密码长度不能超过${rules.maxLength}个字符`);
    } else {
        result.details.length = true;
    }
    return result;
}