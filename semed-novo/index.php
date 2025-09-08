<?php
// index.php
?><!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Base Nacional - Exemplo de Abas Verticais</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header">
    <div class="nav-container">
      <div style="font-weight:700">BASE NACIONAL</div>
    </div>
  </header>  
  <!-- Sidebar já começa visível -->
  <nav class="sidebar show" aria-label="Menu lateral">
    <div class="tab" data-target="inicio" data-hash="inicio"><span class="text-tab">1. Início</span></div>
    <div class="tab" data-target="estrutura" data-hash="estrutura"><span class="text-tab">2. Núcleo de Educação Digital</span></div>
    <div class="tab" data-target="referencial" data-hash="referencial"><span class="text-tab">3. Referencial e documentos</span></div>
    <div class="tab" data-target="educacao-digital" data-hash="educacao-digital"><span class="text-tab">4. Educação digital e midiática</span></div>
    <div class="tab" data-target="projetos" data-hash="projetos"><span class="text-tab">5. Projetos da rede</span></div>
    <div class="tab" data-target="recursos" data-hash="recursos"><span class="text-tab">6. Recursos educacionais</span></div>
    <div class="tab" data-target="cursos" data-hash="cursos"><span class="text-tab">7. Cursos de formação</span></div>
  </nav>

  <!-- Área principal de conteúdo -->
  <main id="content-area" class="content" role="main" aria-live="polite">
    <h1>Bem-vindo</h1>
    <img src="eduDigital.png" alt="Educação Digital" class="logo-eduDigital">
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
  </main>

<script>
(function () {
  const tabs = Array.from(document.querySelectorAll('.tab'));
  const contentArea = document.getElementById('content-area');
  const sidebar = document.querySelector('.sidebar');
  const validHashes = tabs.map(t => t.dataset.hash);

  function activateTab(tabEl, pushHash = true) {
  if (!tabEl) return;

  const isAlreadyActive = tabEl.classList.contains('active');
  const isInicio = tabEl.dataset.hash === 'inicio';

  if (isAlreadyActive && !isInicio) {
    // ✅ Se clicou na aba já ativa, volta para o início
    tabs.forEach(t => t.classList.remove('active'));
    sidebar.classList.remove('hide');
    sidebar.classList.add('show');
    contentArea.innerHTML = `
      <h1>Bem-vindo</h1>
      <p>Selecione uma aba ao lado para visualizar o conteúdo.</p>
    `;
    if (pushHash) {
      window.location.href = 'index.php';
    }
    return;
  }

  // ✅ Define a aba ativa normalmente
  tabs.forEach(t => t.classList.remove('active'));
  tabEl.classList.add('active');

  if (!isInicio) {
    sidebar.classList.remove('show');
    sidebar.classList.add('hide');
  } else {
    sidebar.classList.remove('hide');
    sidebar.classList.add('show');
  }

  setTimeout(() => {
    const target = tabEl.dataset.target;
    fetch('ajax_handler.php?target=' + encodeURIComponent(target))
      .then(res => res.text())
      .then(html => {
        contentArea.style.opacity = 0;
        setTimeout(() => {
          contentArea.innerHTML = html;
          contentArea.style.opacity = 1;
        }, 2000);
        
      });
      

    if (pushHash) {
      const newHash = tabEl.dataset.hash;
      if (window.location.hash !== '#' + newHash) {
        history.pushState(null, '', '#' + newHash);
      }
    }
  }, 1);
}




  // clique e teclado
  tabs.forEach(tab => {
    tab.addEventListener('click', () => activateTab(tab));
    tab.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        activateTab(tab);
      }
    });
  });

  // back/forward
  window.addEventListener('hashchange', () => {
    setActiveByHash(window.location.hash);
  });

  // ao carregar
  document.addEventListener('DOMContentLoaded', () => {
    setActiveByHash(window.location.hash || '#inicio');
  });  
})();
</script>
</body>
</html>
