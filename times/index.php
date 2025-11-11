<?php
include '../includes/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$filter_nome = isset($_GET['nome']) ? $_GET['nome'] : '';

$query = "SELECT * FROM times WHERE nome LIKE '%$filter_nome%' LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM times WHERE nome LIKE '%$filter_nome%'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total = $total_row['total'];
$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Times</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Times</h1>
    <a href="../index.php">Voltar</a>
    <a href="create.php">Adicionar Time</a>

    <form method="GET">
        <label for="nome">Filtrar por Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($filter_nome); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cidade</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['nome']); ?></td>
            <td><?php echo htmlspecialchars($row['cidade']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Editar</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&nome=<?php echo urlencode($filter_nome); ?>">Anterior</a>
        <?php endif; ?>
        Página <?php echo $page; ?> de <?php echo $total_pages; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&nome=<?php echo urlencode($filter_nome); ?>">Próxima</a>
        <?php endif; ?>
    </div>
</body>
</html>
