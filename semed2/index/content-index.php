<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// Buscar todas as observações
$observacoes = $conn->query("SELECT * FROM observacao ORDER BY criado_em DESC");


// Buscar cronogramas
$sql = "SELECT id, titulo, imagem, mensagem, data_evento, hora_evento, criado_em 
        FROM cronograma 
        ORDER BY criado_em DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma</title>
    <link rel="stylesheet" href="index/content-index.css">   
</head>

<body>
    <!-- Exibir observações aqui -->
     🔔<strong>Avisos:</strong><br><br>
     <?php if ($observacoes && $observacoes->num_rows > 0): ?>
            <?php while($obs = $observacoes->fetch_assoc()): ?>
                <br>
                <div class="observacao-card">
                    <div class="badge-aviso"></div>
                    <div class="observacao-conteudo">
                        <?php echo nl2br(htmlspecialchars($obs['observacao'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-observacoes">
                <h3>📝 Nenhum aviso disponível</h3>
                <p>Não há avisos cadastrados no momento.</p>
            </div>
        <?php endif; ?>
    </div>  
    <div class="title">Cronograma</div>

<div class="feature-highlight">
    <div class="recursos-lista">
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="item">
                        <div class="item-header">
                            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                            <div class="event-date">
                                <span class="date-badge">
                                    📅 <?php echo date('d/m/Y', strtotime($row['data_evento'])); ?>
                                </span>
                                <span class="time-badge">
                                    ⏰ <?php echo $row['hora_evento']; ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($row['imagem'])): ?>
                            <div class="image-container">
                                <img src="index/exibe_imagem.php?id=<?php echo $row['id']; ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">

                                <div class="image-overlay"></div>
                            </div>
                        <?php endif; ?>

                        <div class="content">
                            <p>
                        <?php
                            // Expressão regular para encontrar links (http, https, www)
                            $urlRegex = '/(https?:\/\/[^\s]+|www\.[^\s]+)/i';

                            // Usa preg_replace_callback para encontrar os links e processar o texto
                            $mensagem_com_links = preg_replace_callback(
                                $urlRegex,
                                function ($matches) {
                                    $url = $matches[0];
                                    // Adiciona o "http://" se não houver, para garantir que o link funcione
                                    $link_url = (strpos($url, 'http') === 0) ? $url : 'http://' . $url;
                                    // Cria o HTML do link e aplica htmlspecialchars somente na URL e no texto do link para segurança
                                    return '<a href="' . htmlspecialchars($link_url) . '" target="_blank">' . htmlspecialchars($url) . '</a>';
                                },
                                htmlspecialchars($row['mensagem']) // Aplica htmlspecialchars para garantir que o resto do texto seja seguro
                            );

                            // Imprime o resultado, mantendo as quebras de linha
                            echo nl2br($mensagem_com_links);
                        ?>
                        </p>
                        </div>

                        <div class="item-footer">
                            <small class="created-info">
                                ✨ Criado em <?php echo date('d/m/Y', strtotime($row['criado_em'])); ?>
                            </small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>Nenhum cronograma disponível</h3>
                <p>Os próximos eventos aparecerão aqui quando forem cadastrados.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>

    <!-- Botão de lápis fixo -->
    <?php if (isset($_SESSION["usuario"])): ?>
    <div class="btn-add" onclick="addDataInicio()" data-autor="🤖 RoboEdu:" data-fala="Editar">
        <span id="btn-icon">✏️</span>
    </div>
    <input type="file" id="hiddenUpload" style="display:none" />
    <?php endif; ?>
</body>
</html>
