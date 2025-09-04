<?php
if ($_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $pasta = "uploads/"; // Crie essa pasta com permissão de escrita
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $nome = basename($_FILES['arquivo']['name']);
    $destino = $pasta . $nome;

    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino)) {
        echo json_encode(["status" => "ok", "arquivo" => $destino]);
    } else {
        echo json_encode(["status" => "erro"]);
    }
} else {
    echo json_encode(["status" => "erro"]);
}
?>
