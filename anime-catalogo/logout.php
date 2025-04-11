<?php
session_start();

// Destruir a sessão
session_unset();
session_destroy();

// Redirecionar para o login
header("Location: login.php");
exit;
?>
