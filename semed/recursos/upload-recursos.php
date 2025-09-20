<?php
header('Content-Type: application/json');
include __DIR__ . '/../conexao.php'; // conexão com db-semed

if ($_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $nome = $_FILES['arquivo']['name'];
    $tipo = $_FILES['arquivo']['type'];
    $conteudo = file_get_contents($_FILES['arquivo']['tmp_name']); // pega os bytes do arquivo

    // Prepara o INSERT
    $stmt = $conn->prepare("INSERT INTO adicionais (nome, tipo, arquivo) VALUES (?, ?, ?)");
    $null = NULL;
    $stmt->bind_param("ssb", $nome, $tipo, $null); 

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
            "msg" => "Falha ao gravar arquivo no banco."
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
