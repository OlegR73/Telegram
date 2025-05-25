<?php
include __DIR__ . '/init.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');


include "d_base/db_connection.php";
include "api_openai.php";
include "api_cripto.php";
include "func.php";
include "d_base/db_func.php";

define('BOT_TOKEN', '8196569644:AAGRQG5NZhSquAI5vtRVkbkD5-QfHhnBRqg');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

$payload = file_get_contents('php://input');

//logging
file_put_contents(__DIR__.'/hook.log', date('c')."  ".$payload.PHP_EOL, FILE_APPEND);
    
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = json_decode($payload, true);
    if (!isset($update['message']['text'])) {
        exit;
    }
   
            $chat_id = $update['message']['chat']['id'];
            $text = $update['message']['text'] ?? '';

            // 2. get current state
            $currentState = getState($conn, $chat_id);
      
            if ($currentState !== null && mb_substr($text, 0, 1) !== '/') {

                 if ($currentState == 'book') {

                       $reply = getBook($conn, $text);
                        botMessage(API_URL, $chat_id, $reply);
                            // wait next question or/stop
                        botMessage(API_URL, $chat_id, "Enter book title or /stop");
          

                 }elseif($currentState == 'author'){
                        $reply = getAuthor($conn, $text);
                        botMessage(API_URL, $chat_id, $reply);
                            // wait next question or/stop
                        botMessage(API_URL, $chat_id, "Enter author name or /stop");
                 }elseif($currentState == 'assistant'){
                        $reply = AI_assistant($openai_api_key, $text);
                        botMessage(API_URL, $chat_id, $reply);
                        // wait next question or/stop
                        botMessage(API_URL, $chat_id, "Enter question to assistant or /stop");         
                }elseif($currentState == 'cripto'){
                        // botMessage(API_URL, $chat_id, $reply);
                        //  wait next question or/stop
                        // botMessage(API_URL, $chat_id, "Enter question to assistant or /stop");
                }
                http_response_code(200);
                exit;
            }

            switch ($text) {
                case '/start':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'start');
                    $reply = "HELLO, ". $username ."! I am — your PHP-bot for Telegram.";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/help':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'help');
                    $reply = "Available commands:\n/start — HELLO, ". $username ."\n/help — help\n/ai - Ask AI assistant\n/search_by_book  — Ask about book in Library\n/search_by_author  — Ask about author in Library\n/stop  — Stop action" ;
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/stop':                                      
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'stop');
                    unset($_SESSION['chat_history']);
                    clearState($conn, $chat_id);
                    $reply = "Stoped.";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/ai':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'ai');
                    setState($conn, $chat_id, 'assistant');
                    $reply = 'Enter question or /stop';
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/cripto':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'cripto');
                    //setState($conn, $chat_id, 'cripto');
                    $reply = getCripto($crypto_api_key);
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/search_by_book':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'books');
                    setState($conn, $chat_id, 'book');
                    $reply = "Enter book title or /stop";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                case '/search_by_author':
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'authors');
                    setState($conn, $chat_id, 'author');
                    $reply = "Enter author name or /stop";
                    botMessage(API_URL, $chat_id, $reply);
                    break;
                default:                                    
                    $username = $update['message']['from']['first_name'];
                    insertVisitor($conn, $chat_id, $username, 'default');
                    $reply = "Do not understand command. Write /help.";
                    botMessage(API_URL, $chat_id, $reply);
            }

//}


http_response_code(200);

?>