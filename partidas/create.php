<?php
include '../includes/db.php';

$message = '';
$times = $conn->query("SELECT id, nome FROM times");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $time_casa_id = (int)$_POST['time_casa_id'];
    $time_fora_id = (int)$_POST['time_fora_id'];
    $data_jogo = $_POST['data_jogo'];
    $gols_casa = (int)$_POST['gols_casa'];
    $gols_fora = (int)$_POST['gols_fora'];

    if ($time_casa_id == $time_fora_id || $gols_casa < 0 || $gols_fora < 0 || empty($data_jogo)) {
        $message = '<p class="error">Times devem ser diferentes, placar não negativo e data obrigatória.</p>';
    } else {
        $stmt = $conn->prepare("INSERT INTO partidas (time_casa_id, time_fora_id, data_jogo, gols_casa, gols_fora) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisii", $time_casa_id, $time_fora_id, $data_jogo, $gols_casa, $gols_fora);
        if ($stmt->execute()) {
            $message = '<p class="success">Partida adicionada com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao adicionar partida.</p>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Partida</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Adicionar Partida</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="time_casa_id">Time Casa:</label>
        <select name="time_casa_id" required>
            <option value="">Selecione</option>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>"><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="time_fora_id">Time Fora:</label>
        <select name="time_fora_id" required>
            <option value="">Selecione</option>
            <?php $times->data_seek(0); while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>"><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="data_jogo">Data do Jogo:</label>
        <input type="date" name="data_jogo" required>

        <label for="gols_casa">Gols Casa:</label>
        <input type="number" name="gols_casa" min="0" required>

        <label for="gols_fora">Gols Fora:</label>
        <input type="number" name="gols_fora" min="0" required>

        <button type="submit">Adicionar</button>
    </form>
</body>
</html>
