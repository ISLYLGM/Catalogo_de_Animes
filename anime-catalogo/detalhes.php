<?php 
session_start();

// Verifica se o usuário está logado
$logado = isset($_SESSION['usuario_nome']);
$usuario_nome = $logado ? $_SESSION['usuario_nome'] : null;

// Processa exclusão se foi enviada via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id']) && $logado) {
    $anime_id = $_POST['excluir_id'];
    $arquivo_animes = "animes_{$usuario_nome}.json";

    if (file_exists($arquivo_animes)) {
        $animes = json_decode(file_get_contents($arquivo_animes), true);
        $animes = array_filter($animes, fn($anime) => $anime['id'] !== $anime_id);
        $animes = array_values($animes);
        file_put_contents($arquivo_animes, json_encode($animes, JSON_PRETTY_PRINT));
    }

    header("Location: index.php");
    exit;
}

// Exibe os detalhes do anime
$id = $_GET['id'] ?? '';
$anime = null;

if ($logado) {
    $arquivo_animes = "animes_{$usuario_nome}.json";
    if (file_exists($arquivo_animes)) {
        $animes = json_decode(file_get_contents($arquivo_animes), true);
        foreach ($animes as $a) {
            if ($a['id'] === $id) {
                $anime = $a;
                break;
            }
        }
    }
} else {
    // Se não estiver logado, mostrar apenas os animes padrões
    $animes_padrao = [
        ['id' => '1', 'nome' => 'Naruto', 'imagem_url' => 'img/naruto.jpg', 'descricao' => 'Descrição do Naruto.'],
        ['id' => '2', 'nome' => 'One Piece', 'imagem_url' => 'img/onepiece.jpg', 'descricao' => 'Descrição do One Piece.'],
        ['id' => '3', 'nome' => 'Attack on Titan', 'imagem_url' => 'img/attackontitan.jpg', 'descricao' => 'Descrição do Attack on Titan.'],
        ['id' => '4', 'nome' => 'My Hero Academia', 'imagem_url' => 'img/bokunohero.jpg', 'descricao' => 'Descrição do My Hero Academia.'],
        ['id' => '5', 'nome' => 'Death Note', 'imagem_url' => 'img/deathnote.jpg', 'descricao' => 'Descrição do Death Note.'],
        ['id' => '6', 'nome' => 'Demon Slayer', 'imagem_url' => 'img/DemonSlayer.jpg', 'descricao' => 'Descrição do Demon Slayer.'],
    ];
    foreach ($animes_padrao as $a) {
        if ($a['id'] === $id) {
            $anime = $a;
            break;
        }
    }
}

// Se não achou o anime
if (!$anime) {
    echo "<p>Anime não encontrado.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($anime['nome']) ?></title>
    <style>
       body {
    font-family: Arial, sans-serif;
    padding: 0;
    margin: 0;
    background-color:rgb(13, 60, 87);
}

nav {
    background-color: #222;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: flex-end; /* Alinha os itens de navegação à direita */
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    box-sizing: border-box;
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

.anime-detalhes {
    max-width: 700px;
    margin: 90px auto 30px; /* Ajusta a margem superior para não ficar atrás do menu fixo */
    background-color:rgb(9, 40, 66);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.41);
    text-align: left;
    color: white;
}

.anime-detalhes img {
    width: 200px; /* Largura fixa igual ao catálogo */
    height: 300px; /* Altura fixa igual ao catálogo */
    object-fit: cover; /* Faz a imagem preencher a área sem distorcer */
    border-radius: 10px;
    margin: 0 auto;
    display: block;
}

.anime-detalhes h2 {
    margin-top: 20px;
}

.anime-detalhes p {
    text-align: left;
    line-height: 1.6;
    margin: 10px 0;
    word-wrap: break-word;
}

.anime-detalhes p strong {
    color: rgb(255, 96, 96);
    font-size: 16px;
}

.anime-detalhes .descricao {
    max-height: 200px;
    overflow-y: auto;
    text-overflow: ellipsis;
}

.btn-voltar {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 20px;
    background-color: rgb(24, 75, 90);
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn-voltar:hover {
    background-color: #444;
}

.btn-excluir {
    margin-top: 15px;
    padding: 10px 20px;
    background-color: rgb(112, 28, 28);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-excluir:hover {
    background-color: darkred;
}

    </style>
</head>
<body>

<!-- Navegação -->

<nav>
    <a href="index.php">Catálogo</a>
    <a href="perfil.php">Perfil</a>
    <a href="cadastro.php">Cadastrar Anime</a>
    <a href="logout.php">Sair</a>
</nav>

<div class="anime-detalhes">
    <img src="<?= htmlspecialchars($anime['imagem_url']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>">
    <h2><?= htmlspecialchars($anime['nome']) ?></h2>
    
    <p><strong>Gênero:</strong> <?= htmlspecialchars($anime['genero'] ?? '-') ?></p>
    <p><strong>Autor:</strong> <?= htmlspecialchars($anime['autor'] ?? '-') ?></p>
    <p><strong>Estúdio:</strong> <?= htmlspecialchars($anime['estudio'] ?? '-') ?></p>
    <p><strong>Ano:</strong> <?= htmlspecialchars($anime['ano'] ?? '-') ?></p>
    <p><strong>Avaliação:</strong> <?= htmlspecialchars($anime['avaliacao'] ?? '-') ?></p>
    
    <p class="descricao"><?= htmlspecialchars($anime['descricao']) ?></p>

    <a class="btn-voltar" href="index.php">Voltar ao Catálogo</a>

    <?php if ($logado): ?>
        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este anime?');">
            <input type="hidden" name="excluir_id" value="<?= $anime['id'] ?>">
            <button type="submit" class="btn-excluir">Excluir Anime</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
