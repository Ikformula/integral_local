<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VacancyMail extends Mailable
{
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

    public function build()
    {
        return $this->view('emails.vacancy');
    }
}
