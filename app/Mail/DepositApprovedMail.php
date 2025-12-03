<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $amount;
    public $packageName;
    public $isUpgrade;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, float $amount, string $packageName, bool $isUpgrade = false)
    {
        $this->userName = $userName;
        $this->amount = $amount;
        $this->packageName = $packageName;
        $this->isUpgrade = $isUpgrade;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isUpgrade ? 'Package Upgrade Approved - Earn Quest' : 'Deposit Approved - Earn Quest',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit-approved',
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
