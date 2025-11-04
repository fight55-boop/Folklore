<?php
// register.php - 用户注册处理

header('Content-Type: application/json; charset=utf-8');

// 引入数据库配置
require_once 'config.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    $username = trim($_POST['注册用户名'] ?? '');
    $password = $_POST['注册密码'] ?? '';
    $confirm_password = $_POST['确认密码'] ?? '';
    
    // 基本验证
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $response['message'] = '所有字段都必须填写';
        echo json_encode($response);
        exit;
    }
    
    // 用户名长度验证
    if (strlen($username) < 3 || strlen($username) > 20) {
        $response['message'] = '用户名长度必须在3-20个字符之间';
        echo json_encode($response);
        exit;
    }
    
    // 密码长度验证
    if (strlen($password) < 8 || strlen($password) > 20) {
        $response['message'] = '密码长度必须在8-20个字符之间';
        echo json_encode($response);
        exit;
    }
    
    // 确认密码验证
    if ($password !== $confirm_password) {
        $response['message'] = '两次输入的密码不一致';
        echo json_encode($response);
        exit;
    }
    
    try {
        // 检查用户名是否已存在
        $check_sql = "SELECT id FROM users WHERE username = :username";
        $check_stmt = $db->prepare($check_sql);
        $check_stmt->bindParam(':username', $username);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $response['message'] = '用户名已存在，请选择其他用户名';
            echo json_encode($response);
            exit;
        }
        
        // 加密密码
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 插入新用户
        $insert_sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $insert_stmt = $db->prepare($insert_sql);
        $insert_stmt->bindParam(':username', $username);
        $insert_stmt->bindParam(':password', $hashed_password);
        
        if ($insert_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = '注册成功！';
        } else {
            $response['message'] = '注册失败，请稍后重试';
        }
        
    } catch(PDOException $e) {
        error_log("注册错误: " . $e->getMessage());
        $response['message'] = '系统错误，请稍后重试';
    }
} else {
    $response['message'] = '无效的请求方法';
}

echo json_encode($response);
?>