<?php

declare(strict_types=1);

namespace App\Services\System;

use App\Models\System\Cron;
use App\Services\Catalog\Onliner\ImportOnlinerService;
use App\Services\System\Enum\CronKeyEnum;
use DateInterval;
use Exception;
use Illuminate\Support\Facades\Log;

final readonly class CronService
{
    public function __construct(private ImportOnlinerService $importOnlinerService) {}

    public function setLog(string $message): void
    {
        Log::info($message);
    }

    public function needRun(Cron $job): bool
    {
        $lastWork = $job->getLastWork();
        if (is_null($lastWork)) {
            return true;
        }
        $lastWork->add(new DateInterval('PT' . $job->getPeriod() . 'M'));

        return now() > $lastWork;
    }

    public function runAllActive(): void
    {
        /** @var Cron $job */
        foreach (Cron::where('active', true)->get()->all() as $job) {
            if ($this->needRun($job)) {
                try {
                    $this->runById($job->id());
                } catch (Exception $e) {
                    $this->setLog('Wrong run cron job:' . $e->getMessage());
                }
            }
        }
    }

    public function runById(int $id): void
    {
        $cron = Cron::loadByOrDie($id);

        match ($cron->getCronKey()) {
            CronKeyEnum::UPDATE_CATALOG_GOODS => $this->importOnlinerService->updateCatalogGoods(),
        };

        $cron->setLastWork(now());
        $cron->save();
    }
}
