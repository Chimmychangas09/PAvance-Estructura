<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

function conectarDB() {
    $rutaBase = dirname(__DIR__);
    
    try {
        $dotenv = Dotenv::createImmutable($rutaBase);
        $dotenv->load();

        $host     = $_ENV['DB_HOST'];
        $port     = $_ENV['DB_PORT'] ?? 5432;
        $dbname   = $_ENV['DB_NAME'];
        $user     = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
        
        // Retornamos el objeto PDO
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

    } catch (Exception $e) {
        die("❌ Error de conexión: " . $e->getMessage());
    }
}