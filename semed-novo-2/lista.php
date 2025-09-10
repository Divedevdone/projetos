<?php
header('Content-Type: application/json');
include 'conexao.php';

$sql = "SELECT id, nome, tipo FROM recursos ORDER BY id DESC";
$sql = "SELECT id, nome, tipo FROM refencial ORDER BY id DESC";
$result = $conn->query($sql);

$arquivos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arquivos[] = [
            "id" => $row['id'],
            "nome" => $row['nome'],
            "tipo" => $row['tipo'],
            "url" => "download.php?id=" . $row['id'] // link para download
        ];
    }
}

echo json_encode($arquivos);

$conn->close();
?>
