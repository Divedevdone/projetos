<?php
header('Content-Type: application/json');
include __DIR__ . '/../conexao.php'; // conexão com db-semed

$sql = "SELECT id, nome, tipo FROM educacao ORDER BY id DESC";
$result = $conn->query($sql);

$arquivos = [];

while ($row = $result->fetch_assoc()) {
    $arquivos[] = [
        "id" => $row["id"],
        "nome" => $row["nome"],
        "tipo" => $row["tipo"],
        "url" => "download-educacao.php?id=" . $row["id"] // link para baixar o arquivo
    ];
}

echo json_encode($arquivos);
$conn->close();
?>

