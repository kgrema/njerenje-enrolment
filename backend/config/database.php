<?php
class Database {
    private $connection;

    public function __construct() {
        // Get database URL from Render environment
        $databaseUrl = getenv('DATABASE_URL');
        
        if ($databaseUrl) {
            // Parse PostgreSQL connection string
            $url = parse_url($databaseUrl);
            
            $host = $url['host'];
            $port = $url['port'];
            $dbname = ltrim($url['path'], '/');
            $username = $url['user'];
            $password = $url['pass'];
            
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            
            try {
                $this->connection = new PDO($dsn, $username, $password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>