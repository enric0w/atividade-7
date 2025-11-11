<?php
include '../includes/db.php';

$id = $_GET['id'];

if (isset($_POST['confirm'])) {
    $conn->query("DELETE FROM jogadores WHERE id = $id");
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Jogador</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Excluir Jogador</h1>
    <p>Tem certeza que deseja excluir este jogador?</p>
    <form method="POST">
        <button type="submit" name="confirm">Sim, excluir</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
