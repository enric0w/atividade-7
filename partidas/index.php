<?php
include '../includes/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$filter_time = isset($_GET['time_id']) ? $_GET['time_id'] : '';
$filter_data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : '';
$filter_data_final = isset($_GET['data_final']) ? $_GET['data_final'] : '';
$filter_resultado = isset($_GET['resultado']) ? $_GET['resultado'] : '';

$where = "1=1";
if ($filter_time) {
    $where .= " AND (time_casa_id = '$filter_time' OR time_fora_id = '$filter_time')";
}
if ($filter_data_inicial) {
    $where .= " AND data_jogo >= '$filter_data_inicial'";
}
if ($filter_data_final) {
    $where .= " AND data_jogo <= '$filter_data_final'";
}
if ($filter_resultado) {
    if ($filter_resultado == 'casa') {
        $where .= " AND gols_casa > gols_fora";
    } elseif ($filter_resultado == 'fora') {
        $where .= " AND gols_casa < gols_fora";
    } elseif ($filter_resultado == 'empate') {
        $where .= " AND gols_casa = gols_fora";
    }
}

$query = "SELECT partidas.*, t1.nome as time_casa, t2.nome as time_fora FROM partidas LEFT JOIN times t1 ON partidas.time_casa_id = t1.id LEFT JOIN times t2 ON partidas.time_fora_id = t2.id WHERE $where LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM partidas WHERE $where";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

$times = $conn->query("SELECT id, nome FROM times");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Partidas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Partidas</h1>
    <a href="../index.php">Voltar</a>
    <a href="create.php">Adicionar Partida</a>

    <form method="GET">
        <label for="time_id">Filtrar por Time:</label>
        <select name="time_id">
            <option value="">Todos</option>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>" <?php if ($filter_time == $time['id']) echo 'selected'; ?>><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="data_inicial">Data Inicial:</label>
        <input type="date" name="data_inicial" value="<?php echo htmlspecialchars($filter_data_inicial); ?>">

        <label for="data_final">Data Final:</label>
        <input type="date" name="data_final" value="<?php echo htmlspecialchars($filter_data_final); ?>">

        <label for="resultado">Resultado:</label>
        <select name="resultado">
            <option value="">Todos</option>
            <option value="casa" <?php if ($filter_resultado == 'casa') echo 'selected'; ?>>Vitória Casa</option>
            <option value="empate" <?php if ($filter_resultado == 'empate') echo 'selected'; ?>>Empate</option>
            <option value="fora" <?php if ($filter_resultado == 'fora') echo 'selected'; ?>>Vitória Fora</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Time Casa</th>
            <th>Time Fora</th>
            <th>Data</th>
            <th>Placar</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['time_casa']); ?></td>
            <td><?php echo htmlspecialchars($row['time_fora']); ?></td>
            <td><?php echo $row['data_jogo']; ?></td>
            <td><?php echo $row['gols_casa']; ?> - <?php echo $row['gols_fora']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Editar</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&time_id=<?php echo urlencode($filter_time); ?>&data_inicial=<?php echo urlencode($filter_data_inicial); ?>&data_final=<?php echo urlencode($filter_data_final); ?>&resultado=<?php echo urlencode($filter_resultado); ?>">Anterior</a>
        <?php endif; ?>
        Página <?php echo $page; ?> de <?php echo $total_pages; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&time_id=<?php echo urlencode($filter_time); ?>&data_inicial=<?php echo urlencode($filter_data_inicial); ?>&data_final=<?php echo urlencode($filter_data_final); ?>&resultado=<?php echo urlencode($filter_resultado); ?>">Próxima</a>
        <?php endif; ?>
    </div>
</body>
</html>
