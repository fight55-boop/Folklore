<?php
// login.php - 用户登录处理

header('Content-Type: application/json; charset=utf-8');

// 引入数据库配置
require_once 'config.php';

session_start();

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    $username = trim($_POST['用户名'] ?? '');
    $password = $_POST['密码'] ?? '';
    $remember = isset($_POST['记住']);
    
    // 基本验证
    if (empty($username) || empty($password)) {
        $response['message'] = '用户名和密码必须填写';
        echo json_encode($response);
        exit;
    }
    
    try {
        // 查询用户
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            
            // 验证密码
            if (password_verify($password, $user['password'])) {
                // 登录成功
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                
                // 记住我功能
                if ($remember) {
                    $cookie_value = $user['id'] . ':' . hash('sha256', $user['password']);
                    setcookie('remember_me', $cookie_value, time() + (86400 * 30), "/"); // 30天
                }
                
                $response['success'] = true;
                $response['message'] = '登录成功！';
                $response['redirect'] = 'start_game.html';
                $response['user_id'] = $user['id']; // 添加这一行返回user_id
            } else {
                $response['message'] = '密码错误';
            }
        } else {
            $response['message'] = '用户名不存在';
        }
        
    } catch(PDOException $e) {
        error_log("登录错误: " . $e->getMessage());
        $response['message'] = '系统错误，请稍后重试';
    }
} else {
    $response['message'] = '无效的请求方法';
}

echo json_encode($response);
?>