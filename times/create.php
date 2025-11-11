<?php
include '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cidade = trim($_POST['cidade']);

    if (empty($nome) || empty($cidade)) {
        $message = '<p class="error">Nome e cidade são obrigatórios.</p>';
    } else {
        $stmt = $conn->prepare("INSERT INTO times (nome, cidade) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $cidade);
        if ($stmt->execute()) {
            $message = '<p class="success">Time adicionado com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao adicionar time.</p>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Time</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Adicionar Time</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" required>

        <button type="submit">Adicionar</button>
    </form>
</body>
</html>
