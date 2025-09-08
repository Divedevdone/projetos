<?php
// ajax_handler.php
$map = [
  'inicio' => 'content-inicio.html',
  'estrutura' => 'content-estrutura.html',
  'referencial' => 'content-referencial.html',
  'educacao-digital' => 'content-educacao-digital.html',
  'projetos' => 'content-projetos.html',
  'recursos' => 'content-recursos.html',
  'cursos' => 'content-cursos.html'
];

$target = $_GET['target'] ?? '';

if (!array_key_exists($target, $map)) {
    http_response_code(400);
    echo '<p>Conteúdo inválido.</p>';
    exit;
}

$fn = $map[$target];

if (!is_file($fn)) {
    http_response_code(404);
    echo '<p>Arquivo não encontrado.</p>';
    exit;
}

// retorna o conteúdo do arquivo (não usar includes diretos para segurança)
echo file_get_contents($fn);
