<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;



class MailableMailtrap extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $dealing_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $dealing_id)
    {
        $this->name = $name;
        $this->dealing_id = $dealing_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引完了のお知らせ')
            ->view('mail.dealing-complete')
            ->with([
                'name' => $this->name,
                'dealing_id' => $this->dealing_id,
            ]);
    }

    /**
     * メッセージのEnvelopeを取得
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            $this->subject('取引完了のお知らせ'),
        );
    }

    /**
     * メッセージコンテンツの定義を取得
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            $this->view('mail.dealing-complete')
                 ->with(['name' => $this->name]),
        );
    }

    /**
     * メッセージの添付ファイルを取得
     * 
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
