<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$rutaBase = dirname(__DIR__);
$archivoEnv = $rutaBase . '/.env';

try {
    // 1. Intentar cargar con la librería oficial
    if (file_exists($archivoEnv)) {
        $dotenv = Dotenv::createImmutable($rutaBase);
        $dotenv->load();
    } else {
        die("❌ Error: No se encontró el archivo .env en $rutaBase");
    }

    // 2. Si la librería falló (variables vacías), forzamos lectura manual básica
    if (empty($_ENV['DB_HOST'])) {
        $lineas = file($archivoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            if (strpos(trim($linea), '=') !== false && strpos(trim($linea), '#') !== 0) {
                list($nombre, $valor) = explode('=', $linea, 2);
                $_ENV[trim($nombre)] = trim($valor);
            }
        }
    }

    // 3. Asignación de variables
    $host     = $_ENV['DB_HOST'] ?? null;
    $port     = $_ENV['DB_PORT'] ?? null;
    $dbname   = $_ENV['DB_NAME'] ?? null;
    $user     = $_ENV['DB_USER'] ?? null;
    $password = $_ENV['DB_PASS'] ?? null;

    if (!$host) {
        die("❌ Error: El archivo .env se leyó pero las variables están vacías o mal formateadas.");
    }

    // 4. Conexión PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (Exception $e) {
    die("❌ Error crítico: " . $e->getMessage());
}