<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed


if (!isset($_POST['id'])) {
    echo json_encode(["status" => "erro", "msg" => "ID não informado"]);
    exit;
}

$id = intval($_POST['id']);

// Preparar e executar
$stmt = $conn->prepare("DELETE FROM educacao WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "msg" => "Arquivo excluído com sucesso"]);
} else {
    echo json_encode(["status" => "erro", "msg" => "Erro ao excluir"]);
}

$stmt->close();
$conn->close();
