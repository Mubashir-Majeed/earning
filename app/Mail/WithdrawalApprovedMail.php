<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $amount;
    public $netAmount;
    public $feeAmount;
    public $walletAddress;
    public $transactionId;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, float $amount, float $netAmount, float $feeAmount, ?string $walletAddress = null, ?string $transactionId = null)
    {
        $this->userName = $userName;
        $this->amount = $amount;
        $this->netAmount = $netAmount;
        $this->feeAmount = $feeAmount;
        $this->walletAddress = $walletAddress;
        $this->transactionId = $transactionId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'earnquest82@gmail.com'), config('mail.from.name', 'EarnQuest')),
            subject: 'Withdrawal Approved - Earn Quest',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawal-approved',
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
