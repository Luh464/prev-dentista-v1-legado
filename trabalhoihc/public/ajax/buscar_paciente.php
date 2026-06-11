<?php
// public/ajax/buscar_paciente.php
// Endpoint AJAX dedicado — sem roteador, sem middleware de sessão
// Retorna JSON puro com pacientes que correspondem ao termo buscado

// Suprimir qualquer erro/warning para não contaminar o JSON
error_reporting(0);
ini_set('display_errors', '0');

// Limpar buffer
if (ob_get_level()) ob_clean();

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache');

define('ROOT', dirname(dirname(__DIR__)));

try {
    // Carregar configurações mínimas
    require_once ROOT . '/config/database.php';

    $term = trim($_GET['term'] ?? '');

    if (strlen($term) < 1) {
        echo json_encode([]);
        exit;
    }

    $like = '%' . $term . '%';
    $stmt = $pdo->prepare(
        "SELECT id, nome, cpf, telefone, email
         FROM pacientes
         WHERE nome LIKE ? OR cpf LIKE ?
         ORDER BY nome ASC
         LIMIT 15"
    );
    $stmt->execute([$like, $like]);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultado);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro interno: ' . $e->getMessage()]);
}
exit;
