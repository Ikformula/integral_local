<?php


namespace App\Http\Controllers\Traits;


use App\Models\OutgoingMessage;
use App\Models\OutgoingMessageRecipient;

trait OutgoingMessagesTrait
{
    public function storeMessage($message_data, $user_id, $return_object = false)
    {
        $message_json = json_encode($message_data);
        $message = new OutgoingMessage();
        $message->payload = $message_json;
        $message->save();

        $message_recipient = new OutgoingMessageRecipient();
        $message_recipient->user_id = $user_id;
        // null user_id means it should send to the admin's email ** Doesn't Apply anymore: 21/08/2023
        // provided in the env
        $message_recipient->message_id = $message->id;
        $message_recipient->save();

        if($return_object)
        return $message_recipient;

        return true;
    }
}
