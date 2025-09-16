// Variáveis globais
let currentIndex = null;
let currentSection = 'index'; // Seção atual (padrão: index)
const transitionTime = 1000;


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
            falaPopup.style.left = centerX - popupWidth + 300 + 'px';
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

        // Mostra mensagem de ativação (CSS CORRIGIDO)
        showCustomAlert(`
            <h2>🤖 RoboEdu ativado!</h2>
            <p>Agora você receberá dicas de navegação ao passar o mouse sobre os elementos.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
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
            <h2>🔇 RoboEdu Desativado</h2>
            <p>Clique no botão 🔊 para me ativar!</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
            </div>
        `);
        return;
    }

    // Usa a seção atual para determinar a mensagem
    const mensagem = messages[currentSection];

    if (mensagem) {
        showCustomAlert(mensagem);
    } else {
        showCustomAlert(`
            <h2>🤖 RoboEdu:</h2>
            <p>Não encontrei uma mensagem para esta seção.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    }
}

// Mensagens do RoboEdu para cada seção (CSS CORRIGIDO EM TODAS)
const messages = {
    'index': `
        <h2>👋 Olá, eu sou o 🤖 RoboEdu!</h2>
        <p>Sou seu assistente virtual! Clique nas abas coloridas para navegar entre as seções ou clique novamente para retornar!</p>
        <p>Clique no botão 🔊 para me ativar ou 🔇 para me desativar.</p>
        <p>Ao clicar em editar (✏️) você adiciona ou remove dados.</p>
        <div style="text-align: center; margin-top: 1rem; top: 20px">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
            <p style="margin-top: 1rem;">📍 <strong>Navegue logado para alterar cada conteúdo!</strong></p>
        </div>
    `,
    'estrutura': `
        <h2>🤖 RoboEdu - 🏢 Núcleo de Educação Digital:</h2>
        <p>Aqui está o Núcleo de Educação Digital! Você encontrará documentos e informações sobre sua estrutura e funcionamento no município.</p>
        <p>Entre com login e senha para adicionar ou remover seus conteúdos.</p>
        <p><strong>💡 Dica:</strong> Explore os organogramas, diretrizes e protocolos disponíveis nesta seção.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `,
    'referencial': `
        <h2>🤖 RoboEdu - 📚 Referencial e Documentos:</h2>
        <p>Veja os documentos curriculares e orientações pedagógicas que fundamentam a educação municipal.</p>
        <p><strong>💡 Dica:</strong> Acesse currículos, BNCC e diretrizes municipais para enriquecer sua prática pedagógica.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `,
    'educacao-digital': `
        <h2>🤖 RoboEdu - 💻 Educação Digital e Midiática:</h2>
        <p>Descubra recursos de educação digital e midiática para transformar sua sala de aula.</p>
        <p><strong>💡 Dica:</strong> Explore tutoriais, ferramentas e metodologias voltadas para literacia digital.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `,
    'rede': `
        <h2>🤖 RoboEdu -🌐 Projetos da Rede:</h2>
        <p>Conheça os projetos inovadores desenvolvidos pela rede municipal de ensino.</p>
        <p><strong>💡 Dica:</strong> Veja cases, relatórios e boas práticas que inspiram transformação digital na educação.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `,
    'recursos': `
        <h2>🤖 RoboEdu - 🎯 Recursos Educacionais:</h2>
        <p>Explore ferramentas e recursos educacionais pensados para apoiar o ensino digital.</p>
        <p>Entre com login e senha para adicionar ou remover seus conteúdos.</p>
        <p><strong>💡 Dica:</strong> Navegue por apps, jogos e plataformas educativas disponíveis nesta seção.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `,
    'cursos': `
        <h2>🤖 RoboEdu - 🎓 Cursos de Formação:</h2>
        <p>Encontre cursos de formação e capacitação para educadores da rede municipal.</p>
        <p>Entre com login e senha para adicionar ou remover seus conteúdos.</p>
        <p><strong>💡 Dica:</strong> Confira cronogramas, inscrições e certificações disponíveis para você.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; font-size: 16px; padding: 20px 40px; border-radius: 8px; cursor: pointer;">Entendi!</button>
        </div>
    `
};

// Função para mostrar alert customizado
function showCustomAlert(htmlContent) {
    const modal = document.getElementById('customAlert');
    const messageBox = document.getElementById('customAlertMessage');

    if (!modal || !messageBox) {
        console.error('Modal ou messageBox não encontrados!');
        // Fallback para alert nativo
        alert(htmlContent.replace(/<[^>]*>/g, ''));
        return;
    }

    messageBox.innerHTML = htmlContent;
    modal.style.display = 'block';
}

// Função para fechar alert customizado
function closeCustomAlert() {
    const modal = document.getElementById('customAlert');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Função para mostrar mensagem do mascote
function showMascotMessage() {
    const modal = document.getElementById('mascotModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Função para fechar modal
function closeModal() {
    const modal = document.getElementById('mascotModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function addDataEstrutura() {
    window.location.href = "estrutura/dados-estrutura.php";
}
function addDataReferencial() {
    window.location.href = "referencial/dados-referencial.php";
}
function addDataEducacao() {
    window.location.href = "educacao/dados-educacao.php";
}
function addDataRede() {
    window.location.href = "rede/dados-rede.php";
}
function addDataRecursos() {
    window.location.href = "recursos/dados-recursos.php";
}
function addDataCursos() {
    window.location.href = "cursos/dados-cursos.php";
}

// ✅ Event Listeners fora de qualquer função
document.addEventListener('DOMContentLoaded', function () {
    carregarEstadoRoboEdu();

    document.querySelectorAll('.tab').forEach((tab, index) => {
        tab.addEventListener('click', function () {
            const target = this.getAttribute('data-target') || this.getAttribute('data-hash');
            if (target) {
                changeSection(target, this);
            }
        });
    });

    window.onclick = function (event) {
        const modal = document.getElementById('mascotModal');
        const customAlert = document.getElementById('customAlert');

        if (event.target == modal && modal) {
            modal.style.display = 'none';
        }

        if (event.target == customAlert && customAlert) {
            customAlert.style.display = 'none';
        }
    };
    function changeSection(sectionId, tabElement) {
        currentSection = sectionId; // 🔥 Atualiza a seção atual

        // Aqui você pode adicionar lógica para mostrar/esconder seções
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = section.id === sectionId ? 'block' : 'none';
        });

        // Atualiza estilo da aba ativa, se necessário
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        tabElement.classList.add('active');
    }


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