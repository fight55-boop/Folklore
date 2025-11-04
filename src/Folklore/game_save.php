<?php
// 确保文件开头没有空格或空行
session_start();

// 错误处理设置
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 立即设置响应头
header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => ''];

try {
    // 检查会话
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('用户未登录');
    }

    // 引入配置文件
    require_once 'config.php';
    
    // 检查数据库连接 - 使用 $db 而不是 $pdo
    if (!isset($db) || $db === null) {
        throw new Exception('数据库连接失败 - 请检查配置文件');
    }

    // 获取输入数据
    $input = file_get_contents('php://input');
    $input_data = [];
    
    if (!empty($input)) {
        $input_data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // 如果不是 JSON，尝试解析为表单数据
            parse_str($input, $input_data);
        }
    }
    
    // 备用：从 $_POST 获取
    if (empty($input_data) && !empty($_POST)) {
        $input_data = $_POST;
    }

    // 获取游戏数据
    $game_data = $input_data['game_data'] ?? '';

    if (empty($game_data)) {
        throw new Exception('游戏数据为空');
    }

    // 验证游戏数据是有效的 JSON
    $test_json = json_decode($game_data);
    if (json_last_error() !== JSON_ERROR_NONE && $game_data[0] !== '{') {
        // 如果不是 JSON 对象，重新包装
        $game_data = json_encode(['passage' => $game_data, 'timestamp' => date('Y-m-d H:i:s')]);
    }

    $user_id = $_SESSION['user_id'];

    // 检查是否已有存档
    $check_sql = "SELECT id FROM game_saves WHERE user_id = ?";
    $check_stmt = $db->prepare($check_sql); // 使用 $db
    $check_stmt->execute([$user_id]);
    $existing_save = $check_stmt->fetch();

    if ($existing_save) {
        // 更新存档
        $sql = "UPDATE game_saves SET game_data = ?, updated_at = NOW() WHERE user_id = ?";
        $stmt = $db->prepare($sql); // 使用 $db
        $result = $stmt->execute([$game_data, $user_id]);
    } else {
        // 创建新存档
        $sql = "INSERT INTO game_saves (user_id, game_data, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
        $stmt = $db->prepare($sql); // 使用 $db
        $result = $stmt->execute([$user_id, $game_data]);
    }

    if ($result) {
        $response['success'] = true;
        $response['message'] = '游戏进度已保存';
    } else {
        throw new Exception('保存操作失败');
    }

} catch (PDOException $e) {
    $response['message'] = '数据库错误: ' . $e->getMessage();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// 输出 JSON 响应
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;