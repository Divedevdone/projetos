<?php
session_start();
include 'conexao.php';

$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $senha = trim($_POST["senha"]);

    if (!empty($usuario) && !empty($senha)) {
        // Gera hash seguro da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere no banco
        $sql = "INSERT INTO usuarios (usuario, senha) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $senhaHash);

        if ($stmt->execute()) {
            $mensagem = "✅ Usuário cadastrado com sucesso!";
        } else {
            if ($conn->errno == 1062) { // erro de chave única (usuário já existe)
                $mensagem = "⚠️ Usuário já existe!";
            } else {
                $mensagem = "❌ Erro ao cadastrar: " . $conn->error;
            }
        }
    } else {
        $mensagem = "⚠️ Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        /* Logos posicionados */
.logo-semed {
    position: fixed;
    top: 1px;
    right: 620px;
    width: 130px;
    height: auto;
    z-index: 100;
}


/* Rodapé */
.footer-content {
    position: absolute;
    top: 532px;
    right: 335px;
    text-align: center;
    font-size: 14px;
    width: 35%;
    display: flex;
}
           /* Logos posicionados */
.logo-semed {
    position: fixed;
    top: 1px;
    right: 620px;
    width: 125px;
    height: auto;
    z-index: 100;
}
/* Rodapé */
.footer-content {
    position: absolute;
    top: 532px;
    right: 335px;
    text-align: center;
    font-size: 14px;
    width: 35%;
    display: flex;
}
    
        body {
            font-family: Arial, sans-serif;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        .container {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
            width: 350px;
            height:100px:
        }
        .cadastro {
            background: white;
            width: 100px;
     
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            border: none;
            background: #42519C;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #2e3973;
        }
        .mensagem {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .link-voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #42519C;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Novo Usuário</h2>
        <form action="cadastrar.php" method="post">
            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Cadastrar</button>
        </form>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>

        <a href="index.php" class="link-voltar">⬅ Voltar para início</a>
    </div>
    

            <div>
                <img src="semed.png" alt="Logo SEMED" class="logo-semed">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>

            <div class="footer-content">
                <p>SEMED | Secretaria municipal de educação</p>
            </div>
</body>
</html>
