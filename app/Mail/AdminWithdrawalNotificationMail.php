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

class AdminWithdrawalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $netAmount;
    public $feeAmount;
    public $walletAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, float $amount, float $netAmount, float $feeAmount, ?string $walletAddress = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->netAmount = $netAmount;
        $this->feeAmount = $feeAmount;
        $this->walletAddress = $walletAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'earnquest82@gmail.com'), config('mail.from.name', 'EarnQuest')),
            subject: 'New Withdrawal Request - ' . $this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-withdrawal-notification',
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
