<?php
// debug_db.php - 详细数据库调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>数据库连接调试</h2>";

require_once 'config.php';

if (!$db) {
    echo "❌ 数据库连接失败<br>";
    exit;
}

echo "✅ 数据库连接成功<br>";

// 测试表访问
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "✅ 用户表访问成功，记录数: " . $result['count'] . "<br>";
} catch(PDOException $e) {
    echo "❌ 用户表访问失败: " . $e->getMessage() . "<br>";
}

// 测试插入操作（模拟注册）
try {
    $test_username = "test_" . time();
    $hashed_password = password_hash("test123", PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $test_username);
    $stmt->bindParam(':password', $hashed_password);
    
    if ($stmt->execute()) {
        echo "✅ 测试注册成功，用户名: " . $test_username . "<br>";
        
        // 清理测试数据
        $db->query("DELETE FROM users WHERE username = '$test_username'");
        echo "✅ 测试数据已清理<br>";
    } else {
        echo "❌ 测试注册失败<br>";
    }
} catch(PDOException $e) {
    echo "❌ 注册测试失败: " . $e->getMessage() . "<br>";
}

// 显示表结构
echo "<h3>表结构检查:</h3>";
try {
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch(PDOException $e) {
    echo "表结构查询失败: " . $e->getMessage();
}
?>