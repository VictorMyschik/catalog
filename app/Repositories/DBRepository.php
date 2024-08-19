<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\DatabaseManager;

readonly class DBRepository
{
    public function __construct(protected DatabaseManager $db) {}
}