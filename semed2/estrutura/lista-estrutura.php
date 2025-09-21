<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

$usuarioLogadoId = $_SESSION['usuario_id'];

// Pegar apenas os arquivos do usuário logado
$sql = "SELECT id, nome, tipo FROM recursos WHERE usuario_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioLogadoId);
$stmt->execute();
$result = $stmt->get_result();

$arquivos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arquivos[] = [
            "id"   => $row['id'],
            "nome" => $row['nome'],
            "tipo" => $row['tipo'],
            "url"  => "download-estrutura.php?id=" . $row['id'] // endpoint para baixar
        ];
    }
}

echo json_encode($arquivos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
