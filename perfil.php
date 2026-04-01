<?php
// perfil.php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: lista.php');
    exit;
}

require_once 'config/database.php';
require_once 'funciones/validacion.php';

// 1. Obtener y validar el ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: lista.php');
    exit;
}

try {
    $pdo = conectarDB();

    // 2. Consulta con Marcadores (Sentencia Preparada)
    // Nota: Asegúrate de si tu columna es 'created_at' o 'fecha_registro' 
    // (en el lista.php usabas fecha_registro, cámbialo aquí si es necesario)
    $stmt = $pdo->prepare("SELECT usuario, email, fecha_registro FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // 3. Obtener el usuario
    $usuario = $stmt->fetch();

} catch (PDOException $e) {
    die("❌ Error en la base de datos: " . $e->getMessage());
}

// Si no existe el usuario, de vuelta a la lista
if (!$usuario) {
    header('Location: lista.php');
    exit;
}

$tituloPagina = "Perfil de " . htmlspecialchars($usuario['usuario']);
$contenedor_clase = "contenedor";
include 'includes/header.php';
?>

<div class="contenedor">
    <h1>Perfil de Usuario</h1>
    <div class="perfil-header">
        <div class="perfil-avatar">
            <span class="avatar-inicial">
                <?php echo strtoupper(substr($usuario['usuario'], 0, 1)); ?>
            </span>
        </div>
        <div class="perfil-info">
            <h2><?php echo htmlspecialchars($usuario['usuario']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Miembro desde:</strong> 
                <?php 
                    // Postgres suele devolver el timestamp, lo formateamos bonito
                    echo date('d/m/Y', strtotime($usuario['fecha_registro'])); 
                ?>
            </p>
        </div>
    </div>

    <div class="volver">
        <a href="lista.php" class="enlace">← Volver a la lista</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>