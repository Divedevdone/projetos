<?php
if (!isset($_GET['target'])) {
    http_response_code(400);
    echo "Parâmetro inválido.";
    exit;
}

$target = basename($_GET['target']); // evita path traversal
$file = __DIR__ . "/content-$target.php";

if (file_exists($file)) {
    include $file;
} else {
    http_response_code(404);
    echo "Conteúdo não encontrado.";
}
