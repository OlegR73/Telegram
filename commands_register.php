<?php 
define('BOT_TOKEN', '8196569644:AAGRQG5NZhSquAI5vtRVkbkD5-QfHhnBRqg');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

$commands = [
    ['command' => 'start',  'description' => 'Welcome'],
    ['command' => 'help',   'description' => 'help'],
    ['command' => 'ai',     'description' => 'Ask AI assistant'],
    ['command' => 'search_by_book',   'description' => 'Ask about book in Library'],
    ['command' => 'search_by_author', 'description' => 'Ask about author in Library'],
    ['command' => 'stop',   'description' => 'Stop actions'],
];

$url = API_URL . 'setMyCommands?commands=' . urlencode(json_encode($commands));
$result = file_get_contents($url);
echo $result;

// $info = file_get_contents(API_URL . 'getMe');
// header('Content-Type: application/json');
// echo $info;


?>