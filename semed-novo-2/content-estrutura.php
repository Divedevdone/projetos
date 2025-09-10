<?php
session_start();
include 'conexao.php'; // conexão com db-semed

// Pegar os arquivos do banco
$sql = "SELECT id, nome FROM recursos ORDER BY data_upload DESC";
$result = $conn->query($sql);
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
    <h1 class="titulo-iframe">Núcleo de Educação Digital</h1>
    <p class="subtitle2">Documentos e informações sobre a estrutura e funcionamento do núcleo de Educação digital do município.</p>
  <br><br><br><br>
    <div class="feature-highlight">
        <div class="icons">🎯</div>
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
    <!-- Robozinho mascote -->
    <div class="robot-mascot" onclick="showMascotMessageBySection()" title="Clique para receber dicas de navegação">
        🤖
    </div>
    <!-- Botão para ligar/desligar RoboEdu -->
    <div class="robo-toggle" onclick="toggleRoboEdu()" title="Ligar/Desligar RoboEdu">
        <span id="toggle-icon">🔊</span>
    </div>

    <!-- Botão para adicionar dados (só aparece se logado) -->
<?php if (isset($_SESSION["usuario"])): ?>
<div class="btn-add" class="gif-popup"
     onclick="addData()" 
     data-autor="🤖 RoboEdu:" 
     data-fala="Editar">
    <span id="btn-icon">✏️</span>
</div>
<?php endif; ?>


    <!-- Modal -->
    <div id="mascotModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
    <!--O robô fica aqui-->
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeModal()"
                    style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        </div>
    </div>

    <div id="customAlert" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCustomAlert()">&times;</span>
            <div id="customAlertMessage"></div>
        </div>
    </div>
    <script src="script.js"></script>
    <script src="scripts-robo.js"></script>

</body>

</html>