<?php
session_start();
require_once '../DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные
    $product_id = $_POST['product_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    // Подготавливаем и выполняем запрос на обновление
    $stmt = $DB->prepare("
        UPDATE products 
        SET name = ?, 
            description = ?, 
            price = ?,
            stock = ?
        WHERE id = ?
    ");

    $result = $stmt->execute([
        $name,
        $description,
        $price,
        $quantity,
        $product_id
    ]);

    if ($result) {
        // Если обновление успешно, перенаправляем обратно на страницу товаров
        header('Location: ../../product.php');
        exit;
    } else {
        // Если произошла ошибка, сохраняем сообщение об ошибке и перенаправляем
        $_SESSION['error'] = 'Ошибка при обновлении данных товара';
        header('Location: ../../product.php');
        exit;
    }

} else {
    echo "Метод запроса должен быть POST";
}