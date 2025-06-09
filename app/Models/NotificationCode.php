<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCode extends Model
{
    public const null UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'code',
        'action',
        'data',
    ];
}
