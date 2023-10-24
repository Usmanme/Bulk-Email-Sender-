<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '';
    public $name = '';
    public $body = '';
    public $doc = [];

    /**
     * Create a new message instance.
     */
    public function __construct($subject,$body, $name)
    {

        $this->name = $name;
        $this->subject = $subject;
        $this->body = $body;
    }
    public function build()
    {
        return $this->from('mani.yousaf98@gmail.com', $this->name)->subject($this->subject)->view('app.email.email-index')->with(['body'=>$this->body]);

    }



    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
