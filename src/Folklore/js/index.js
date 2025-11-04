// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function () {
    // 导航菜单激活效果
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // 按钮悬停效果增强
    const buttons = document.querySelectorAll('.action-btn, .login-btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function () {
            this.style.boxShadow = '0 5px 15px rgba(201, 170, 113, 0.3)';
        });

        button.addEventListener('mouseleave', function () {
            this.style.boxShadow = 'none';
        });
    });

    // 版本更新项点击效果
    const updateItems = document.querySelectorAll('.update-item');
    updateItems.forEach(item => {
        item.addEventListener('click', function () {
            updateItems.forEach(i => i.style.backgroundColor = 'transparent');
            this.style.backgroundColor = 'rgba(201, 170, 113, 0.1)';
        });
    });

    // 登录按钮功能
    const loginBtn = document.querySelector('.login-btn');
    loginBtn.addEventListener('click', function () {
        window.location.href = 'login.html';
    });

    // 操作按钮功能
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach((button, index) => {
        button.addEventListener('click', function () {
            const actions = ['账户充值', '一卡通购买'];
            alert(`${actions[index]}功能即将开放！`);
        });
    });

    // 查看更多链接功能
    const viewMoreLink = document.querySelector('.view-more');
    viewMoreLink.addEventListener('click', function (e) {
        e.preventDefault();
        alert('查看更多版本更新内容');
    });

    // 游戏卡片点击效果
    const gameCards = document.querySelectorAll('.game-card');
    gameCards.forEach(card => {
        card.addEventListener('click', function () {
            const gameName = this.querySelector('h3').textContent;
            alert(`即将跳转到${gameName}游戏页面`);
        });
    });

    // 添加页面加载动画效果
    const mainContent = document.querySelector('.main-container');
    mainContent.style.opacity = '0';
    mainContent.style.transform = 'translateY(20px)';

    setTimeout(() => {
        mainContent.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        mainContent.style.opacity = '1';
        mainContent.style.transform = 'translateY(0)';
    }, 300);
});


// 背景图片数组
const images = [
    'images/back1.jpg',
    'images/back2.jpg',
    'images/back3.jpg'
];

let index = 0;

function changeBackground() {
    document.body.style.setProperty(
        '--bg',
        `url('${images[index]}')`
    );
    document.body.style.backgroundImage = `url('${images[index]}')`;
    document.body.style.backgroundSize = 'cover';
    document.body.style.backgroundPosition = 'center';

    // 伪元素背景切换
    document.body.style.setProperty(
        'background-image',
        `url('${images[index]}')`
    );
    document.body.style.setProperty('--current-bg', `url('${images[index]}')`);
    document.body.style.cssText += `--current-bg: url('${images[index]}');`;

    document.body.style.setProperty('--bg-image', `url('${images[index]}')`);

    // 改伪元素样式
    document.body.style.setProperty('--bg', `url('${images[index]}')`);
    document.body.style.cssText = `
      --bg:url('${images[index]}');
    `;

    document.body.style.background = `url('${images[index]}') center/cover no-repeat`;

    document.styleSheets[0].addRule(
        'body::before',
        `background-image:url('${images[index]}')`
    );

    index = (index + 1) % images.length;
}

// 初始化
document.styleSheets[0].addRule(
    'body::before',
    `background-image:url('${images[0]}')`
);

// 每 5 秒切换一次背景
setInterval(changeBackground, 3000);
