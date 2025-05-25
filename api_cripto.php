<?php 
include 'key.php';


function getCripto($crypto_api_key){


        $url = 'https://api.cryptorank.io/v2/currencies/';

        $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'X-Api-Key:' .$crypto_api_key
                ],
                CURLOPT_TIMEOUT => 30,
            ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            return;
        }else{
            $data = json_decode($response, true);
            $count = 0;
            // for ($i=0; $i < 2 ; $i++) {  
            //     $lines[] = sprintf("%s - %f", $data['data'][$i]['symbol'],  $data['data'][$i]['price'],);
            // }

            foreach ($data['data'] as $coin) {
                $lines[] = sprintf("%s - %0.4f $", $coin['symbol'],  $coin['price']);
                $count++;
                if ($count == 10) {
                    break;
                }
            }
            return implode("\n",$lines) ;
            //return $data['data'][0]['symbol']." - ". $data['data'][0]['price']."<br>". $data['data'][1]['symbol']." - ". $data['data'][1]['price'];
        }
        curl_close($ch);
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';


        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON decode error: ' . json_last_error_msg();
            return;
        }
}

//getCripto($crypto_api_key);
?>
   <!-- <img src="<?php // echo $data['data'][$i]['images']['icon'] ?>" alt=""> -->