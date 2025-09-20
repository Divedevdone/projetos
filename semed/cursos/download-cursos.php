<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed


$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT nome, tipo, arquivo FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nome, $tipo, $arquivo);
    $stmt->fetch();

    header("Content-Type: $tipo");
    header("Content-Disposition: attachment; filename=\"$nome\"");
    echo $arquivo;
} else {
    echo "Arquivo não encontrado.";
}

$stmt->close();
$conn->close();
?>
