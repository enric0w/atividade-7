<?php
include '../includes/db.php';

$id = $_GET['id'];

// Verificar dependências
$jogadores = $conn->query("SELECT COUNT(*) as count FROM jogadores WHERE time_id = $id")->fetch_assoc()['count'];
$partidas_casa = $conn->query("SELECT COUNT(*) as count FROM partidas WHERE time_casa_id = $id")->fetch_assoc()['count'];
$partidas_fora = $conn->query("SELECT COUNT(*) as count FROM partidas WHERE time_fora_id = $id")->fetch_assoc()['count'];

if ($jogadores > 0 || $partidas_casa > 0 || $partidas_fora > 0) {
    echo '<p class="error">Não é possível excluir o time porque há jogadores ou partidas associadas.</p>';
    echo '<a href="index.php">Voltar</a>';
    exit;
}

if (isset($_POST['confirm'])) {
    $conn->query("DELETE FROM times WHERE id = $id");
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Time</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Excluir Time</h1>
    <p>Tem certeza que deseja excluir este time?</p>
    <form method="POST">
        <button type="submit" name="confirm">Sim, excluir</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
