<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserEmailUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\UserEmailUpdate
     */
    public $emailUpdate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\UserEmailUpdate $emailUpdate)
    {
        $this->emailUpdate = $emailUpdate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->emailUpdate->user()->first();

        return $this->markdown('emails.user.email-update', [
            'name' => $user->first_name . ' ' . $user->last_name,
            'old_email' => $user->email,
            'new_email' => $this->emailUpdate->new_email,
            'verification_url' => config('lapi.url_front_user_email_update') . '?token=' . $this->emailUpdate->token
        ])->subject('[' . config('app.name') . '] Confirme a atualização de email da sua conta');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: '[' . config('app.name') . '] Confirme a atualização de email da sua conta',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}