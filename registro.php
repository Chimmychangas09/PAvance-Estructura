<?php
// registro.php
session_start();
require_once 'config/database.php';
require_once 'funciones/validacion.php';

$errores = [];
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = limpiarDato($_POST['usuario'] ?? '');
    $email    = limpiarDato($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    $valUsuario = validarUsuario($usuario);
    if ($valUsuario !== true) $errores[] = $valUsuario;

    $valEmail = validarEmail($email);
    if ($valEmail !== true) $errores[] = $valEmail;

    $valPassword = validarPassword($password);
    if ($valPassword !== true) $errores[] = $valPassword;

    if (empty($errores)) {
        try {
            $pdo = conectarDB();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // 1. Sentencia Preparada (Seguridad total contra Inyección SQL)
            $sql = "INSERT INTO usuarios (usuario, email, password_hash) 
                    VALUES (:usuario, :email, :password_hash)";
            
            $stmt = $pdo->prepare($sql);
            
            // 2. Ejecutar pasando los valores limpios
            $stmt->execute([
                ':usuario' => $usuario,
                ':email'   => $email,
                ':password_hash' => $password_hash
            ]);

            $exito = "✅ Usuario registrado correctamente";

        } catch (PDOException $e) {
            // Código 23505 es "Clave duplicada" en PostgreSQL
            if ($e->getCode() == 23505) {
                $errores[] = "❌ El usuario o email ya existe.";
            } else {
                $errores[] = "❌ Error en BD: " . $e->getMessage();
            }
        }
    }
}

$tituloPagina = "Registro";
$contenedor_clase = "contenedor";
include 'includes/header.php';
?>

<div class="contenedor">
    <h1>Registro de Usuario</h1>

    <?php if ($exito): ?>
        <div class="mensaje-exito"><?php echo $exito; ?></div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="mensaje-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="formulario">
        <div class="campo">
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
        </div>
        <div class="campo">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="campo">
            <label>Contraseña:</label>
            <input type="password" name="password">
        </div>
        <button type="submit" class="boton">Registrarse</button>
    </form>
    
    <div class="volver-flex">
        <a href="index.php" class="enlace">Volver al Inicio</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>