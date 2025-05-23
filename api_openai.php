<?php 
require __DIR__ . '/init.php';
include "key.php";
//echo $openai_api_key;
$question = 'WHAT IS CRIPTOCURRENCY?';
function AI_assistant($openai_api_key, $question){
    $prompt = 'You are a helpful assistant.';
    if (!isset($_SESSION['chat_history']) ) {
        $_SESSION['chat_history'] = [
            ['role' => 'system', 'content' => $prompt]
        ];
    }

    $_SESSION['chat_history'][] = ['role' => 'user', 'content' => $question]; 

    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => $_SESSION['chat_history'],
        'max_tokens' => 350,
        'temperature' => 0.7
    ];


    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $openai_api_key
        ],
        CURLOPT_POSTFIELDS     => json_encode($data),
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'cURL error: ' . curl_error($ch);
        curl_close($ch);
        return;
    }
    curl_close($ch);

    $json = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'JSON decode error: ' . json_last_error_msg();
        return;
    }
    if (isset($json['choices'][0]['message']['content']) ) {
        $assistantAnswer = $json['choices'][0]['message']['content'];
        $_SESSION['chat_history'][] = [
            'role' => 'assistant',
            'content' => $assistantAnswer
        ];
        return $assistantAnswer;
    }else{
        return 'No answer';
    }
}

//echo AI_assistant($openai_api_key, $question);


//  Webhook  hook.php
// curl "https://api.telegram.org/bot8091712566:AAE1wGuRPd8xvx7L1AYAIgxaTq7Hi-5DyBM/setWebhook?url=https://trbot.eu/Telegram/hook.php"
// https://trbot.eu/

?>