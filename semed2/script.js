// Variáveis globais
let currentIndex = null;
const transitionTime = 1000;

// Função principal para mostrar conteúdo das abas
function showContent(index) {
    const sections = document.querySelectorAll('.section');
    const tabs = document.querySelectorAll('.tab');

    if (currentIndex === index) {
        // Restaurar estado original
        tabs.forEach((tab, i) => {
            tab.classList.remove('tab-hidden', 'tab-active');
            tab.style.left = (i * 40) + 'px';
        });

        sections.forEach(sec => sec.classList.add('hidden'));
        document.getElementById('content1').classList.remove('hidden');
        currentIndex = null;
    } else {
        sections.forEach(sec => sec.classList.add('hidden'));

        tabs.forEach((tab, i) => {
            tab.classList.remove('tab-hidden', 'tab-active');
            if (i === index - 1) {
                tab.classList.add('tab-active');
            } else {
                tab.classList.add('tab-hidden');
            }
        });

        setTimeout(() => {
            const contentDiv = document.getElementById('content' + index);
            const tab = tabs[index - 1];
            const file = tab.getAttribute('data-file');

            if (file) {
                // Limpa conteúdo anterior
                contentDiv.innerHTML = '';

                // Cria iframe
                const iframe = document.createElement('iframe');
                iframe.src = file;
                iframe.style.width = '800px';
                iframe.style.height = '2000px'; // ajuste a altura conforme necessário
                iframe.style.border = '20px';

                contentDiv.appendChild(iframe);
                contentDiv.classList.remove('hidden');
            } else {
                // Exibe conteúdo já existente
                contentDiv.classList.remove('hidden');
            }
        }, transitionTime);

        currentIndex = index;
    }
}

// pop up de falas do robô
document.querySelectorAll('.tab[data-fala], .btn-add[data-fala]').forEach(tab => {
    tab.addEventListener('mouseenter', (e) => {
        console.log('hover no botão');


        tab.classList.add('tab:hover');
        if (!roboEduAtivo) return;

        const falaPopup = document.createElement('div');
        falaPopup.classList.add('gif-popup');
        falaPopup.classList.add('popup-edicao');

        const autor = tab.dataset.autor || '🤖 RoboEdu:';
        const mensagem = tab.dataset.fala || '';

        falaPopup.innerHTML = `<strong>${autor}</strong> ${mensagem}`;
        document.body.appendChild(falaPopup);

        const rect = tab.getBoundingClientRect();
        const popupWidth = falaPopup.offsetWidth;
        const popupHeight = falaPopup.offsetHeight;

        if (tab.classList.contains('btn-add')) {
            falaPopup.style.left = rect.left + rect.width / 2 - popupWidth / 2 + 'px';
            falaPopup.style.top = rect.bottom - 100 + 'px';


        } else {
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            falaPopup.style.left = centerX - popupWidth / 2 + 'px';
            falaPopup.style.top = centerY - popupHeight - 250 + 'px';
        }

        falaPopup.style.display = 'block';

        // Salva a referência no próprio elemento
        tab._falaPopup = falaPopup;
    });

    tab.addEventListener('mouseleave', () => {
        if (tab._falaPopup) {
            tab._falaPopup.remove();
            tab._falaPopup = null;
        }
    });
});


let roboEduAtivo = true; // Estado do RoboEdu (ligado/desligado)




