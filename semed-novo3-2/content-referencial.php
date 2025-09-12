<?php
session_start();
include 'conexao.php'; // conexão com db-semed

// Pegar os arquivos do banco
$sql = "SELECT id, nome FROM referencial ORDER BY data_upload DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referencial e documentos</title>
    <link rel="stylesheet" href="content.css">
</head>

<body>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("listar.php")
        .then(res => res.json())
        .then(arquivos => {
            let recursoDiv = document.querySelector(".feature-highlight p");
            if (arquivos.length > 0) {
                recursoDiv.innerHTML = arquivos.map(arq => 
                    `<a href="${arq.url}" target="_blank">📂 ${arq.nome}</a>`
                ).join("<br>");
            } else {
                recursoDiv.innerHTML = "<em>Nenhum recurso disponível</em>";
            }
        })
        .catch(err => {
            console.error("Erro ao carregar arquivos:", err);
        });
});
</script>

    <h1 class="titulo-iframe">Referencial e documentos</h1>
    <p class="subtitle2">Documentos oficiais do referencial curricular da educação, normativos e orientações pedagógicas.</p>
<br><br><br><br>
    <div class="feature-highlight">
        <div class="icons">📚</div>
        <strong> Recursos:&nbsp;</strong>
        <p>
            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <a href="recuperar.php?id=<?= $row['id'] ?>" target="_blank">
                            <?= htmlspecialchars($row['nome']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                Nenhum recurso disponível.
            <?php endif; ?>
        </p>
    </div>
    
    <div class="footer-content">
        <p>SEMED | Secretaria municipal de educação</p>
    </div>

</body>

</html>