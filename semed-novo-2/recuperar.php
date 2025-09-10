<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT nome, tipo, arquivo FROM recursos WHERE id = ?");
    $stmt = $conn->prepare("SELECT nome, tipo, arquivo FROM referencial WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nome, $tipo, $arquivo);
    $stmt->fetch();

    header("Content-Type: $tipo");
    header("Content-Disposition: inline; filename=\"$nome\"");
    echo $arquivo;

    $stmt->close();
}
$conn->close();
?>
