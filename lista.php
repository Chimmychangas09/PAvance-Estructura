<?php
// lista.php
session_start();
require_once 'config/database.php';
require_once 'funciones/validacion.php';

$password_acceso = "admin123";

// --- LÓGICA DE LOGIN (Se mantiene igual) ---
if (!isset($_SESSION['autenticado'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_acceso'])) {
        if ($_POST['password_acceso'] === $password_acceso) {
            $_SESSION['autenticado'] = true;
        } else {
            $error_login = "Contraseña incorrecta";
        }
    }

    if (!isset($_SESSION['autenticado'])) {
        $tituloPagina = "Acceso";
        $contenedor_clase = "contenedor";
        include 'includes/header.php';
        ?>
        <h2>Acceso a Lista de Usuarios</h2>
        <?php if (isset($error_login)): ?>
            <div class="mensaje-error"><?php echo $error_login; ?></div>
        <?php endif; ?>
        <form method="POST" class="formulario">
            <div class="campo">
                <label>Contraseña:</label>
                <input type="password" name="password_acceso" required>
            </div>
            <button type="submit" class="boton">Acceder</button>
        </form>
        <?php
        include 'includes/footer.php';
        exit;
    }
}

// --- USUARIO AUTENTICADO: CAMBIOS PDO AQUÍ ---
$pdo = conectarDB(); // 1. Usamos el nombre de variable $pdo por claridad

// 2. Ejecutamos la consulta con PDO
$stmt = $pdo->query("SELECT id, usuario, email, fecha_registro FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll(); // Traemos todos los resultados a un array

$tituloPagina = "Lista de Usuarios";
$contenedor_clase = "contenedor-tabla";
include 'includes/header.php';
?>

<h1>Usuarios Registrados</h1>

<?php if (count($usuarios) > 0): ?> 
    <div class="tabla-container">
        <table class="tabla-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $fila): ?> 
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($fila['email']); ?></td>
                        <td><?php echo $fila['fecha_registro']; ?></td>
                        <td>
                            <a href="perfil.php?id=<?php echo $fila['id']; ?>" class="btn-accion">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="mensaje-error">No hay usuarios</div>
<?php endif; ?>

<div class="volver-flex">
    <a href="registro.php" class="enlace">Nuevo usuario</a>
    <a href="logout.php" class="enlace cerrar-sesion">Salir</a>
</div>

<?php
// mysqli_close($conn); // <-- Esto ya no es necesario con PDO
include 'includes/footer.php';
?>