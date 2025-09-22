<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// --- EXCLUSÃO ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $sql = "DELETE FROM estrutura WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redireciona para evitar "refresh duplicado"
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red'>Erro ao excluir: " . $conn->error . "</p>";
    }
}

// --- PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo   = $_POST['titulo'];
    $mensagem = $_POST['mensagem'];

    // Valores padrão (caso não seja enviado nada)
    $conteudoImg = null;
    $tipoImg     = null;

    $conteudoArq = null;
    $nomeArq     = null;
    $tipoArq     = null;

    // Se enviou imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $conteudoImg = file_get_contents($_FILES['imagem']['tmp_name']);
        $tipoImg     = $_FILES['imagem']['type'];
    }

    // Se enviou arquivo
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $conteudoArq = file_get_contents($_FILES['arquivo']['tmp_name']);
        $nomeArq     = $_FILES['arquivo']['name'];
        $tipoArq     = $_FILES['arquivo']['type'];
    }

    $sql = "INSERT INTO estrutura 
                (titulo, mensagem, imagem, tipo_imagem, arquivo, nome_arquivo, tipo_arquivo, criado_em) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssss",
        $titulo,
        $mensagem,
        $conteudoImg,
        $tipoImg,
        $conteudoArq,
        $nomeArq,
        $tipoArq
    );

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ Salvo com sucesso!</div>";
    } else {
        echo "<div class='alert alert-error'>❌ Erro ao salvar: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Buscar
$result = $conn->query("SELECT * FROM estrutura ORDER BY criado_em DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Núcleo de educação digital</title>
    <link rel="stylesheet" href="../content-dados.css">
</head>
<body>
    
   <div class="container">
    <div class="form-container">
        <h1><img src="../semed.png" alt="Logo SEMED" class="logo-semed"></h1>           
        <!-- FORMULÁRIO -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <h1>Núcleo de educação digital</h1>
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do evento..." required>
            </div>

            <div class="form-group">
                <label for="imagem">Imagem</label>
                <div class="file-input">
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <label for="imagem" class="file-input-label">
                        📎 Clique para selecionar uma imagem
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="arquivo">Arquivo</label>
                <div class="file-input">
                    <input type="file" id="arquivo" name="arquivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                    <label for="arquivo" class="file-input-label">
                        📂 Clique para selecionar um arquivo
                    </label>
                    <span id="nome-arquivo" class="nome-arquivo"></span>
                </div>
            </div>

            <script>
                const inputArquivo = document.getElementById('arquivo');
                const nomeArquivo = document.getElementById('nome-arquivo');

                inputArquivo.addEventListener('change', function () {
                    if (inputArquivo.files.length > 0) {
                        nomeArquivo.textContent = `📄 ${inputArquivo.files[0].name}`;
                    } else {
                        nomeArquivo.textContent = '';
                    }
                });
            </script>
            <div class="form-group">
                <label for="mensagem">Descrição</label>
                <textarea id="mensagem" name="mensagem" placeholder="Descreva os detalhes do evento..."></textarea>
            </div>
            <button type="submit" class="btn-submit">💾 Salvar</button>
        </form>
    </div>

    <!-- LISTA  -->
<div class="lista">
    <h2>Conteúdo</h2>
    <div class="grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Deseja realmente excluir?')" title="Excluir">×</a>
                    
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    
                    <!-- Mostrar imagem se houver -->
                    <?php if (!empty($row['imagem'])): ?>
                        <img src="data:<?php echo $row['tipo_imagem']; ?>;base64,<?php echo base64_encode($row['imagem']); ?>" alt="Imagem do evento">
                    <?php endif; ?>

                    <!-- Mostrar link para download se houver arquivo -->
                    <?php if (!empty($row['arquivo'])): ?>
                        <p>
                            📄 <a href="download-estrutura.php?id=<?php echo $row['id']; ?>" target="_blank">
                                <?php echo htmlspecialchars($row['nome_arquivo']); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <p><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></p>
                    
                    <small>
                        ✏️ <strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($row['criado_em'])); ?>
                    </small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-items">
                <p>📝 Nenhum conteúdo foi adicionado ainda.</p>
                <p>Utilize o formulário acima para criar o primeiro!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

    <br><br><br><br>
          <!-- Botão flutuante para voltar ao início -->
    <a href="../index.php#educacao.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>
    <!--footer-->
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>

    <!-- Botão de edição fixo -->
    <?php if (isset($_SESSION["usuario"])): ?>
    <div class="btn-add" onclick="addDataEstrutura()" data-autor="🤖 RoboEdu:" data-fala="Editar" title="Editar">
        <span id="btn-icon">✏️</span>
    </div>
    <input type="file" id="hiddenUpload" style="display:none" />
    <?php endif; ?>

    <script>
        // Função para melhorar a experiência do usuário
        document.getElementById('imagem').addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label');
            if (e.target.files.length > 0) {
                label.textContent = `📷 ${e.target.files[0].name}`;
                label.style.color = '#667eea';
            } else {
                label.textContent = '📎 Clique para selecionar uma imagem';
                label.style.color = '#6c757d';
            }
        });
        
    </script>
</body>
</html>

<?php $conn->close(); ?>