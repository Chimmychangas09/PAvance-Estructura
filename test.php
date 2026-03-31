<?php
require_once 'config/conexion.php';

try {
    // Datos de prueba
    $usuario = "CamiDev";
    $email = "cami@ejemplo.com";
    $password = password_hash("mi_clave_secreta", PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (usuario, email, password_hash) VALUES (:user, :email, :pass)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':user'  => $usuario,
        ':email' => $email,
        ':pass'  => $password
    ]);

    echo "✅ Usuario insertado con éxito. Revisa tu tabla en Supabase.";

} catch (PDOException $e) {
    // Si lo corres dos veces, aquí te dirá que el usuario ya existe (por el UNIQUE)
    echo "❌ Error al insertar: " . $e->getMessage();
}