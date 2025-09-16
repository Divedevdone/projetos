<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// Pegar os arquivos do banco
$sql = "SELECT id, nome FROM rede ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos da rede</title>
    <link rel="stylesheet" href="content.css">
    
</head>

<body>
    <p class="title">Projetos da rede</p>
    <p class="sub">
       Iniciativas e projetos desenvolvidos pela rede municipal de ensino em educação digital.
    </p>

    <div class="feature-highlight">
        <div class="icons">📂</div>
        <strong>Lista de arquivos:</strong>

        <div class="recursos-lista">
            <?php if ($result && $result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <a href="rede/visualizar-rede.php?id=<?= $row['id'] ?>" target="_blank">
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
    

    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>
    <!-- Botão de lápis fixo -->
  <?php if (isset($_SESSION["usuario"])): ?>
  <div class="btn-add" onclick="addDataRede()" data-autor="🤖 RoboEdu:" data-fala="Editar">
    <span id="btn-icon">✏️</span>
  </div>
  <input type="file" id="hiddenUpload" style="display:none" />
<?php endif; ?>
</body>
</html>
