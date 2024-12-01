<?php
session_start();
include('db.php');

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['id'])) {  // Используем $_SESSION['id'] для идентификатора пользователя
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];  // Используем $_SESSION['id']
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История заказов</title>
</head>
<body>
    <h1>История заказов</h1>

    <?php if (count($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_amount']); ?> ₽</td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас нет заказов.</p>
    <?php endif; ?>
</body>
</html>
