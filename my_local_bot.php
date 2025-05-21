<?php
include "d_base/db_connection.php";
include "api_openai.php";
include "func.php";

define('BOT_TOKEN', '8091712566:AAE1wGuRPd8xvx7L1AYAIgxaTq7Hi-5DyBM');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


    

    $update = json_decode(file_get_contents('php://input'), true);
    if (!isset($update['message']['text'])) {
        exit;
    }
   
            $chat_id = $update['message']['chat']['id'];
            $text = $update['message']['text'] ?? '';

            $waiting = [];
            
            if (!empty($waiting[$chat_id]) && mb_substr($text, 0, 1) !== '/') {
                // обрабатываем как название книги
                 if ($waiting[$chat_id] == 'book') {

                       $reply = getBook($conn, $text);
                        botMessage($chat_id, $reply);
                            // предлагаем ещё или ждём /stop 
                        botMessage($chat_id, "Enter book title or /stop");
          

                 }elseif($waiting[$chat_id] == 'author'){
                        $reply = getAuthor($conn, $text);
                        botMessage($chat_id, $reply);
                            // предлагаем ещё или ждём /stop
                        botMessage($chat_id, "Enter author name or /stop");
               

                 }elseif($waiting[$chat_id] == 'assistant'){
                        $reply = AI_assistant($openai_api_key, $text);
                        botMessage($chat_id, $reply);
                        // предлагаем ещё или ждём /stop
                        botMessage($chat_id, "Enter question to assistant or /stop");
         
                }
            }
            


              switch ($text) {
                case '/start':
                    $reply = "Привет! Я — ваш PHP-бот для Telegram.";
                    botMessage($chat_id, $reply);
                    break;
                case '/help':
                    $reply = "Available commands:\n/start — приветствие\n/help — help\n/ai - Ask AI assistant\n/search_by_book  — Ask about book in Library\n/search_by_author  — Ask about author in Library\n/stop  — Stop action" ;
                    botMessage($chat_id, $reply);
                    break;
                case '/stop':
                    unset($waiting[$chat_id]);
                    $reply = "Stoped.";
                    botMessage($chat_id, $reply);
                    break;
                case '/ai':
                    $waiting[$chat_id] = 'assistant';
                    $reply = 'Enter question or /stop';
                    botMessage($chat_id, $reply);
                    break;
                case '/search_by_book':
                    $waiting[$chat_id] = 'book';
                    $reply = "Enter book title or /stop";
                    botMessage($chat_id, $reply);
                    break;
                case '/search_by_author':
                    $waiting[$chat_id] = 'author';
                    $reply = "Enter author name or /stop";
                    botMessage($chat_id, $reply);
                    break;
                default:
                    $reply = "Do not understand command. Write /help.";
                    botMessage($chat_id, $reply);
            }


   

    // echo "<pre>";
    // print_r($updates); 
    // echo "</pre>";



