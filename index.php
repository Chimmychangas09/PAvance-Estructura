<?php
// index.php
session_start();
require_once 'config/database.php'; // Asegúrate que el nombre coincida
require_once 'funciones/validacion.php';

$pdo = conectarDB(); // Ahora $pdo es un objeto PDO

// Cambiamos mysqli por la sintaxis de PDO
$stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
$total_usuarios = $stmt->fetch()['total'];

// En PDO no es estrictamente necesario cerrar la conexión como en mysqli, 
// se cierra sola al terminar el script o haciendo $pdo = null;

$tituloPagina = "Inicio";
$contenedor_clase = "contenedor";
include 'includes/header.php';
?>