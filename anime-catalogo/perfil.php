<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit;
}

// Definir as datas de login
$usuario_nome = $_SESSION['usuario_nome'];
$primeiro_login = $_SESSION['primeiro_login'] ?? date("Y-m-d H:i:s");
$ultimo_login = date("Y-m-d H:i:s");

// Atualizar o último login
$_SESSION['ultimo_login'] = $ultimo_login;

// Salvar a data do primeiro login se ainda não foi salva
if (!isset($_SESSION['primeiro_login'])) {
    $_SESSION['primeiro_login'] = $primeiro_login;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(54, 99, 158);
            margin: 0;
        }

        nav {
            background-color: #222;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: flex-end;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
        }

        nav a.active {
            background-color: #444;
        }

        .container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color:rgba(148, 212, 255, 0.8);
        }

        h2 {
            text-align: center;
        }

        .perfil-imagem {
            display: block;
            margin: 20px auto;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: rgb(255, 250, 221);;
            text-align: center;
            line-height: 150px;
            font-size: 50px;
        }

        .info {
            margin: 10px 0;
            font-size: 16px;
        }

        button {
            width: 100%;
            background-color: #222;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

<!-- Navegação -->
<nav>
    <a href="index.php">Catálogo</a>
    <a href="perfil.php" class="active">Perfil</a>
    <a href="cadastro.php">Cadastrar Anime</a>
    <a href="logout.php">Sair</a>
</nav>

<!-- Exibir Perfil -->
<div class="container">
    <h2>Perfil de Usuário</h2>

    <div class="perfil-imagem">
        <span><?= strtoupper(substr($usuario_nome, 0, 1)) ?></span> <!-- Primeira letra do nome -->
    </div>

    <div class="info">
        <strong>Nome:</strong> <?= htmlspecialchars($usuario_nome) ?>
    </div>

    <div class="info">
        <strong>Primeiro Login:</strong> <?= $primeiro_login ?>
    </div>

    <div class="info">
        <strong>Último Login:</strong> <?= $ultimo_login ?>
    </div>

    <!-- Botão para Sair -->
    <form action="logout.php" method="POST">
        <button type="submit">Sair</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
