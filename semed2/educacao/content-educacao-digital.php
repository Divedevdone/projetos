<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// Pegar os arquivos do banco
$sql = "SELECT id, nome FROM educacao ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educação digital</title>
    <link rel="stylesheet" href="content.css">

    
</head>

<body>
    <p class="title">Educação digital e midiática</p>
    <p class="sub">
        Materiais sobre literatura digital, uso pedagógico de tecnologias e educação midiática.
    </p>

    <div class="feature-highlight">
        <div class="icons">📂</div>
        <strong>Lista de arquivos:</strong>

        <div class="recursos-lista">
            <?php if ($result && $result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <a href="educacao/visualizar-educacao.php?id=<?= $row['id'] ?>" target="_blank">
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
  <div class="btn-add" onclick="addDataEducacao()" data-autor="🤖 RoboEdu:" data-fala="Editar">
    <span id="btn-icon">✏️</span>
  </div>
  <input type="file" id="hiddenUpload" style="display:none" />
<?php endif; ?>
</body>
</html>
