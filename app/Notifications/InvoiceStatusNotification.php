<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Invoice $invoice, public string $event) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // add 'broadcast' for real-time
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Invoice {$this->invoice->invoice_number} — " . ucfirst($this->event))
            ->line("Your invoice #{$this->invoice->invoice_number} has been {$this->event}.")
            ->action('View Invoice', url("/invoices/{$this->invoice->id}"))
            ->line('Thank you for using our platform.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'invoice_id'     => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'event'          => $this->event,
            'total_amount'   => $this->invoice->total_amount,
            'message'        => "Invoice {$this->invoice->invoice_number} has been {$this->event}.",
        ];
    }
}
