<?php
session_start();
include 'conexao.php';

$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($senha, $row["senha"])) {
            $_SESSION["usuario"] = $usuario;
            header("Location: index.php"); // redireciona para página principal
            exit();
        } else {
            $mensagem = "❌ Senha incorreta!";
        }
    } else {
        $mensagem = "⚠️ Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Header */
.header {
    background: white;
    padding: 3px;
    position: fixed;
    width: 100%;
    top: 0;
    right:200px
    z-index: 50;
    backdrop-filter: blur(10px);
    color: antiquewhite;
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
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="cadastro"><a href="cadastrar.php" class="link-voltar">Cadastrar</a></div>
            <nav>
                <ul class="nav-menu">
                    
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
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
