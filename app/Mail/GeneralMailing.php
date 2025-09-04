<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralMailing extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->data['to'])) {
            if (is_array($this->data['to'])) {
                $tos = [];
                foreach ($this->data['to'] as $to){
                    if(filter_var($to, FILTER_VALIDATE_EMAIL)){
                        $tos[] = $to;
                    }
                }

                $mail = $this->to($tos)
                    ->view('mail.mail-template')
                    ->subject($this->data['subject']);
            } else if(filter_var($this->data['to'], FILTER_VALIDATE_EMAIL)){
                $mail = $this->to(trim($this->data['to']), (isset($this->data['to_name']) ? $this->data['to_name'] : strstr($this->data['to'], '@', true)))
                    ->view('mail.mail-template')
                    ->subject($this->data['subject']);
            }
        }

// Set 'from' and 'from_name' using either the provided values or the default values from the configuration
        $from = isset($this->data['from']) ? $this->data['from'] : config('mail.from.address');
        $fromName = isset($this->data['from_name']) ? $this->data['from_name'] : config('mail.from.name');
        $mail = isset($mail) ? $mail->from($from, $fromName) : $this->view('mail.mail-template')->subject($this->data['subject'])->from($from, $fromName);

// Set 'cc' if provided
        if (isset($this->data['cc'])) {
            if(is_array($this->data['cc'])) {
                foreach ($this->data['cc'] as $cc) {
                    if(filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                        $mail = $mail->cc(trim($cc));
                    }
                }
            }else{
                if(filter_var($this->data['cc'], FILTER_VALIDATE_EMAIL)) {
                    $mail = $mail->cc(trim($this->data['cc']));
                }
            }
        }

        if (isset($this->data['bcc'])) {
            if(is_array($this->data['bcc'])) {
                foreach ($this->data['bcc'] as $bcc) {
                    if(filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
                        $mail = $mail->bcc(trim($bcc));
                    }
                }
            }else{
                if(filter_var($this->data['bcc'], FILTER_VALIDATE_EMAIL)) {
                    $mail = $mail->bcc(trim($this->data['bcc']));
                }
            }
        }

// Set additional headers or parameters here
// Example:
// $mail = $mail->header('X-MyHeader', 'my-value');
// $mail = $mail->attach($pathToFile);

// Set 'replyTo' using the default values from the configuration
        $mail = $mail->replyTo(config('mail.from.address'), config('mail.from.name'));

// Return the modified mail instance
        return $mail;

    }
}
