<?php
session_start();
require_once '../DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные
    $order_id = $_POST['order_id'] ?? '';
    $status = $_POST['status'] ?? '';

    // Подготавливаем и выполняем запрос на обновление
    $stmt = $DB->prepare("
        UPDATE orders 
        SET status = ?
        WHERE id = ?
    ");

    $result = $stmt->execute([
        $status,
        $order_id
    ]);

    if ($result) {
        // Если обновление успешно, перенаправляем обратно на страницу заказов
        header('Location: ../../orders.php');
        exit;
    } else {
        // Если произошла ошибка, сохраняем сообщение об ошибке и перенаправляем
        $_SESSION['orders_error'] = 'Ошибка при обновлении статуса заказа';
        header('Location: ../../orders.php');
        exit;
    }

} else {
    echo "Метод запроса должен быть POST";
} 