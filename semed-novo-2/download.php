<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT nome, tipo, arquivo FROM recursos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nome, $tipo, $arquivo);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        header("Content-Type: $tipo");
        header("Content-Disposition: attachment; filename=\"$nome\"");
        echo $arquivo;
    } else {
        echo "Arquivo não encontrado.";
    }
    $stmt->close();
}

$conn->close();
?>
