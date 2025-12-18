<?php
class Database {
    private $connection;

    public function __construct() {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        
        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("Database connected successfully");
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            $this->connection = null;
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>