// FUNÇÃO PARA LIGAR/DESLIGAR O ROBOEDU
function toggleRoboEdu() {
    roboEduAtivo = !roboEduAtivo;

    const robotMascot = document.querySelector('.robot-mascot');
    const toggleIcon = document.getElementById('toggle-icon');
    const toggleButton = document.querySelector('.robo-toggle');

    if (roboEduAtivo) {
        // RoboEdu ligado
        robotMascot.classList.remove('disabled');
        toggleIcon.textContent = '🔊';
        toggleButton.title = 'Desligar RoboEdu';

        // Mostra mensagem de ativação
        showCustomAlert(`
            <h2>🤖 RoboEdu ativado!</h2>
            <p>Agora você receberá dicas de navegação ao passar o mouse sobre os elementos.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    } else {
        // RoboEdu desligado
        robotMascot.classList.add('disabled');
        toggleIcon.textContent = '🔇';
        toggleButton.title = 'Ligar RoboEdu';

        // Esconde todos os popups ativos
        document.querySelectorAll('.gif-popup').forEach(popup => {
            popup.style.display = 'none';
        });


    }

    // Salva o estado no localStorage (se disponível)
    try {
        localStorage.setItem('roboEduAtivo', roboEduAtivo);
    } catch (e) {
        // Ignora se localStorage não estiver disponível
    }
}

// FUNÇÃO PARA CARREGAR O ESTADO SALVO DO ROBOEDU
function carregarEstadoRoboEdu() {
    try {
        const estadoSalvo = localStorage.getItem('roboEduAtivo');
        if (estadoSalvo !== null) {
            roboEduAtivo = estadoSalvo === 'true';

            const robotMascot = document.querySelector('.robot-mascot');
            const toggleIcon = document.getElementById('toggle-icon');
            const toggleButton = document.querySelector('.robo-toggle');

            if (!roboEduAtivo) {
                robotMascot.classList.add('disabled');
                toggleIcon.textContent = '🔇';
                toggleButton.title = 'Ligar RoboEdu';
            }
        }
    } catch (e) {
        // Ignora se localStorage não estiver disponível
    }
}
// Função para mostrar mensagens do mascote por seção
function showMascotMessageBySection() {
    if (!roboEduAtivo) {
        showCustomAlert(`
         <h2>🔇 Desativado </h2>
         <div style="text-align: center; margin-top: 1rem;">
             <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
         </div>
     `);
        return;
    }

    const messages = {
        'content1': `
            <h2>👋 Olá, eu sou o RoboEdu!</h2>
            <p>Sou seu assistente virtual! Clique nas abas coloridas para navegar entre as seções ou clique novamente para retornar!</p>
            <p>Clique no botão 🔊 para me ativar ou 🔇desativar.</p>
            <p>Ao clicar em editar (✏️) você adiciona ou remove dados.</p>
            <strong>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
                <p style="margin-top: 1rem;">📍 Navegue logado para alterar cada conteúdo!</p>
            </div>
        `,
        'content2': `
            <h2>👋 RoboEdu:</h2>
            <p>Aqui está o Núcleo de Educação Digital! Você encontrará documentos e informações sobre sua estrutura e funcionamento no município.</p>
            <p>Entre com login e senha para adicionar ou remover seus conteúdos</p>
            <p><strong>💡 Dica:</strong> Explore os organogramas, diretrizes e protocolos disponíveis nesta seção.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content3': `
            <h2>👋 RoboEdu:</h2>
            <p>Veja os documentos curriculares e orientações pedagógicas que fundamentam a educação municipal.</p>
            <p><strong>💡 Dica:</strong> Acesse currículos, BNCC e diretrizes municipais para enriquecer sua prática pedagógica.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content4': `
            <h2>👋 RoboEdu:</h2>
            <p>Descubra recursos de educação digital e midiático para transformar sua sala de aula.</p>
            <p><strong>💡 Dica:</strong> Explore tutoriais, ferramentas e metodologias voltadas para literacia digital.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content5': `
            <h2>👋 RoboEdu:</h2>
            <p>Conheça os projetos inovadores desenvolvidos pela rede municipal de ensino.</p>
            <p><strong>💡 Dica:</strong> Veja cases, relatórios e boas práticas que inspiram transformação digital na educação.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content6': `
            <h2>👋 RoboEdu:</h2>
            <p>Explore ferramentas e recursos educacionais pensados para apoiar o ensino digital.</p>
            <p>Entre com login e senha para adicionar ou remover seus conteúdos</p>
            <p><strong>💡 Dica:</strong> Navegue por apps, jogos e plataformas educativas disponíveis nesta seção.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content7': `
            <h2>👋 RoboEdu:</h2>
            <p>Encontre cursos de formação e capacitação para educadores da rede municipal.</p>
            <p>Entre com login e senha para adicionar ou remover seus conteúdos</p>
            <p><strong>💡 Dica:</strong> Confira cronogramas, inscrições e certificações disponíveis para você.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `
    };

    const activeSection = Array.from(document.querySelectorAll('.section'))
        .find(section => !section.classList.contains('hidden'));

    const sectionId = activeSection?.id || 'content1';
    const message = messages[sectionId];

    showCustomAlert(message);
}

// Função para mostrar alert customizado
function showCustomAlert(htmlContent) {
    const modal = document.getElementById('customAlert');
    const messageBox = document.getElementById('customAlertMessage');
    messageBox.innerHTML = htmlContent;
    modal.style.display = 'block';
}

// Função para fechar alert customizado
function closeCustomAlert() {
    document.getElementById('customAlert').style.display = 'none';
}

// Função para mostrar mensagem do mascote
function showMascotMessage() {
    document.getElementById('mascotModal').style.display = 'block';
}

// Função para fechar modal
function closeModal() {
    document.getElementById('mascotModal').style.display = 'none';
}

// Função openSection (mencionada no HTML mas não implementada)
function openSection(sectionName) {
    console.log('Abrindo seção:', sectionName);
    // Implementar lógica específica se necessário
}

// Função para adicionar/editar dados (usuários logados)
// Função principal de edição (abre popup com opções + upload + exclusão)
function addData() {
    // Verifica qual seção está ativa
    const activeSection = Array.from(document.querySelectorAll('.section'))
        .find(section => !section.classList.contains('hidden'));

    const sectionId = activeSection?.id || 'content1';

    if (sectionId === 'content1') {
        showContent(2);
        setTimeout(() => {
            addData();
        }, transitionTime + 100);
        return;
    }

    // Mapear seções para nomes amigáveis
    const sectionNames = {
        'content2': 'Núcleo de Educação Digital',
        'content3': 'Referencial e Documentos',
        'content4': 'Educação Digital e Midiática',
        'content5': 'Projetos da Rede',
        'content6': 'Recursos Educacionais',
        'content7': 'Cursos de Formação'
    };

    const currentSectionName = sectionNames[sectionId] || 'Seção Atual';

    // HTML do popup (com upload e listagem)
    showCustomAlert(`
        <h2>🤖 RoboEdu:</h2>
        <p><strong>Seção atual:</strong> ${currentSectionName}</p>

        <form id="uploadForm" enctype="multipart/form-data" style="margin-top: 10px;">
            <input type="file" name="arquivo" required>
            <input type="hidden" name="section" value="${sectionId}">
            <button type="submit" 
                style="background: #2E7D32; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; margin-top: 8px;">
                📁 Upload de Arquivo
            </button>
        </form>

        <div id="fileList" style="margin-top: 15px; max-height: 150px; overflow-y: auto; border-top: 1px solid #ccc; padding-top: 10px;">
            <em>Carregando arquivos...</em>
        </div>

        <div style="text-align: center; margin-top: 15px;">
            <button onclick="closeCustomAlert()" 
                style="background: #666; color: white; border: none; padding: 10px 10px; border-radius: 8px; cursor: pointer;">
                Fechar
            </button>
        </div>
    `);

    // Assim que abrir o popup, carregar arquivos existentes
    loadFiles(sectionId);

    // Bind do formulário de upload
    document.getElementById("uploadForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("upload.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "ok") {
                    loadFiles(sectionId); // recarrega lista
                } else {
                    alert("Erro ao enviar o arquivo.");
                }
            });
    });
}

// Carrega arquivos da seção
function loadFiles(sectionId) {
    fetch(`listFiles.php?section=${sectionId}`)
        .then(res => res.json())
        .then(files => {
            let fileList = document.getElementById("fileList");
            if (files.length === 0) {
                fileList.innerHTML = "<em>Nenhum arquivo enviado.</em>";
            } else {
                fileList.innerHTML = files.map(file => `
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <a href="${file.url}" target="_blank">📂 ${file.name}</a>
                        <button onclick="deleteFile('${file.name}', '${sectionId}')" 
                            style="background:#c62828; color:white; border:none; padding:4px 8px; border-radius
...: 6px; cursor:pointer;">
❌
</button>
</div>
`).join("");
            }
        });
}
// Deletar arquivo
function deleteFile(fileName, sectionId) {
    if (!confirm("Tem certeza que deseja excluir este arquivo?")) return;

    fetch("delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `file=${encodeURIComponent(fileName)}&section=${encodeURIComponent(sectionId)}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") {
                loadFiles(sectionId);
            } else {
                alert("Erro ao excluir o arquivo.");
            }
        });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function () {
    carregarEstadoRoboEdu();
    // Fechar modal ao clicar fora
    window.onclick = function (event) {
        const modal = document.getElementById('mascotModal');
        const customAlert = document.getElementById('customAlert');

        if (event.target == modal) {
            modal.style.display = 'none';
        }

        if (event.target == customAlert) {
            customAlert.style.display = 'none';
        }
    };

    // Rolagem suave para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});