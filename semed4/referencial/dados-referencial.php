<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Referencial</title>
    <link rel="stylesheet" href="../content-dados.css">  
    
</head>

<body>
    </div>
    <div>
        <img src="../semed.png" alt="Logo SEMED" class="logo-semed">
        <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
    </div>
    <br><br>
    <main>
        <h2>Referencial e documentos</h2>
        <p class="subtitulo">
          Documentos oficiais do referencial curricular da educação, normativos e orientações pedagógicas.        </p>

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
            fetch("lista-referencial.php")
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
        fetch("delete-referencial.php", {
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
         <!-- Botão flutuante para voltar ao início -->
    <a href="../index.php#referencial.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>

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
                xhr.open('POST', 'upload-referencial.php', true);

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