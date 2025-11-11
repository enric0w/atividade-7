<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'futebol_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error . ". Verifique se o MySQL está rodando e o banco 'futebol_db' foi criado.");
}
?>
