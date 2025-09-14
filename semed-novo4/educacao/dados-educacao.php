<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Educação digital</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        /* Reset e corpo */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f5f7fb;
            color: #111;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            /* distribui cabeçalho, conteúdo e rodapé */
        }

        /* Cabeçalho */
        header {
            width: 100%;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-semed {
            position: absolute;
            left: 50px;
            top: 3px;
            width: 180px;
            height: auto;
        }

        /* Link de voltar */
        .link-voltar {
            position: absolute;
            left: 70px;
            top: 195px;
            font-size: 0.9rem;
            text-decoration: none;
            color: #1976d2;
        }

        /* Conteúdo principal */
        main {
            width: 90%;
            max-width: 900px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            /* espaço entre blocos */
            text-align: center;
        }

        h2 {
            margin-top: 0;
            text-align: center;
        }

        /* Área de resultado / progresso */
        #resultado {
            width: 120%;
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            word-break: break-word;
            margin-right: -50px;
        }

        #progressoContainer {
            display: none;
            margin-top: 12px;
        }

        #barraProgresso {
            width: 100%;
            height: 12px;
            background: #e6e9ef;
            border-radius: 6px;
            overflow: hidden;
        }

        #barraProgressoInner {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #4caf50, #2ea44f);
            border-radius: 6px;
            transition: width 0.2s ease;
        }

        /* Lista de arquivos */
        ul#listaArquivos {
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 600px;
        }

        ul#listaArquivos li {
            background: #fff;
            margin: 6px 0;
            padding: 8px 12px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        ul#listaArquivos li a {
            text-decoration: none;
            color: #111;
            word-break: break-word;
        }

        .delete-btn {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 16px;
        }

        /* Botão flutuante (FAB) */
        .fab {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(180deg, #1976d2, #115293);
            box-shadow: 0 8px 20px rgba(3, 64, 120, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            font-size: 28px;
            border: none;
            z-index: 9999;
            transition: transform .12s ease, box-shadow .12s ease;
        }

        .fab:active {
            transform: scale(.96);
        }

        .fab:hover {
            box-shadow: 0 12px 30px rgba(3, 64, 120, 0.28);
        }

        .fabLabel {
            position: fixed;
            right: 100px;
            bottom: 38px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            display: none;
            z-index: 9998;
        }

        p {
            margin: 0 0 12px 0;
        }

        .subtitulo {
            font-size: 25px;
            color: #1b9284;
            text-align: center;
            display: block;
            width: 100%;
            margin: 12px 0 20px 0;
            padding: 0;
        }

      /* Rodapé */
.footer-content {
    position: fixed;
    top: 560px;
    right: 320px;
    text-align: center;
    font-size: 14px;
    width: 35%;
    transition: all 0.4s ease;
    font-size: 20px;
}

        /* Responsividade */
        @media (max-width: 600px) {
            .logo-semed {
                width: 100px;
            }

            ul#listaArquivos {
                max-width: 100%;
            }

            .fab {
                width: 56px;
                height: 56px;
                font-size: 24px;
            }

            .fabLabel {
                right: 80px;
                bottom: 28px;
                font-size: 12px;
            }

            .subtitulo {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <a href="../index.php" class="link-voltar">⬅ Voltar para início</a>
    </div>
    <div>
        <img src="../semed.png" alt="Logo SEMED" class="logo-semed">
        <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
    </div>
    <br><br>
    <main>
        <h2>Educação digital</h2>
        <p class="subtitulo">
          Materiais sobre literatura digital, uso pedagógico de tecnologias e educação midiática.</p>

        <div id="resultado">
            <strong>Status:</strong>
            <div id="statusText">Nenhum sendo realizado.</div>
            <div id="progressoContainer">
                <div id="barraProgresso">
                    <div id="barraProgressoInner"></div>
                </div>
                <div id="percent">0%</div>
            </div>
        </div>

        <h3>Arquivos enviados</h3>
        <ul id="listaArquivos"></ul>
    </main>

    <script>
        function carregarArquivos() {
            fetch("lista-educacao.php")
                .then(r => r.json())
                .then(arquivos => {
                    let lista = document.getElementById("listaArquivos");
                    lista.innerHTML = "";
                    arquivos.forEach(a => {
                        let li = document.createElement("li");

                        // link do arquivo
                        li.innerHTML = `<a href="${a.url}" target="_blank">${a.nome}</a>`;

                        // botão lixeira
                        let btn = document.createElement("button");
                        btn.textContent = "🗑️";
                        btn.style.marginLeft = "10px";
                        btn.style.cursor = "pointer";
                       btn.onclick = function () {
    if (confirm("Tem certeza que deseja excluir este arquivo?")) {
        fetch("delete-educacao.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + encodeURIComponent(a.id)
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === "ok") {
                alert("Arquivo excluído com sucesso!");
                carregarArquivos();
            } else {
                alert("Erro: " + res.msg);
            }
        })
        .catch(err => alert("Erro na conexão: " + err));
    }
};

                        li.appendChild(btn);
                        lista.appendChild(li);
                    });
                })
                .catch(err => console.error("Erro ao carregar lista:", err));
        }

        // chama logo ao abrir a página
        carregarArquivos();
    </script>

    <!-- input file oculto (é acionado pelo botão flutuante) -->
    <input type="file" id="arquivoInput" name="arquivo" style="display:none" />

    <!-- botão flutuante criado por JS (mas deixamos a estrutura para acessibilidade) -->
    <button id="fab" class="fab" aria-label="Enviar arquivo">+</button>
    <div id="fabLabel" class="fabLabel">Enviar arquivo</div>

    <script>
        (function () {
            const fab = document.getElementById('fab');
            const fabLabel = document.getElementById('fabLabel');
            const fileInput = document.getElementById('arquivoInput');
            const statusText = document.getElementById('statusText');
            const progressoContainer = document.getElementById('progressoContainer');
            const barraInner = document.getElementById('barraProgressoInner');
            const percentText = document.getElementById('percent');

            // Mostra label ao passar o mouse (útil em desktop)
            fab.addEventListener('mouseenter', () => fabLabel.style.display = 'block');
            fab.addEventListener('mouseleave', () => fabLabel.style.display = 'none');

            // Quando clicar no FAB, abre o seletor de arquivos
            fab.addEventListener('click', () => fileInput.click());

            // Ao escolher arquivo, realiza o upload via XHR para ter progresso
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                // Atualiza UI
                statusText.innerHTML = `Pronto para enviar: <strong>${escapeHtml(file.name)}</strong> (${formatBytes(file.size)})`;
                progressoContainer.style.display = 'block';
                barraInner.style.width = '0%';
                percentText.innerText = '0%';

                // Prepara FormData
                const fd = new FormData();
                fd.append('arquivo', file);

                // Usa XMLHttpRequest para acompanhar progresso do upload
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'upload-educacao.php', true);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const pct = Math.round((e.loaded / e.total) * 100);
                        barraInner.style.width = pct + '%';
                        percentText.innerText = pct + '%';
                    }
                };

                xhr.onload = function () {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (xhr.status >= 200 && xhr.status < 300 && res.status === 'ok') {
                            statusText.innerHTML = `<span style="color:green">✔ Enviado com sucesso:</span><br>
                Nome: <strong>${escapeHtml(res.nome)}</strong><br>
                Tipo: <strong>${escapeHtml(res.tipo)}</strong>`;

                            // RECARREGA A LISTA DE ARQUIVOS
                            carregarArquivos();

                        } else {
                            statusText.innerHTML = `<span style="color:red">✖ Erro no upload.</span><br>${escapeHtml((res && res.msg) || xhr.responseText)}`;
                        }
                    } catch (err) {
                        statusText.innerHTML = `<span style="color:red">✖ Resposta inválida do servidor.</span><br>${escapeHtml(xhr.responseText)}`;
                    }
                    fileInput.value = '';
                };
                //location.reload();

                xhr.onerror = function () {
                    statusText.innerHTML = `<span style="color:red">✖ Falha na conexão.</span>`;
                    fileInput.value = '';
                };

                // Envia
                xhr.send(fd);

                // Mostra mensagem inicial
                statusText.innerHTML = `Enviando <strong>${escapeHtml(file.name)}</strong>...`;
            });

            // util: formatar bytes
            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            // util: escapar HTML para exibir nomes seguros
            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

        })();
    </script>
</body>

</html>