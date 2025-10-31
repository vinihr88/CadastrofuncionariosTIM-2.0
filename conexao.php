<?php
$servidor = "localhost"; // Host MySQL
$usuario = "root"; // Nome de usuário do MySQL
$senha = ""; // Senha do MySQL
$banco = "sistema_cadastroTim"; // Nome do banco de dados

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>