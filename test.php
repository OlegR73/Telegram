<?php 
define('BOT_TOKEN', '8196569644:AAGRQG5NZhSquAI5vtRVkbkD5-QfHhnBRqg');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

$response = file_get_contents(API_URL . "setWebhook?url=" . urlencode('https://trbot.eu/hook.php'));
file_put_contents(__DIR__.'/hook.log', date('c')." setWebhook response: ".$response.PHP_EOL, FILE_APPEND);
echo $response;
echo file_get_contents(API_URL . 'getWebhookInfo');


?>