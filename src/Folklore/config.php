<?php
// config.php - 数据库连接配置文件
class Database {
    private $host = "localhost";
    private $db_name = "folk_stories";
    private $username = "folk_stories";
    private $password = "B2dP6n4BbZ636iFJ";
    public $conn;
    
    // 获取数据库连接
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // 连接成功，可以在这里添加日志记录
            error_log("数据库连接成功 - " . date('Y-m-d H:i:s'));
            
        } catch(PDOException $exception) {
            // 记录错误日志
            error_log("数据库连接失败: " . $exception->getMessage());
            
            // 返回错误信息（开发环境下）
            if (isset($_GET['debug'])) {
                echo "数据库连接错误: " . $exception->getMessage();
            }
        }
        
        return $this->conn;
    }
}

// 创建数据库实例
$database = new Database();
$db = $database->getConnection();

// 检查连接是否成功
if (!$db) {
    // 在生产环境中，您可能想要记录错误或显示友好消息
    error_log("无法建立数据库连接");
}
?>