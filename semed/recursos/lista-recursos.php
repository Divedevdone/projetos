<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/../conexao.php'; // conexão com db-semed


$sql = "SELECT id, nome, tipo FROM adicionais ORDER BY id DESC";
$result = $conn->query($sql);

$arquivos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arquivos[] = [
            "id"   => $row['id'],
            "nome" => $row['nome'],
            "tipo" => $row['tipo'],
            "url"  => "download-recursos.php?id=" . $row['id'] // endpoint para baixar
        ];
    }
}

echo json_encode($arquivos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
