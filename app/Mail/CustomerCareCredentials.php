<?php
// app/Mail/CustomerCareCredentials.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerCareCredentials extends Mailable
{
    use Queueable, SerializesModels;

    // public so the emails teplate can access them
    public $name;
    public $email;
    public $password;
    public $loginUrl;

    // constructor receives the data
    public function __construct($name, $email, $password, $loginUrl)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Credify Bank - Customer Care Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-care-credentials',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}