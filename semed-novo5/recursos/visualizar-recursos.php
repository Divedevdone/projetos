<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed


$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT tipo, arquivo FROM adicionais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($tipo, $arquivo);
    $stmt->fetch();

    header("Content-Type: $tipo");
    echo $arquivo;
} else {
    echo "Arquivo não encontrado.";
}

$stmt->close();
$conn->close();
?>
