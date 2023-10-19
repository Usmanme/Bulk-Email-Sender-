<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
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
        return $this->from('mani.yousaf98@gmail.com', $this->name)->subject($this->subject)->view('email.index')->with(['body'=>$this->body]);

        // $email = $this->from('mani.yousaf98@gmail.com')
        //     ->subject($this->data['subject'])->body($this->data['body'])
        //     ->view('email.index')
        //     ->with('data', $this->data);
        // dd($email);


        // return $email;
    }
    // public function build()
    // {
    //     return $this->from('mani.yousaf98@gmail.com', $this->data['subject'])->subject($this->data['subject'])->body($this->data['body'], $this->data['documents'])->view('email.index')->with('data', $this->data);
    // }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Mail Notify',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     // return new Content(
    //     //     view: 'view.name',
    //     // );
    // }

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
