<?php
// config/database.php
$host     = 'localhost';
$db_name  = 'clinica_prev_dentistas';
$username = 'root';   // Altere conforme seu ambiente local
$password = '';       // Altere conforme seu ambiente local

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
