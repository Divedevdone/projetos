<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educação Digital - São José dos Pinhais</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">BASE NACIONAL</div>
            <nav>
                <ul class="nav-menu">
                    <?php if (isset($_SESSION["usuario"])): ?>
                        <li><a href="logout.php">Sair (<?= $_SESSION["usuario"] ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Entrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Conteúdo da página aqui... -->
     <div class="sidebar">
        <div class="tab" data-autor="🤖 RoboEdu:" data-fala="Olá! Pronto para começar?" onclick="showContent(1)">
            <div class="text-tab">1. Início</div>
        </div>
        <!--Aba 2-->
        <div class="tab" data-file="nucleo.php" data-autor="🤖 RoboEdu:"
            data-fala="Organogramas, diretrizes e protocolos" onclick="showContent(2)">
            <div class="text-tab">2. Núcleo de Educação Digital</div>
            <!--Conteúdo externo-->
            <div id="content2" class="section hidden"></div>
        </div>
        <!--Aba 3-->
        <div class="tab" data-file="documentos.php" data-autor="🤖 RoboEdu:" data-fala="Orientações pedagógicas"
            onclick="showContent(3)">
            <div class="text-tab">3. Referencial e documentos</div>
            <!--Conteúdo externo-->
            <div id="content3" class="section hidden"></div>
        </div>
        <!--Aba 4-->
        <div class="tab" data-file="midiatica.php" data-autor="🤖 RoboEdu:" data-fala="Materiais digitais"
            onclick="showContent(4)">
            <div class="text-tab">4. Educação digital e midiática</div>
            <!--Conteúdo externo-->
            <div id="content4" class="section hidden"></div>
        </div>
        <!--Aba 5-->
        <div class="tab" data-file="rede.php" data-autor="🤖 RoboEdu:" data-fala="Interligação digital"
            onclick="showContent(5)">
            <div class="text-tab">5. Projetos da rede</div>
            <!--Conteúdo externo-->
            <div id="content5" class="section hidden"></div>
        </div>
        <!--Aba 6-->
        <div class="tab" data-file="recursos.php" data-autor="🤖 RoboEdu:" data-fala="Materiais pedagógicos"
            onclick="showContent(6)">
            <div class="text-tab">6. Recursos educacionais</div>
            <!--Conteúdo externo-->
            <div id="content6" class="section hidden"></div>
        </div>
        <!--Aba 7-->
        <div class="tab" data-file="formacao.php" data-autor="🤖 RoboEdu:" data-fala="Certificações"
            onclick="showContent(7)">
            <div class="text-tab">7. Cursos de formação</div>
            <!--Conteúdo externo-->
            <div id="content7" class="section hidden"></div>
        </div>
    </div>

    <!-- Corpo da página -->
    <div class="content">
        <div id="content1" class="section" onclick="openSection('inicio')">
            <div>
                <img src="eduDigital.png" alt="Logo eduDigital" class="logo-eduDigital">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>

            <h1> </h1>
            <p class="subtitle">Um novo olhar na forma de aprender</p>

            <div>
                <img src="roboEdu.png" alt="Logo roboEdu" class="logo-robo">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>

            <div>
                <img src="semed.png" alt="Logo SEMED" class="logo-semed">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>

            <div class="footer-content">
                <p>SEMED | Secretaria municipal de educação</p>
            </div>
        </div>
    </div>
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
</body>
</html>
