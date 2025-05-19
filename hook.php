<?php
// session_start();
// 1) Ваш токен и базовый URL API
define('BOT_TOKEN', 'ВАШ_ТОКЕН_ОТ_BOTFATHER');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
// session_unset();
// session_destroy();
// 2) Читаем «сырое» тело POST и декодируем JSON
$update = json_decode(file_get_contents('php://input'), true);

// 3) Если это сообщение с текстом
if (isset($update['message']['text'])) {
    $chat_id = $update['message']['chat']['id'];
    $text    = $update['message']['text'];

    // 4) Определяем команду и готовим ответ
    switch (explode(' ', $text)[0]) {
        case '/start':
            $reply = "Привет! Я — ваш PHP-бот для Telegram.";
            break;
        case '/help':
            $reply = "Доступные команды:\n/start — приветствие\n/help — помощь";
            break;
        default:
            $reply = "Не понимаю команду. Напишите /help.";
    }
    // 5) Отправляем ответ методом GET
    file_get_contents(API_URL . "sendMessage?chat_id={$chat_id}&text=" . urlencode($reply));
}
