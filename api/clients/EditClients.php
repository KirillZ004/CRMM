<?php
session_start();
require_once '../DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем иl очищаем данные
    $client_id = $_POST['client_id'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $birthday = $_POST['birthday'] ?? '';

    // Подготавливаем и выполняем запрос на обновление
    $stmt = $DB->prepare("
        UPDATE clients 
        SET name = ?, 
            email = ?, 
            phone = ?,
            birthday = ?
        WHERE id = ?
    ");

    $result = $stmt->execute([
        $fullname,
        $email,
        $phone,
        $birthday,
        $client_id
    ]);

    if ($result) {
        // Если обновление успешно, перенаправляем обратно на страницу клиентов
        header('Location: ../../clients.php');
        exit;
    } else {
        // Если произошла ошибка, сохраняем сообщение об ошибке и перенаправляем
        $_SESSION['error'] = 'Ошибка при обновлении данных клиента';
        header('Location: ../../clients.php');
        exit;
    }

} else {
    echo "Метод запроса должен быть POST";
} 