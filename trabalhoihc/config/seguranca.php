<?php
// config/seguranca.php
require_once __DIR__ . '/app.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "?rota=login");
    exit;
}
