<?php
header('Content-Type: application/json');
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// Verifica se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Usuário não logado."
    ]);
    exit();
}

$usuarioId = $_SESSION['usuario_id'];

if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $nome = $_FILES['arquivo']['name'];
    $tipo = $_FILES['arquivo']['type'];
    $conteudo = file_get_contents($_FILES['arquivo']['tmp_name']); // pega os bytes do arquivo

    // Prepara o INSERT incluindo usuario_id
    $stmt = $conn->prepare("INSERT INTO recursos (nome, tipo, arquivo, usuario_id) VALUES (?, ?, ?, ?)");
    $null = NULL;
    $stmt->bind_param("ssbi", $nome, $tipo, $null, $usuarioId); // s=string, b=blob, i=int

    // Envia o conteúdo do arquivo como BLOB
    $stmt->send_long_data(2, $conteudo);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "ok",
            "msg" => "Arquivo salvo com sucesso!",
            "nome" => $nome,
            "tipo" => $tipo
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "msg" => "Falha ao gravar arquivo no banco: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "erro",
        "msg" => "Upload inválido ou arquivo não enviado."
    ]);
}

$conn->close();
?>
