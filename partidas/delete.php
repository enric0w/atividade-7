<?php
include '../includes/db.php';

$id = $_GET['id'];

if (isset($_POST['confirm'])) {
    $conn->query("DELETE FROM partidas WHERE id = $id");
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Partida</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Excluir Partida</h1>
    <p>Tem certeza que deseja excluir esta partida?</p>
    <form method="POST">
        <button type="submit" name="confirm">Sim, excluir</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
