<?php
include '../includes/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$filter_nome = isset($_GET['nome']) ? $_GET['nome'] : '';
$filter_posicao = isset($_GET['posicao']) ? $_GET['posicao'] : '';
$filter_time = isset($_GET['time_id']) ? $_GET['time_id'] : '';

$query = "SELECT jogadores.*, times.nome as time_nome FROM jogadores LEFT JOIN times ON jogadores.time_id = times.id WHERE jogadores.nome LIKE '%$filter_nome%' AND jogadores.posicao LIKE '%$filter_posicao%' AND (jogadores.time_id = '$filter_time' OR '$filter_time' = '') LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM jogadores LEFT JOIN times ON jogadores.time_id = times.id WHERE jogadores.nome LIKE '%$filter_nome%' AND jogadores.posicao LIKE '%$filter_posicao%' AND (jogadores.time_id = '$filter_time' OR '$filter_time' = '')";
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
    <title>Jogadores</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Jogadores</h1>
    <a href="../index.php">Voltar</a>
    <a href="create.php">Adicionar Jogador</a>

    <form method="GET">
        <label for="nome">Filtrar por Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($filter_nome); ?>">

        <label for="posicao">Filtrar por Posição:</label>
        <select name="posicao">
            <option value="">Todas</option>
            <option value="GOL" <?php if ($filter_posicao == 'GOL') echo 'selected'; ?>>GOL</option>
            <option value="ZAG" <?php if ($filter_posicao == 'ZAG') echo 'selected'; ?>>ZAG</option>
            <option value="LAT" <?php if ($filter_posicao == 'LAT') echo 'selected'; ?>>LAT</option>
            <option value="MEI" <?php if ($filter_posicao == 'MEI') echo 'selected'; ?>>MEI</option>
            <option value="ATA" <?php if ($filter_posicao == 'ATA') echo 'selected'; ?>>ATA</option>
        </select>

        <label for="time_id">Filtrar por Time:</label>
        <select name="time_id">
            <option value="">Todos</option>
            <?php while ($time = $times->fetch_assoc()): ?>
                <option value="<?php echo $time['id']; ?>" <?php if ($filter_time == $time['id']) echo 'selected'; ?>><?php echo htmlspecialchars($time['nome']); ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Posição</th>
            <th>Número da Camisa</th>
            <th>Time</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['nome']); ?></td>
            <td><?php echo htmlspecialchars($row['posicao']); ?></td>
            <td><?php echo $row['numero_camisa']; ?></td>
            <td><?php echo htmlspecialchars($row['time_nome']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Editar</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&nome=<?php echo urlencode($filter_nome); ?>&posicao=<?php echo urlencode($filter_posicao); ?>&time_id=<?php echo urlencode($filter_time); ?>">Anterior</a>
        <?php endif; ?>
        Página <?php echo $page; ?> de <?php echo $total_pages; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&nome=<?php echo urlencode($filter_nome); ?>&posicao=<?php echo urlencode($filter_posicao); ?>&time_id=<?php echo urlencode($filter_time); ?>">Próxima</a>
        <?php endif; ?>
    </div>
</body>
</html>
