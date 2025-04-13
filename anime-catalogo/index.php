<?php
session_start();

// Lista de animes pré-definidos (para usuários não logados)
$animesPreDefinidos = [
    ['id' => 1, 'nome' => 'Naruto', 'imagem_url' => 'img/naruto.jpg', 'descricao' => 'Descrição do Naruto.', 'genero' => 'Shounen'],
    ['id' => 2, 'nome' => 'One Piece', 'imagem_url' => 'img/onepiece.jpg', 'descricao' => 'Descrição do One Piece.', 'genero' => 'Shounen'],
    ['id' => 3, 'nome' => 'Attack on Titan', 'imagem_url' => 'img/attackontitan.jpg', 'descricao' => 'Descrição do Attack on Titan.', 'genero' => 'Seinen'],
    ['id' => 4, 'nome' => 'My Hero Academia', 'imagem_url' => 'img/bokunohero.jpg', 'descricao' => 'Descrição do My Hero Academia.', 'genero' => 'Shounen'],
    ['id' => 5, 'nome' => 'Death Note', 'imagem_url' => 'img/deathnote.jpg', 'descricao' => 'Descrição do Death Note.', 'genero' => 'Seinen'],
    ['id' => 6, 'nome' => 'Demon Slayer', 'imagem_url' => 'img/DemonSlayer.jpg', 'descricao' => 'Descrição do Demon Slayer.', 'genero' => 'Shounen']
];

// Verificar se o usuário está logado
if (isset($_SESSION['usuario_nome'])) {
    $logado = true;
    $usuario_nome = $_SESSION['usuario_nome'];

    // Carregar os animes do arquivo JSON do usuário
    $arquivo_animes = "animes_{$usuario_nome}.json";
    
    if (file_exists($arquivo_animes)) {
        $animesUsuarioLogado = json_decode(file_get_contents($arquivo_animes), true);
    } else {
        $animesUsuarioLogado = [];
    }
} else {
    $logado = false;
    $animesUsuarioLogado = $animesPreDefinidos;
}
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
            background-color:rgba(18, 65, 126, 0.84);
        }

        nav {
            background-color: rgba(2, 5, 37, 0.73);
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

        #filterInput,
        #generoSelect {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 300px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.36);
            overflow: hidden;
            text-align: center;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color:rgb(30, 12, 73);
            color:white;
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

<!-- Filtros -->
<input type="text" id="filterInput" placeholder="Buscar anime por nome...">

<select id="generoSelect">
    <option value="">Filtrar por gênero...</option>
    <?php
        $generos = ['Doujinshi', 'Idol', 'Isekai', 'Kodomomuke', 'Mecha', 'Seinen', 'Shounen', 'Shoujo', 'Shoujo Ai', 'Slice of Life', 'Youkai'];
        foreach ($generos as $genero) {
            echo "<option value=\"$genero\">$genero</option>";
        }
    ?>
</select>

<!-- Lista de animes -->
<div class="anime-container" id="animeContainer">
    <?php foreach ($animesUsuarioLogado as $anime): ?>
        <div class="anime-card" data-genero="<?= htmlspecialchars($anime['genero'] ?? '') ?>">
            <img src="<?= htmlspecialchars($anime['imagem_url']) ?>" alt="<?= htmlspecialchars($anime['nome']) ?>">
            <h3><?= htmlspecialchars($anime['nome']) ?></h3>
            <p style="display:none;" class="anime-genero"><?= htmlspecialchars($anime['genero'] ?? '') ?></p>
            <a href="detalhes.php?id=<?= $anime['id'] ?>" class="ver-mais-btn">Ver mais</a>
        </div>
    <?php endforeach; ?>
</div>

<!-- Script de filtro -->
<script>
    function normalize(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    const filterInput = document.getElementById('filterInput');
    const generoSelect = document.getElementById('generoSelect');
    const cards = document.querySelectorAll('.anime-card');

    filterInput.addEventListener('input', aplicarFiltros);
    generoSelect.addEventListener('change', aplicarFiltros);

    function aplicarFiltros() {
        const nomeFiltro = normalize(filterInput.value);
        const generoFiltro = generoSelect.value;

        cards.forEach(card => {
            const nome = normalize(card.querySelector('h3').textContent);
            const genero = card.getAttribute('data-genero');

            const correspondeNome = nome.includes(nomeFiltro);
            const correspondeGenero = !generoFiltro || genero === generoFiltro;

            card.style.display = (correspondeNome && correspondeGenero) ? 'flex' : 'none';
        });
    }
</script>

<?php include 'footer.php'; ?>
</body>
</html>
