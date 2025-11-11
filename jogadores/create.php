<?php
include '../includes/db.php';

$message = '';
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
        $stmt = $conn->prepare("INSERT INTO jogadores (nome, posicao, numero_camisa, time_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nome, $posicao, $numero_camisa, $time_id);
        if ($stmt->execute()) {
            $message = '<p class="success">Jogador adicionado com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao adicionar jogador.</p>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Jogador</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Adicionar Jogador</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="posicao">Posição:</label>
        <select name="posicao" required>
            <option value="">Selecione</option>
            <option value="GOL">GOL</option>
            <option value="ZAG">ZAG</option>
            <option value="LAT">LAT</option>
            <option value="MEI">MEI</option>
            <option value="ATA">ATA</option>
        </select>

        <label for="numero_camisa">Número da Camisa:</label>
        <input type="number" name="numero_camisa" min="1" max="99" required>

        <label for="time_id">Time:</label>
        <select name="time_id" required>
            <option value="">Selecione</option>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>"><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Adicionar</button>
    </form>
</body>
</html>
