<?php
session_start();
include 'conexao.php'; // conexão com db-semed

// Pegar os arquivos do banco
$sql = "SELECT id, nome FROM recursos ORDER BY data_upload DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Núcleo de Educação Digital</title>
    <link rel="stylesheet" href="content.css">
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("listar.php")
                .then(res => res.json())
                .then(arquivos => {
                    let recursoDiv = document.querySelector(".feature-highlight p");
                    if (arquivos.length > 0) {
                        recursoDiv.innerHTML = arquivos.map(arq =>
                            `<a href="${arq.url}" target="_blank">📂 ${arq.nome}</a>`
                        ).join("<br>");
                    } else {
                        recursoDiv.innerHTML = "<em>Nenhum recurso disponível</em>";
                    }
                })
                .catch(err => {
                    console.error("Erro ao carregar arquivos:", err);
                });
        });
    </script>
    <h1 class="title">Secretaria</h1>
     <p class="text-content">
            A Secretaria Municipal de Educação compete o planejamento
            e a execução<br> 
            da política educacional do Município, especificamente através 
            das seguintes <br>
            atividades: instalação e manutenção de estabelecimentos de 
            ensino que oferecem<br>
            a Educação Básica: Educação Infantil e Ensino Fundamental, 
            planejamento, <br>
            organização, administração, orientação, acompanhamento, 
            controle e <br>
            avaliação do sistema educacional do Município, em consonância 
            com os sistemas <br>
            estadual e federal de educação, bem como a adoção de medidas 
            que visem a sua <br>
            expansão, consolidação e aperfeiçoamento; atualização permanente 
            da ação educativa, <br>
            ajustando-a às realidades local e regional, pela elevação do 
            nível da produtividade<br>
            da educação, visando a melhoria qualitativa dos processos educativos; 
            controle e <br>
            fiscalização do funcionamento dos prédios e estabelecimentos de ensino 
            a nível municipal; <br>
            promoção da perfeita articulação com os governos estadual e federal 
            em matéria de legislação<br>
            da política educacional; promoção de ações integradoras com os demais órgãos 
            componentes da <br>
            administração pública municipal, estadual e federal, cujas atividades se 
            inter-relacionem <br>
            com a ação educacional; manutenção dos programas de assistência ao estudante 
            e outras atividades<br>
            correlatas determinadas pela Prefeita.<br><br>

            Secretaria Municipal de Educação<br>
            Endereço: Praça 8 de Janeiro, 225<br>
            E-mail: gabinete.semed@sjp.pr.gov.br<br>
            Telefone: (41) 3134-4811 <br><br>

            Horário de atendimento<br>
            Segunda a sexta-feira, das 8h às 12h e das 13h às 17h.<br><br>

            SEMED | Secretaria municipal de educação <br><br>
        </p>  
        <script src="script.js"></script>
    <script src="script-robo.js"></script>
  
</body>

</html>