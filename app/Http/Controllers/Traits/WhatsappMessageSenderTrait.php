<?php


namespace App\Http\Controllers\Traits;


use Illuminate\Support\Facades\Log;

trait WhatsappMessageSenderTrait
{
    public function sendMessage($phone_number, $message)
    {
        $params = [
            'phone' => $phone_number,
            'message' => $message
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.wassenger.com/v1/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Token: ".config('app.wassenger.api_keys')
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
//                echo "cURL Error #:" . $err;
            Log::error("Wassenger to " . $phone_number . " | " . "cURL Error #:" . $err);
            return false;
        } else {
//                echo $response;
            $resp = json_decode($response, true);
            Log::info("Wassenger to " . $phone_number . " | " . $resp['message']);
            return true;
        }
    }
}
