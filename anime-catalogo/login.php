<?php
session_start();

// Função para carregar usuários do arquivo
function carregarUsuarios() {
    $usuarios = [];
    if (file_exists('usuarios.txt')) {
        $file = fopen('usuarios.txt', 'r');
        while ($linha = fgets($file)) {
            // Separar nome de usuário e senha
            list($usuario, $senha) = explode(":", trim($linha));
            $usuarios[$usuario] = $senha;
        }
        fclose($file);
    }
    return $usuarios;
}

// Função para salvar usuários no arquivo
function salvarUsuarios($usuarios) {
    $file = fopen('usuarios.txt', 'w');
    foreach ($usuarios as $usuario => $senha) {
        fwrite($file, $usuario . ":" . $senha . PHP_EOL);
    }
    fclose($file);
}

// Lógica para o login ou cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $acao = $_POST['acao'] ?? '';
    
    // Carregar usuários do arquivo
    $usuarios = carregarUsuarios();
    
    // Se for um login
    if ($acao === 'login') {
        if (isset($usuarios[$usuario]) && $usuarios[$usuario] === $senha) {
            $_SESSION['usuario_nome'] = $usuario;
            $_SESSION['primeiro_login'] = $_SESSION['primeiro_login'] ?? date("Y-m-d H:i:s"); // Se for a primeira vez
            $_SESSION['ultimo_login'] = date("Y-m-d H:i:s"); // Atualiza o último login
            header('Location: perfil.php'); // Redireciona para a página de perfil
            exit;
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
    
    // Se for cadastro de novo usuário
    elseif ($acao === 'cadastro') {
        // Verificar se o nome de usuário já existe
        if (isset($usuarios[$usuario])) {
            $erro = 'Nome já está sendo usado.';
        } elseif ($senha === $confirmar_senha) {
            // Adicionar novo usuário ao array
            $usuarios[$usuario] = $senha;
            salvarUsuarios($usuarios);  // Salvar usuários no arquivo
            $_SESSION['usuario_nome'] = $usuario;
            $_SESSION['primeiro_login'] = date("Y-m-d H:i:s"); // Primeira vez login
            $_SESSION['ultimo_login'] = date("Y-m-d H:i:s"); // Atualiza o último login
            header('Location: perfil.php'); // Redireciona para a página de perfil
            exit;
        } else {
            $erro = 'As senhas não coincidem.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login / Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
             background-image: url('img/….gif');
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
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.56);
            background-color: rgb(10, 15, 66);
            color: white;
        }

        h2 {
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            background-color: rgba(99, 16, 194, 0.56);
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color:rgba(9, 107, 107, 0.71) ;
        }

        .erro {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .alternativa {
            text-align: center;
            margin-top: 15px;
        }

        .opcao-login-cadastro {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<!-- Navegação -->
<nav>
    <a href="index.php">Catálogo</a> <!-- Corrigido o link para index.php -->
    <a href="login.php" class="active">Login</a>
</nav>

<!-- Formulário -->
<div class="container">
    <h2>Login / Cadastro</h2>

    <!-- Formulário de Login -->
    <form method="POST">
        <!-- Seleção de Login ou Cadastro -->
        <div class="opcao-login-cadastro">
            <label><input type="radio" name="tipo_usuario" value="existente" checked> Usuário Existente</label>
            <label><input type="radio" name="tipo_usuario" value="novo"> Novo Usuário</label>
        </div>

        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required>

        <!-- Se for um novo usuário, mostrar campo de confirmação de senha -->
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <div id="confirmar_senha_div" style="display:none;">
            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha">
        </div>

        <!-- Ação de login ou cadastro -->
        <input type="hidden" name="acao" id="acao" value="login">

        <button type="submit" id="submit-button">Entrar</button>
    </form>

    <?php if (isset($erro)): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
</div>

<script>
    // Função para alterar entre login e cadastro
    document.querySelectorAll('input[name="tipo_usuario"]').forEach((input) => {
        input.addEventListener('change', function() {
            if (this.value === 'novo') {
                document.getElementById('confirmar_senha_div').style.display = 'block';
                document.getElementById('acao').value = 'cadastro';
                document.getElementById('submit-button').textContent = 'Cadastrar';
            } else {
                document.getElementById('confirmar_senha_div').style.display = 'none';
                document.getElementById('acao').value = 'login';
                document.getElementById('submit-button').textContent = 'Entrar';
            }
        });
    });
</script>
<?php include 'footer.php'; ?>
</body>
</html>
