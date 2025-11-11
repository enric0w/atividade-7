<?php
include '../includes/db.php';

$message = '';
$id = $_GET['id'];
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
        $stmt = $conn->prepare("UPDATE partidas SET time_casa_id = ?, time_fora_id = ?, data_jogo = ?, gols_casa = ?, gols_fora = ? WHERE id = ?");
        $stmt->bind_param("iisiii", $time_casa_id, $time_fora_id, $data_jogo, $gols_casa, $gols_fora, $id);
        if ($stmt->execute()) {
            $message = '<p class="success">Partida atualizada com sucesso.</p>';
        } else {
            $message = '<p class="error">Erro ao atualizar partida.</p>';
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM partidas WHERE id = $id");
$partida = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Partida</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Editar Partida</h1>
    <a href="index.php">Voltar</a>
    <?php echo $message; ?>
    <form method="POST">
        <label for="time_casa_id">Time Casa:</label>
        <select name="time_casa_id" required>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>" <?php if ($partida['time_casa_id'] == $time['id']) echo 'selected'; ?>><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="time_fora_id">Time Fora:</label>
        <select name="time_fora_id" required>
            <?php $times->data_seek(0); while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>" <?php if ($partida['time_fora_id'] == $time['id']) echo 'selected'; ?>><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="data_jogo">Data do Jogo:</label>
        <input type="date" name="data_jogo" value="<?php echo $partida['data_jogo']; ?>" required>

        <label for="gols_casa">Gols Casa:</label>
        <input type="number" name="gols_casa" value="<?php echo $partida['gols_casa']; ?>" min="0" required>

        <label for="gols_fora">Gols Fora:</label>
        <input type="number" name="gols_fora" value="<?php echo $partida['gols_fora']; ?>" min="0" required>

        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
