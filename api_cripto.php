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
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            foreach ($data['data'] as $coin) {
                $change   = $coin['ath']['percentChange'];
                $high24h = $coin['high24h'];
                $low24h = $coin['low24h'];

                if ($coin['price'] < $coin['low24h']) {
                    $arrow = "🔻";
                }else {
                    $arrow = "🔺";
                }
                $lines[] = sprintf("%s - %0.4f $, %0.4f, %s", $coin['symbol'],  $coin['price'], $arrow);
                $count++;
                if ($count == 10) {
                    break;
                }
            }
            return implode("\n",$lines) ;
            //return $data['data'][0]['symbol']." - ". $data['data'][0]['price']."<br>". $data['data'][1]['symbol']." - ". $data['data'][1]['price'];
        }
        curl_close($ch);



        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON decode error: ' . json_last_error_msg();
            return;
        }
}
// $price    = number_format($coin['price'], 2, '.', ',');
// $arrow = $change < 0 ? "🔻" : "🔺";
// $change   = $coin['ath']['percentChange'];
getCripto($crypto_api_key);
?>
   <!-- <img src="<?php // echo $data['data'][$i]['images']['icon'] ?>" alt=""> -->