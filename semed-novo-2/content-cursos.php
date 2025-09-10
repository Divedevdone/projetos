<?php
session_start();
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
    <div id="content7" class="section hidden" onclick="openSection('cursos')">
        <h1>Cursos de formação</h1>
        <p class="subtitle2">Programas de capacitação e formação continuada para educadores da rede municipal.</p>
         <br><br><br><br>
        <div class="feature-highlight">
            <div class="icons">🏆</div>
            <strong> Recursos:</strong>
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