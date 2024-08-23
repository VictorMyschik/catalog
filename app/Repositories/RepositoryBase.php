<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ORM\ORM;
use Illuminate\Database\DatabaseManager;

readonly abstract class RepositoryBase
{
    public function __construct(protected DatabaseManager $db) {}
}
