<?php

namespace App\Models;

// Laravel's built-in DatabaseNotification already covers this.
// Just use the parent model or extend it:

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    // You can add custom scopes or helpers here

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }
}