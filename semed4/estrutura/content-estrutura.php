<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// --- BUSCA ---
$sql = "SELECT id, titulo, mensagem, imagem, tipo_imagem, arquivo, nome_arquivo, tipo_arquivo, criado_em 
        FROM estrutura 
        ORDER BY criado_em DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Núcleo de educação digital</title>
    <link rel="stylesheet" href="content-all.css">
</head>
<body>
    <?php
            function transformarLinks($texto) {
                // Garante que "www.site.com" vire "http://www.site.com"
                $texto = preg_replace('~\b(www\.[^\s<]+)~i', 'http://$1', $texto);

                // Transforma links (http:// ou https://) em <a>
                $texto = preg_replace(
                    '~(https?://[^\s<]+)~i',
                    '<a href="$1" target="_blank" style="color:blue; text-decoration:underline;">$1</a>',
                    $texto
                );

                return nl2br($texto); // mantém as quebras de linha
               }
              ?>
    
    <h2 class="titulo-principal">Núcleo de educação digital</h2>
    <div class="container">        
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <br>
                <div class="item">
                    <h2><?= htmlspecialchars($row['titulo']) ?></h2>
                    <br>

                    <?php if (!empty($row['imagem'])): ?>
                        <img src="data:<?= $row['tipo_imagem'] ?>;base64,<?= base64_encode($row['imagem']) ?>" alt="Imagem">
                    <?php endif; ?>
                    <p><?= transformarLinks(htmlspecialchars($row['mensagem'])) ?></p>
                    
                     <!-- Mostrar link para download se houver arquivo -->
                    <?php if (!empty($row['arquivo'])): ?>
                        <p>
                            📄 <a href="estrutura/download-estrutura.php?id=<?php echo $row['id']; ?>" target="_blank">
                                <?php echo htmlspecialchars($row['nome_arquivo']); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    <br>
            
                    <p class="meta">Criado em: <?= $row['criado_em'] ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#555;">Nenhum registro encontrado.</p>
        <?php endif; ?>
    </div>
    
    <br><br><br>
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>
    <!-- Botão de lápis fixo -->
  <?php if (isset($_SESSION["usuario"])): ?>
  <div class="btn-add" onclick="addDataEstrutura()" data-autor="🤖 RoboEdu:" data-fala="Editar">
    <span id="btn-icon">✏️</span>
  </div>
  <input type="file" id="hiddenUpload" style="display:none" />
<?php endif; ?>
</body>
</html>