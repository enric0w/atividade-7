<?php
include '../includes/db.php';

$message = '';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cidade = trim($_POST['cidade']);

    if (empty($nome) || empty($cidade)) {
        $message = '<p class="error">Nome e cidade são obrigatórios.</p>';
    } else {
        $stmt = $conn->prepare("UPDATE times SET nome = ?, cidade = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $cidade, $id);
        if ($stmt->execute()) {
            $message = '<p class="success">Time atualizado com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao atualizar time.</p>';
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM times WHERE id = $id");
$time = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Time</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Editar Time</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($time['nome']); ?>" required>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" value="<?php echo htmlspecialchars($time['cidade']); ?>" required>

        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
