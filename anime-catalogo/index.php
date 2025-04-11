<?php
session_start();

// Lista de animes pré-definidos (para usuários não logados)
$animesPreDefinidos = [
    ['id' => 1, 'nome' => 'Naruto', 'imagem_url' => 'img/naruto.jpg', 'descricao' => 'Descrição do Naruto.'],
    ['id' => 2, 'nome' => 'One Piece', 'imagem_url' => 'img/onepiece.jpg', 'descricao' => 'Descrição do One Piece.'],
    ['id' => 3, 'nome' => 'Attack on Titan', 'imagem_url' => 'img/attackontitan.jpg', 'descricao' => 'Descrição do Attack on Titan.'],
    ['id' => 4, 'nome' => 'Death Note', 'imagem_url' => 'img/deathnote.jpg', 'descricao' => 'Descrição do Death Note.'],
    ['id' => 5, 'nome' => 'Demon Slayer', 'imagem_url' => 'img/DemonSlayer.jpg', 'descricao' => 'Descrição do Demon Slayer.']
];

// Verificar se o usuário está logado
if (isset($_SESSION['usuario_nome'])) {
    $logado = true;
    $usuario_nome = $_SESSION['usuario_nome'];

    // Carregar os animes do arquivo JSON do usuário
    $arquivo_animes = "animes_{$usuario_nome}.json";
    
    // Verificar se o arquivo de animes existe
    if (file_exists($arquivo_animes)) {
        $animesUsuarioLogado = json_decode(file_get_contents($arquivo_animes), true);
    } else {
        $animesUsuarioLogado = [];
    }
} else {
    $logado = false;
    // Se não estiver logado, usamos os animes pré-definidos
    $animesUsuarioLogado = $animesPreDefinidos;
}

// Verificar se há animes cadastrados
$animeCount = count($animesUsuarioLogado);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CATÁLOGO DE ANIMES</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color:rgba(84, 148, 190, 0.84);
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

        h1 {
            text-align: center;
            margin-top: 20px;
            color:rgb(255, 255, 255);
        }

        #filterInput {
            display: block;
            margin: 20px auto;
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .anime-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .anime-card {
            background: white;
            margin: 15px;
            width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(6, 0, 59, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color:rgb(218, 167, 90);
        }

        .anime-card:hover {
            transform: scale(1.03);
        }

        .anime-card img {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .anime-card h3 {
            margin: 10px 0 5px 0;
        }

        .anime-card p {
            padding: 0 10px;
            font-size: 14px;
            color: #555;
        }

        .ver-mais-btn {
            margin: 10px;
            padding: 8px 10px;
            background-color: #222;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .ver-mais-btn:hover {
            background-color: #444;
        }

        .no-animes-message {
            text-align: center;
            color: white;
            font-size: 18px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<!-- Navegação -->
<nav>
    <a href="index.php" class="active">Catálogo</a>
    <?php if ($logado): ?>
        <a href="perfil.php">Perfil</a>
        <a href="cadastro.php">Cadastrar Anime</a>
        <a href="logout.php">Sair</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>

<!-- Título -->
<h1>Catálogo de Animes</h1>

<!-- Filtro -->
<input type="text" id="filterInput" placeholder="Buscar anime por nome...">

<!-- Lista de animes -->
<div class="anime-container" id="animeContainer">
    <?php if ($animeCount == 0): ?>
        <!-- Se não houver animes cadastrados -->
        <p class="no-animes-message">Adicione animes!</p>
    <?php else: ?>
        <?php foreach ($animesUsuarioLogado as $anime): ?>
            <div class="anime-card">
                <img src="<?= htmlspecialchars($anime['imagem_url']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>">
                <h3><?= htmlspecialchars($anime['nome']) ?></h3>
                <a href="detalhes.php?id=<?= $anime['id'] ?>" class="ver-mais-btn">Ver mais</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Script de filtro -->
<script>
    function normalize(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    document.getElementById('filterInput').addEventListener('input', function () {
        const filter = normalize(this.value);
        const cards = document.querySelectorAll('.anime-card');

        let found = false;  // Variável para checar se algum anime foi encontrado

        cards.forEach(card => {
            const name = normalize(card.querySelector('h3').textContent);
            if (name.includes(filter)) {
                card.style.display = 'flex';
                found = true;
            } else {
                card.style.display = 'none';
            }
        });

        // Se nenhum anime for encontrado após o filtro, exibe a mensagem "Anime não encontrado"
        const noResultsMessage = document.querySelector('.no-animes-message');
        if (!found) {
            noResultsMessage.textContent = "Anime não encontrado";
        } else {
            noResultsMessage.textContent = "";  // Limpar a mensagem
        }
    });
</script>

<?php include 'footer.php'; ?>

</body>
</html>
