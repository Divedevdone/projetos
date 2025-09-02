// Variáveis globais
let currentIndex = null;
const transitionTime = 1000;
let roboEduAtivo = true; // Estado do RoboEdu (ligado/desligado)

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
                iframe.style.height = '2000px';
                iframe.style.border = 'none';

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

// Pop-up de falas do robô - APENAS para abas e botão "mais"
function initRoboTooltips() {
    // Seleciona APENAS as abas e botões com classe "botao-azul"
    document.querySelectorAll('.tab[data-fala], .botao-azul[data-fala]').forEach(element => {
        element.addEventListener('mouseenter', () => {
            // Verifica se RoboEdu está ativo
            if (!roboEduAtivo) return;

            const falaPopup = document.createElement('div');
            falaPopup.classList.add('gif-popup');

            const autor = element.dataset.autor || '🤖 RoboEdu:';
            const mensagem = element.dataset.fala || '';

            falaPopup.innerHTML = `<strong>${autor}</strong> ${mensagem}`;
            document.body.appendChild(falaPopup);

            const rect = element.getBoundingClientRect();
            const popupWidth = falaPopup.offsetWidth;
            const popupHeight = falaPopup.offsetHeight;

            // Posicionamento diferente para botão vs aba
            if (element.classList.contains('botao-azul')) {
                // Para botões "mais"
                falaPopup.style.left = rect.left + rect.width / 2 - popupWidth / 2 + 'px';
                falaPopup.style.top = rect.bottom + 10 + 'px';
            } else {
                // Para abas laterais
                falaPopup.style.left = rect.right + 10 + 'px';
                falaPopup.style.top = rect.top + rect.height / 2 - popupHeight / 2 + 'px';
            }

            falaPopup.style.display = 'block';

            // Salva a referência no próprio elemento
            element._falaPopup = falaPopup;
        });

        element.addEventListener('mouseleave', () => {
            if (element._falaPopup) {
                element._falaPopup.remove();
                element._falaPopup = null;
            }
        });
    });
}

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
            <p>Agora você receberá dicas de navegação ao passar o mouse sobre as abas e botões.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    } else {
        // RoboEdu desligado
        robotMascot.classList.add('disabled');
        toggleIcon.textContent = '🔇';
        toggleButton.title = 'Ligar RoboEdu';

        // Remove TODOS os popups ativos
        document.querySelectorAll('.gif-popup').forEach(popup => {
            popup.remove();
        });

        showCustomAlert(`
            <h2>🔇 RoboEdu desativado</h2>
            <p>As dicas de navegação foram desabilitadas.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    }

    // Salva o estado (sem localStorage para compatibilidade)
    try {
        if (typeof (Storage) !== "undefined") {
            localStorage.setItem('roboEduAtivo', roboEduAtivo);
        }
    } catch (e) {
        // Ignora se localStorage não estiver disponível
        console.log('LocalStorage não disponível');
    }
}

// FUNÇÃO PARA CARREGAR O ESTADO SALVO DO ROBOEDU
function carregarEstadoRoboEdu() {
    try {
        if (typeof (Storage) !== "undefined") {
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
        }
    } catch (e) {
        console.log('Erro ao carregar estado do RoboEdu');
    }
}

// Função para mostrar mensagens do mascote por seção
function showMascotMessageBySection() {
    if (!roboEduAtivo) {
        showCustomAlert(`
            <h2>🔇 RoboEdu Desativado</h2>
            <p>O assistente virtual está desativado. Clique no botão 🔊 para ativá-lo.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
        return;
    }

    const messages = {
        'content1': `
            <h2>👋 Olá, eu sou o RoboEdu!</h2>
            <p>Sou seu assistente virtual! Clique nas abas coloridas para explorar todos os recursos educacionais.</p>
            <p><strong>💡 Dica:</strong> Passe o mouse sobre as abas para receber dicas específicas de cada seção!</p>
            <p>Clique no botão 🔊 para me desativar ou 🔇 para reativar.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content2': `
            <h2>👋 RoboEdu:</h2>
            <p>Você está na seção do Núcleo de Educação Digital! Aqui encontrará documentos e informações sobre estrutura e funcionamento.</p>
            <p><strong>💡 Dica:</strong> Explore os organogramas, diretrizes e protocolos disponíveis nesta seção.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content3': `
            <h2>👋 RoboEdu:</h2>
            <p>Seção de Referencial e Documentos! Veja orientações pedagógicas e documentos curriculares.</p>
            <p><strong>💡 Dica:</strong> Acesse currículos, BNCC e diretrizes municipais para enriquecer sua prática pedagógica.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content4': `
            <h2>👋 RoboEdu:</h2>
            <p>Educação Digital e Midiática! Descubra recursos para transformar sua sala de aula.</p>
            <p><strong>💡 Dica:</strong> Explore tutoriais, ferramentas e metodologias voltadas para literacia digital.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content5': `
            <h2>👋 RoboEdu:</h2>
            <p>Projetos da Rede! Conheça os projetos inovadores desenvolvidos pela rede municipal.</p>
            <p><strong>💡 Dica:</strong> Veja cases, relatórios e boas práticas que inspiram transformação digital na educação.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content6': `
            <h2>👋 RoboEdu:</h2>
            <p>Recursos Educacionais! Explore ferramentas e recursos pensados para apoiar o ensino digital.</p>
            <p><strong>💡 Dica:</strong> Navegue por apps, jogos e plataformas educativas disponíveis nesta seção.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `,
        'content7': `
            <h2>👋 RoboEdu:</h2>
            <p>Cursos de Formação! Encontre cursos de capacitação para educadores da rede municipal.</p>
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
            <p>Agora você receberá dicas de navegação ao passar o mouse sobre as abas e botões especiais.</p>
            <p><strong>Nota:</strong> As dicas aparecem apenas nas abas laterais e botões com o símbolo "+".</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    } else {
        // RoboEdu desligado
        robotMascot.classList.add('disabled');
        toggleIcon.textContent = '🔇';
        toggleButton.title = 'Ligar RoboEdu';

        // Remove APENAS os popups das abas e botões especiais
        document.querySelectorAll('.gif-popup').forEach(popup => {
            popup.remove();
        });

        showCustomAlert(`
            <h2>🔇 RoboEdu desativado</h2>
            <p>As dicas de navegação das abas foram desabilitadas.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <button onclick="closeCustomAlert()" style="background: #42519C; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">Entendi!</button>
            </div>
        `);
    }

    // Salva o estado no localStorage (se disponível)
    try {
        if (typeof (Storage) !== "undefined") {
            localStorage.setItem('roboEduAtivo', roboEduAtivo);
        }
    } catch (e) {
        // Ignora se localStorage não estiver disponível
        console.log('LocalStorage não disponível');
    }
}

