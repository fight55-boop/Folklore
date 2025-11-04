<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '用户未登录']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $db->prepare("SELECT game_data FROM game_saves WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $save_data = $stmt->fetch();
    
    if ($save_data && !empty($save_data['game_data'])) {
        echo json_encode([
            'success' => true, 
            'game_data' => $save_data['game_data'],
            'message' => '游戏进度加载成功'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => '未找到以往的游戏进度，开始新游戏吧'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '数据库错误: ' . $e->getMessage()]);
}
?>