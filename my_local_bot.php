<?php
include "d_base/db_connection.php";
include "func.php";

define('BOT_TOKEN', '8091712566:AAE1wGuRPd8xvx7L1AYAIgxaTq7Hi-5DyBM');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


file_get_contents(API_URL . "deleteWebhook?drop_pending_updates=true");

    $commands = [
        ['command'     => 'start', 'description' => 'Приветствие'],
        ['command'     => 'help',  'description' => 'Помощь'],
        ['command'     => 'ai',  'description' => 'Ask AI assistant'],
        ['command'     => 'search_by_book',  'description' => 'Ask about book in Library'],
        ['command'     => 'search_by_author',  'description' => 'Ask about author in Library'],
        ['command'     => 'stop',  'description' => 'Stop action'],
    ];
    file_get_contents(API_URL. "setMyCommands?commands=" . urlencode(json_encode($commands)));

    $offset = 0;
    $waiting = [];

while (true) {
    $response = file_get_contents(API_URL . "getUpdates?offset={$offset}&timeout=10");
    $updates = json_decode($response, true);

    if (!empty($updates['result'])) {
        foreach ($updates['result'] as $update) {
            $offset = $update['update_id'] + 1;
            $chat_id = $update['message']['chat']['id'];
            $text = $update['message']['text'] ?? '';

            
          

            if (!empty($waiting[$chat_id]) && mb_substr($text, 0, 1) !== '/') {
                // обрабатываем как название книги
                 if ($waiting[$chat_id] == 'book') {

                       $reply = getBook($conn, $text);
                        botMessage(API_URL, $chat_id, $reply);
                            // предлагаем ещё или ждём /stop 
                        botMessage(API_URL, $chat_id, "Enter book title or /stop");
                        continue;

                 }elseif($waiting[$chat_id] == 'author'){
                        $reply = getAuthor($conn, $text);
                        botMessage(API_URL, $chat_id, $reply);
                            // предлагаем ещё или ждём /stop
                        botMessage(API_URL, $chat_id, "Enter author name or /stop");
                        continue;

                 }elseif($waiting[$chat_id] == 'assistant'){
                        $reply = AI_assistant($text);

                        botMessage(API_URL, $chat_id, $reply);
                        // предлагаем ещё или ждём /stop
                        botMessage(API_URL, $chat_id, "Enter question to assistant or /stop");
                        continue;
                }
            }
            


              switch ($text) {
                case '/start':
                    $reply = "Привет! Я — ваш PHP-бот для Telegram.";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/help':
                    $reply = "Available commands:\n/start — приветствие\n/help — help\n/ai - Ask AI assistant\n/search_by_book  — Ask about book in Library\n/search_by_author  — Ask about author in Library\n/stop  — Stop action" ;
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/stop':
                    unset($waiting[$chat_id]);
                    $reply = "Stoped.";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/ai':
                    $waiting[$chat_id] = 'assistant';
                    $reply = 'Enter question or /stop';
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/search_by_book':
                    $waiting[$chat_id] = 'book';
                    $reply = "Enter book title or /stop";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/search_by_author':
                    $waiting[$chat_id] = 'author';
                    $reply = "Enter author name or /stop";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                default:
                    $reply = "Do not understand command. Write /help.";
                    botMessage(API_URL, $chat_id, $reply);
            }

        }
   
    }
    // echo "<pre>";
    // print_r($updates); 
    // echo "</pre>";
    sleep(1);

}