// FUNÇÃO PARA CARREGAR O ESTADO SALVO DO ROBOEDU
function carregarEstadoRoboEdu() {
    try {
        if (typeof (Storage) !== "undefined") {
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
        }
    } catch (e) {
        console.log('Erro ao carregar estado do RoboEdu');
    }
}

// Inicialização dos tooltips do RoboEdu
function initializeRoboEdu() {
    // Remove event listeners antigos se existirem
    document.querySelectorAll('.tab[data-fala], .botao-azul[data-fala]').forEach(element => {
        // Clona o elemento para remover todos os event listeners
        const newElement = element.cloneNode(true);
        element.parentNode.replaceChild(newElement, element);
    });

    // Adiciona os novos event listeners apenas para abas e botões especiais
    document.querySelectorAll('.tab[data-fala], .botao-azul[data-fala]').forEach(element => {
        element.addEventListener('mouseenter', () => {
            if (!roboEduAtivo) return;

            const falaPopup = document.createElement('div');
            falaPopup.classList.add('gif-popup');

            const autor = element.dataset.autor || '🤖 RoboEdu:';
            const mensagem = element.dataset.fala || '';

            falaPopup.innerHTML = `<strong>${autor}</strong> ${mensagem}`;
            document.body.appendChild(falaPopup);

            const rect = element.getBoundingClientRect();
            const popupWidth = falaPopup.offsetWidth;
            const popupHeight = falaPopup.offsetHeight;

            if (element.classList.contains('botao-azul')) {
                // Posicionamento para botões "mais"
                falaPopup.style.left = rect.left + rect.width / 2 - popupWidth / 2 + 'px';
                falaPopup.style.top = rect.bottom + 10 + 'px';
            } else {
                // Posicionamento para abas laterais
                falaPopup.style.left = rect.right + 10 + 'px';
                falaPopup.style.top = rect.top + rect.height / 2 - popupHeight / 2 + 'px';
            }

            falaPopup.style.display = 'block';
            element._falaPopup = falaPopup;
        });

        element.addEventListener('mouseleave', () => {
            if (element._falaPopup) {
                element._falaPopup.remove();
                element._falaPopup = null;
            }
        });
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function () {
    // Carrega estado salvo do RoboEdu
    carregarEstadoRoboEdu();

    // Inicializa os tooltips do RoboEdu
    initializeRoboEdu();

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

    // Reaplica os event listeners quando necessário
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.type === 'childList') {
                // Reinicializa os tooltips se novos elementos foram adicionados
                initializeRoboEdu();
            }
        });
    });

    // Observa mudanças no DOM
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});