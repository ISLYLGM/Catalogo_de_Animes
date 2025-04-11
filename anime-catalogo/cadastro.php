<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit;
}

// Gêneros de anime em ordem alfabética
$generos = [
    'Doujinshi', 'Idol', 'Isekai', 'Kodomomuke', 'Mecha', 'Seinen', 'Shounen', 'Shoujo', 'Shoujo Ai', 'Slice of Life', 'Youkai'
];

// Processar o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $autor = $_POST['autor'];
    $estudio = $_POST['estudio'];
    $ano = $_POST['ano'];
    $avaliacao = $_POST['avaliacao'];
    $descricao = $_POST['descricao'];
    $imagem_url = $_POST['imagem_url'];

    $usuario_nome = $_SESSION['usuario_nome'];
    $arquivo_animes = "animes_{$usuario_nome}.json";

    if (file_exists($arquivo_animes)) {
        $animes = json_decode(file_get_contents($arquivo_animes), true);
    } else {
        $animes = [];
    }

    $novo_anime = [
        'id' => uniqid(),
        'nome' => $nome,
        'genero' => $genero,
        'autor' => $autor,
        'estudio' => $estudio,
        'ano' => $ano,
        'avaliacao' => $avaliacao,
        'descricao' => $descricao,
        'imagem_url' => $imagem_url
    ];

    $animes[] = $novo_anime;
    file_put_contents($arquivo_animes, json_encode($animes, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anime</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/@animangascenery.gif');
            margin: 0;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            
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
            max-width: 600px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: black;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin: 10px 0;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 100px;
            min-height: 100px;
            resize: none;
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
            margin-top: 15px;
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
    <a href="perfil.php">Perfil</a>
    <a href="cadastro.php" class="active">Cadastrar Anime</a>
    <a href="logout.php">Sair</a>
</nav>
 <img src="img/imagem_2025-04-10_233638129-removebg-preview.png" alt="Decoração" style="
        position: absolute;
        top: 67px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        z-index: 10;
    ">
<!-- Formulário -->
<div class="container">
    <h2>Cadastrar Novo Anime</h2>
    <form action="cadastro.php" method="POST">

        <div class="form-group">
            <label for="nome">Nome do Anime</label>
            <input type="text" id="nome" name="nome" required>
        </div>

        <div class="form-group">
            <label for="genero">Gênero</label>
            <select id="genero" name="genero" required>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?= $genero ?>"><?= $genero ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="autor">Autor do Anime</label>
            <input type="text" id="autor" name="autor" required>
        </div>

        <div class="form-group">
            <label for="estudio">Estúdio de Animação</label>
            <input type="text" id="estudio" name="estudio" required>
        </div>

        <div class="form-group">
            <label for="ano">Ano de Lançamento</label>
            <input type="number" id="ano" name="ano" required>
        </div>

        <div class="form-group">
            <label for="avaliacao">Avaliação</label>
            <select id="avaliacao" name="avaliacao" required>
                <option value="1">1 Estrela</option>
                <option value="2">2 Estrelas</option>
                <option value="3">3 Estrelas</option>
                <option value="4">4 Estrelas</option>
                <option value="5">5 Estrelas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" required></textarea>
        </div>

        <div class="form-group">
            <label for="imagem_url">Imagem URL</label>
            <input type="url" id="imagem_url" name="imagem_url" required>
        </div>

        <button type="submit">Cadastrar Anime</button>
    </form>
</div>

</body>
</html>
