<?php

declare(strict_types=1);

namespace App\Services\System;

use App\Models\System\Cron;
use DateInterval;
use Exception;
use Illuminate\Support\Facades\Log;

final readonly class CronService
{
    public function setLog(string $message): void
    {
        Log::info($message);
    }

    public function needRun(Cron $job): bool
    {
        $lastWork = $job->getLastWorkObject();
        $lastWork->add(new DateInterval('PT' . $job->getPeriod() . 'M'));

        return now() > $lastWork;
    }

    public function runAllActive(): void
    {
        /** @var Cron $job */
        foreach (Cron::where('active', true)->get()->all() as $job) {
            if ($this->needRun($job)) {
                try {

                } catch (Exception $e) {
                    $this->setLog('Wrong run cron job:' . $e->getMessage());
                }
            }
        }
    }
}
