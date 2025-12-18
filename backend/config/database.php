<?php
// Save as backend/config/database.php
class Database {
    private $conn;
    private $host;
    private $db_name;
    private $username;
    private $password;

    public function __construct() {
        // Get environment variables from Render
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'njerenje_db';
        $this->username = getenv('DB_USERNAME') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            // Don't show database errors to users
            throw new Exception("Database connection failed. Please try again later.");
        }
        
        return $this->conn;
    }
}
?>