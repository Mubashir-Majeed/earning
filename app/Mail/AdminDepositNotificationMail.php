<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminDepositNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $packageName;
    public $transactionId;
    public $walletAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, float $amount, string $packageName, ?string $transactionId = null, ?string $walletAddress = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->packageName = $packageName;
        $this->transactionId = $transactionId;
        $this->walletAddress = $walletAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'earnquest82@gmail.com'), config('mail.from.name', 'EarnQuest')),
            subject: 'New Deposit Request - ' . $this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-deposit-notification',
        );
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
