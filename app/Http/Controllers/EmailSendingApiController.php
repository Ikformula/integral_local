<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;
use Illuminate\Support\Facades\Log;


class EmailSendingApiController extends Controller
{
    use OutgoingMessagesTrait;

    public function storeEmailRequest(Request $request){

        $validated = $request->validate([
            'payload' => ['required']
        ]);

        $data = json_decode($request->payload, true);
        Log::debug('Email fired from '.$_SERVER['REMOTE_ADDR']. ' to '.$data['to']);

        $allowed_IPs = [
            '35.187.65.170',
            '::1'
        ];
        $domain = strtolower(explode('@', $data['to'])[1]);
        if($domain != 'arikair.com'){
            return [
              'status' => 'failed',
              'message' => 'email domain not allowed'
            ];
        }

        // if(!in_array($_SERVER['REMOTE_ADDR'], $allowed_IPs)){
        //     return [
        //       'status' => 'failed',
        //       'message' => 'request IP address not authorized'
        //     ];
        // }
        if (isset($data['to']) && !filter_var($data['to'], FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'failed',
                'message' => 'Invalid email address'
            ];
        }

        if(isset($data['subject']) && isset($data['line']) && isset($data['to'])) {
            $this->storeMessage($data, null);
            return [
                'status' => 'successful',
                'message' => 'Email queued for sending'
            ];
        }else{
            return [
                'status' => 'failed',
                'message' => 'Incomplete data sent'
            ];
        }

    }

}
