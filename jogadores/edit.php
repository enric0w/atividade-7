<?php
include '../includes/db.php';

$message = '';
$id = $_GET['id'];
$times = $conn->query("SELECT id, nome FROM times");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $posicao = $_POST['posicao'];
    $numero_camisa = (int)$_POST['numero_camisa'];
    $time_id = (int)$_POST['time_id'];

    $posicoes_validas = ['GOL', 'ZAG', 'LAT', 'MEI', 'ATA'];

    if (empty($nome) || !in_array($posicao, $posicoes_validas) || $numero_camisa < 1 || $numero_camisa > 99 || !$time_id) {
        $message = '<p class="error">Dados inválidos. Verifique nome, posição, número da camisa (1-99) e time.</p>';
    } else {
        $stmt = $conn->prepare("UPDATE jogadores SET nome = ?, posicao = ?, numero_camisa = ?, time_id = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $nome, $posicao, $numero_camisa, $time_id, $id);
        if ($stmt->execute()) {
            $message = '<p class="success">Jogador atualizado com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao atualizar jogador.</p>';
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM jogadores WHERE id = $id");
$jogador = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Jogador</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Editar Jogador</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($jogador['nome']); ?>" required>

        <label for="posicao">Posição:</label>
        <select name="posicao" required>
            <option value="GOL" <?php if ($jogador['posicao'] == 'GOL') echo 'selected'; ?>>GOL</option>
            <option value="ZAG" <?php if ($jogador['posicao'] == 'ZAG') echo 'selected'; ?>>ZAG</option>
            <option value="LAT" <?php if ($jogador['posicao'] == 'LAT') echo 'selected'; ?>>LAT</option>
            <option value="MEI" <?php if ($jogador['posicao'] == 'MEI') echo 'selected'; ?>>MEI</option>
            <option value="ATA" <?php if ($jogador['posicao'] == 'ATA') echo 'selected'; ?>>ATA</option>
        </select>

        <label for="numero_camisa">Número da Camisa:</label>
        <input type="number" name="numero_camisa" value="<?php echo $jogador['numero_camisa']; ?>" min="1" max="99" required>

        <label for="time_id">Time:</label>
        <select name="time_id" required>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>" <?php if ($jogador['time_id'] == $time['id']) echo 'selected'; ?>><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
