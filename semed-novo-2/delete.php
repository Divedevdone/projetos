<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    die("Acesso negado");
}

if (isset($_GET['file'])) {
    $arquivo = "recursos/" . basename($_GET['file']);

    if (file_exists($arquivo)) {
        unlink($arquivo);
        echo "Arquivo excluído com sucesso! <a href='index.php'>Voltar</a>";
    } else {
        echo "Arquivo não encontrado.";
    }
}
