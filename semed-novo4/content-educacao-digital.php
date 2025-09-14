<?php
session_start();
include 'conexao.php'; // conexão com db-semed

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
    <link rel="stylesheet" href="styles.css">
    <style>
/* Reset básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Corpo da página */
body, html {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    color: #333;
    margin: 0;
    padding: 0;   /* <- remove o espaço lateral */
    line-height: 1.6;
    text-align: left;
}

/* Título principal */
.title {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 10px;
    text-align: center;
}
.sub {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 10px;
    text-align: center;
}

/* Texto introdutório */
body > p {
    font-size: 1rem;
    max-width: 100%;
    margin-bottom: 40px;
    text-align: left;
}

/* Destaque de recursos */
.feature-highlight {
    margin: 0;
    padding: 2px;
    width: 80%;
    max-width: none;
    border-left: 5px solid #3498db;
}

/* Ícone de pasta */
.icons {
    font-size: 2rem;
    margin-bottom: 10px;
    text-align: left;
}

/* Lista de recursos */
.recursos-lista {
    margin-left: 0;
    padding-left: 0;
    text-align: left;
}

.recursos-lista ul {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start; /* garante alinhamento à esquerda */
    gap: 10px;
    list-style: none;
    padding-left: 0;
    margin-left: 0;
}

.recursos-lista li {
    background-color: #ecf0f1;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.recursos-lista li:hover {
    background-color: #d0e6f7;
}

.recursos-lista a {
    text-decoration: none;
    color: #2c3e50;
    font-weight: 500;
}

/* Rodapé */
.footer-content {
    text-align: left;
    font-size: 0.9rem;
    color: #777;
    margin-top: 35px;
}

/* Força alinhamento à esquerda para qualquer elemento interno */
div, section, article, header, footer, p, span {
    text-align: left;
}


</style>
    
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
                            <a href="visualizar-educacao.php?id=<?= $row['id'] ?>" target="_blank">
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
