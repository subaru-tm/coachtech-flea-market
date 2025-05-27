<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailSendController extends Controller
{
    public function index() {
        $data = [];

        Mail::send('emails.welcome', $data, function($message){
            $message->to('abc@example.com', 'Test')
            ->subject('This is a test mail');
        });
    }
}
