<?php

namespace App\Http\Controllers;

//use App\Models\OutgoingMessage;
use App\Models\OutgoingMessageRecipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\GeneralMailing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Traits\WhatsappMessageSenderTrait;


class OutboundMessagesController extends Controller
{
    use WhatsappMessageSenderTrait;

    public $takes = 20;

    public function attempts(Request $request){
        $validated = $request->validate([
            'attempts' => 'sometimes|numeric|max:3|min:0'
        ]);

        if($request->filled('attempts')){
            unset($attempts);
            return [$request->attempts];
        }

        return ['<', 3];
    }

    public function emailWorker(Request $request)
    {
        $attempts = $this->attempts($request);

        $recs = OutgoingMessageRecipient::whereNull('email_sent_at')
            ->where('attempts', $attempts)
            ->take($this->takes)
            ->get();

        $now = Carbon::now();

        foreach($recs as $rec){
            $data = json_decode($rec->message->payload, true);

            if(isset($data['to'])) {
                if (!is_array($data['to']) && !is_null($rec->user_id)) {
//                if (!isset($data['to']) || !filter_var($data['to'], FILTER_VALIDATE_EMAIL)) {
//                    $data['to'] = config('mail.admin_email');
//                }
                    if (!isset($data['to_name'])) {
                        $data['to_name'] = isset($data['to']) ? strstr($data['to'], '@', true) : '';
                    }
                } else if (!is_array($data['to']) && $rec->canSendToEmail() && !isset($data['to']) && isset($rec->user) && filter_var($rec->user->email, FILTER_VALIDATE_EMAIL)) {
                    $data['to'] = $rec->user->email;
                    $data['to_name'] = $rec->user->name;
                }
            }

//            if(Mail::send(new GeneralMailing($data))) {
            Mail::send(new GeneralMailing($data));
            $rec->email_sent_at = $now;
            $rec->attempts += 1;
            $rec->save();

            echo $rec->id . ' - ' . $rec->email_sent_at.'<br>';
//            }else{
//                echo $rec->id . ' - Not yet sent as at ' . $now;
//            }
        }

        die();
    }

    public function whatsappWorker(Request $request)
    {
        $attempts = $this->attempts($request);
        $recs = OutgoingMessageRecipient::where('whatsapp_sent_at', null)
            ->where('user_id', '!=', null)
            ->where('wa_attempts', $attempts)
            ->take($this->takes)
            ->get();

        $now = Carbon::now();
        foreach($recs as $rec){
            $data = json_decode($rec->message->payload, true);
            $message = '';
            if(isset($data['subject']))
                $message .= $data['subject'].PHP_EOL;

            if(isset($data['greeting']))
                $message .= PHP_EOL.$data['greeting'].PHP_EOL;

            foreach ($data['line'] as $line){
                $message .= $line.PHP_EOL;
            }

            if(isset($data['action_url'])) {
                $message .= PHP_EOL.$data['action_url'];
            }

            if($rec->canSendToWhatsapp()){
                if($this->sendMessage($rec->user->phone_number, $message)){
                    $rec->whatsapp_sent_at = $now;
                }
            }else{
                Log::error("Wassenger to " . $rec->user->phone_number . ", outbound_messages:id = ".$rec->id." | Cannot send to Whatsapp");
            }
            $rec->wa_attempts += 1;
            $rec->save();
        }

        die();

    }
}
