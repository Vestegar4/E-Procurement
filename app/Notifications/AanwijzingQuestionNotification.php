<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AanwijzingQuestionNotification extends Notification
{
    use Queueable;

    protected string $vendorName;
    protected string $tenderTitle;

    public function __construct(string $vendorName, string $tenderTitle)
    {
        $this->vendorName = trim($vendorName);
        $this->tenderTitle = trim($tenderTitle);
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pertanyaan Baru',
            'message' =>
            $this->vendorName .
                ' mengirim pertanyaan pada tender ' .
                $this->tenderTitle,
            'type' => 'aanwijzing',
        ];
    }
}
