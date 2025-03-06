<?php
// Установка заголовка Content-Type
header('Content-Type: text/html; charset=utf-8');

// Подключение к базе данных
$host = '127.0.0.1';
$dbname = 'crm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Функция для получения имени пользователя по email
function getUserNameByEmail($email, $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM clients WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['name'] ?? 'Гость';
}

// Проверяем существование файла изображения
$imagePath = '../../img/hoh.jpg';
$backgroundImage = file_exists($imagePath) ? 'url("' . $imagePath . '")' : 'none';

// Почта пользователя
$email = $_GET['email'] ?? '';

// Получение имени пользователя из БД, если оно не передано через POST
$userName = $_POST['userName'] ?? getUserNameByEmail($email, $pdo);

// Элементы страницы
$header = $_POST['header'] ?? 'Дорогие коллеги!';
$main = $_POST['main'] ?? 'Описание отсутствует';
$footer = $_POST['footer'] ?? 'СИБИРЬ БЛИЖЕ, ЧЕМ ВЫ ДУМАЕТЕ...';
$footerInfo = 'СИБИРЬ БЛИЖЕ, ЧЕМ ВЫ ДУМАЕТЕ...';

// HTML-контент (создан до try)
$html = "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='utf-8'>
    <title>Сибирский гостинец</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            background-image: $backgroundImage !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(249, 249, 249, 0.95);
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #4a2c2c;
        }
        .content {
            line-height: 1.6;
            color: #333;
            text-align: center;
            max-width: 80%;
            margin: 0 auto;
            padding: 20px 0;
        }
        .content p {
            text-align: center;
            margin-bottom: 15px;
        }
        .content h2 {
            text-align: center;
        }
        .contact-info {
            margin-top: 30px;
            color: #666;
            text-align: right;
            padding-right: 0;
        }
        .contact-info p {
            margin: 5px 0;
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #333;
        }
        h2 {
            color: #4a2c2c;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        p {
            margin-bottom: 15px;
        }
        .form-container {
            margin-top: 30px;
            text-align: center;
        }
        .form-container input {
            width: 80%;
            padding: 10px;
            margin: 5px 0;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #4a2c2c;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #333;
        }
        .header {
            font-size: 24px;
            color: #4a2c2c;
            text-align: center;
            margin-bottom: 30px;
        }
        .main {
            line-height: 1.6;
            color: #333;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='logo'>
            <img src='../../img/1.jpg' alt='Сибирские Сладости' style='max-width: 100%; height: auto;'>
            <h1>Сибирский</h1>
            <h1>гостинец</h1>
        </div>
        
        <div class='header'><?php echo $header; ?></div>
    
        <div class='content'>
            <h2>Дорогие коллеги!</h2>
            <p>Компания «Сибирский гостинец» — это российский производитель натуральных продуктов из экологически чистого сырья. Мы перерабатываем и реализуем дикорастущие лесные ягоды с применением инновационных технологий сублимации, а также выпускаем снековую продукцию (кедровый орех и сушеные грибы).</p>
            <p>Мы работаем с 2012 года, но уже наладили взаимовыгодные партнёрские отношения с крупными российскими торговыми сетями: «Лента», «Ашан», «Магнит», «Звездный», «Линия», «Глобус» и другие. Нас ценят за высокое качество продукта и строгое соблюдение сроков. А мы ценим своих партнёров и всегда рады новым!</p>
            
        </div>
        <div class='form-container'>
            <h2>Отправка письма </h2>
            <form method='POST'>
                <input type='text' name='recipientEmail' placeholder='Кому отправляем?' required>
                <input type='text' name='emailSubject' placeholder='Тема сообщения' required>
                <button type='submit'>ПОСЛАТЬ ПИСЬМО</button>
            </form>
        </div>
        <div class='contact-info'>
            <p>(3462) 77-40-59</p>
            <p>info@sg-trade.ru</p>
            <p>628406, РФ, ХМАО-Югра,</p>
            <p>г. Сургут, ул. Университетская, 4</p>
        </div>
        <div class='footer'><?php echo $footerInfo; ?></div>
    </div>
</body>
</html>";

// Подключение PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Автозагрузка PHPMailer

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.mail.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'dima.haunov@mail.ru';
    $mail->Password = 'ikW5x1urvtS6bnm7afNp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Почта отправителя
    $mail->setFrom('jirnoffkirill@yandex.ru', 'Kirill');
    // Почта получателя
    $mail->addAddress('matviei.maksimov@bk.ru', 'Matviei Maksimov');
    $mail->isHTML(true);
    $mail->Subject = 'Сообщение';
    $mail->CharSet = 'UTF-8';

    $mail->Body = $html;
    $mail->send();

    echo "Письмо отправлено!";
} catch (Exception $e) {
    echo "Ошибка отправки письма: {$mail->ErrorInfo}";
}

// Отображение HTML-кода в браузере
echo $html;
?>