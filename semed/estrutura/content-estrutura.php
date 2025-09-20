<?php
session_start();
include __DIR__ . '/../conexao.php';

$usuarioLogadoId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

// Pegar apenas os arquivos do usuário logado
$sql = "SELECT id, nome, usuario_id FROM recursos WHERE usuario_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioLogadoId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Núcleo de Educação Digital</title>
    <link rel="stylesheet" href="content.css">
    
</head>

<body>
    <!-- Título e subtítulo -->
    <p class="title">Núcleo de Educação Digital</p>
    <p class="sub">
        Documentos e informações sobre a estrutura e funcionamento do núcleo de Educação Digital do município.
    </p>

    <!-- Bloco de destaque -->
    <div class="feature-highlight">
        <div class="icons">📂</div>
        <strong>Lista de arquivos:</strong>

        <!-- Lista de recursos vinda do PHP -->
        <div class="recursos-lista">
            <?php if ($result && $result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <a href="estrutura/visualizar-estrutura.php?id=<?= $row['id'] ?>" target="_blank">
                                <?= htmlspecialchars($row['nome']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum recurso disponível.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>

        <!-- Botão de lápis fixo (visível só se logado) -->
        <?php if (isset($_SESSION["usuario_id"])): ?>
            <div class="btn-add" onclick="addDataEstrutura()" data-autor="🤖 RoboEdu:" data-fala="Editar">
                <span id="btn-icon">✏️</span>
            </div>
            <input type="file" id="hiddenUpload" style="display:none" />
        <?php endif; ?>


</body>

