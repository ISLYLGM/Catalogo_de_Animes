<?php
session_start();


function carregarUsuarios() {
    $usuarios = [];
    if (file_exists('usuarios.txt')) {
        $file = fopen('usuarios.txt', 'r');
        while ($linha = fgets($file)) {
            list($usuario, $senha) = explode(":", trim($linha));
            $usuarios[$usuario] = $senha;
        }
        fclose($file);
    }
    return $usuarios;
}


function salvarUsuarios($usuarios) {
    $file = fopen('usuarios.txt', 'w');
    foreach ($usuarios as $usuario => $senha) {
        fwrite($file, $usuario . ":" . $senha . PHP_EOL);
    }
    fclose($file);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $acao = $_POST['acao'] ?? '';
    

    $usuarios = carregarUsuarios();
    
    if ($acao === 'login') {
        if (isset($usuarios[$usuario]) && password_verify($senha, $usuarios[$usuario])) {
            $_SESSION['usuario_nome'] = $usuario;
            $_SESSION['primeiro_login'] = $_SESSION['primeiro_login'] ?? date("Y-m-d H:i:s");
            $_SESSION['ultimo_login'] = date("Y-m-d H:i:s");
            header('Location: perfil.php');
            exit;
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }


    elseif ($acao === 'cadastro') {
        if (isset($usuarios[$usuario])) {
            $erro = 'Nome já está sendo usado.';
        } elseif ($senha === $confirmar_senha) {
          
            $usuarios[$usuario] = password_hash($senha, PASSWORD_DEFAULT);
            salvarUsuarios($usuarios);
            $_SESSION['usuario_nome'] = $usuario;
            $_SESSION['primeiro_login'] = date("Y-m-d H:i:s");
            $_SESSION['ultimo_login'] = date("Y-m-d H:i:s");
            header('Location: perfil.php');
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
                color: white;
                position: relative;
                overflow-x: hidden;
            }

            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('img/….gif');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                filter: blur(8px);
                z-index: -1;
            }

            nav {
                padding: 20px 40px;
                display: flex;
                justify-content: flex-end;
                gap: 20px;
                position: relative;
            }

            nav a {
                color: white;
                text-decoration: none;
                font-size: 18px;
                font-weight: bold;
                padding: 8px 16px;
                border: 1px solid rgba(255,255,255,0.3);
                border-radius: 8px;
                transition: 0.3s;
            }

            nav a:hover, nav a.active {
                background-color: rgba(255,255,255,0.1);
                backdrop-filter: blur(5px);
            }

            .container {
                max-width: 350px;
                margin: 100px auto;
                background: rgba(0, 0, 50, 0.7);
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 0 20px rgba(0,0,0,0.7);
                backdrop-filter: blur(5px);
            }

            h2 {
                text-align: center;
                margin-bottom: 25px;
                font-size: 28px;
            }

            input[type="text"],
            input[type="password"] {
                width: 95%;
                padding: 12px;
                margin: 10px 0;
                font-size: 16px;
                border: none;
                border-radius: 8px;
                background: rgba(255,255,255,0.1);
                color: white;
                outline: none;
                transition: 0.3s;
            }


            input[type="text"]:focus,
            input[type="password"]:focus {
                background: rgba(255,255,255,0.2);
            }

            button {
                width: 101%;
                padding: 12px;
                margin: 10px 0; 
                font-size: 18px;
                border: none;
                border-radius: 8px;
                background: rgba(99, 16, 194, 0.7);
                color: white;
                cursor: pointer;
                transition: 0.3s;
            }

            button:hover {
                background: rgba(9, 107, 107, 0.8);
            }

            .opcao-login-cadastro {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                margin-bottom: 15px;
            }

            .opcao-login-cadastro label {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .erro {
                color: #ff6b6b;
                text-align: center;
                margin-top: 10px;
                font-weight: bold;
            }

            #confirmar_senha_div {
                margin-top: 10px;
            }

    </style>
</head>
<body>


<nav>
    <a href="index.php">Catálogo</a> 
    <a href="login.php" class="active">Login</a>
</nav>


<div class="container">
    <h2>Login / Cadastro</h2>


    <form method="POST">

        <div class="opcao-login-cadastro">
            <label><input type="radio" name="tipo_usuario" value="existente" checked> Usuário Existente</label>
            <label><input type="radio" name="tipo_usuario" value="novo"> Novo Usuário</label>
        </div>

        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <div id="confirmar_senha_div" style="display:none;">
            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha">
        </div>

    
        <input type="hidden" name="acao" id="acao" value="login">

        <button type="submit" id="submit-button">Entrar</button>
    </form>

    <?php if (isset($erro)): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
</div>

<script>
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

