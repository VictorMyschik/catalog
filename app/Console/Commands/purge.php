<?php

namespace App\Console\Commands;

use App\Console\Commands\Lego\CacheClearTrait;
use App\Console\Commands\Lego\ConsoleMessagesTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class purge extends Command
{
    use ConsoleMessagesTrait;
    use CacheClearTrait;

    protected $signature = 'purge';

    protected $description = 'Sync database, clear cache, clear logs';

    public function handle(): void
    {
        if (ENV('PRODUCTION') === true) {
            $this->err('ERROR: USING FOR DEVELOPMENT ONLY');
            $this->nl();

            return;
        }

        $this->refreshTables();
        $this->addTablesData();

        $this->clearCache();

        $this->nl();
        $this->success('FINISH');
        $this->nl();
    }

    private function refreshTables(): void
    {
        $statements = [
            "DROP SCHEMA IF EXISTS public CASCADE;",
            "CREATE SCHEMA public;",
            "GRANT ALL ON SCHEMA public TO public;",
        ];

        foreach ($statements as $statement) {
            $result = DB::statement($statement);
            $result ? $this->success($statement) : $this->err($statement);
            $this->nl();
        }

        $this->nl();
        $this->success('migration tables');

        Artisan::call('migrate');

        $this->success(' is OK');
        $this->nl();
    }

    private function addTablesData(): void
    {
        $this->nl();
        $this->success('Add data');
        $this->nl();

        foreach ($this->tableList as $tableName) {
            $path = __DIR__ . "/purge_data/$tableName.sql";
            if (!is_file($path)) {
                continue;
            }

            $file = file_get_contents($path);

            if (strlen($file) < 5) {
                continue;
            }

            DB::unprepared($file);

            // Refresh field counter. for postgresql only
            DB::statement("SELECT pg_catalog.setval(pg_get_serial_sequence('$tableName', 'id'), MAX(id)) FROM $tableName;");
        }
    }

    private array $tableList = [
        'settings',
        'users',
        'on_catalog_groups',
        /*'role_users',
        'roles',
        'countries',
        'cron',
        'on_catalog_groups',
        'on_catalog_group_attributes',
        'on_catalog_attributes',
        'on_catalog_markets',
        'on_catalog_attribute_values',
        'on_manufacturers',
        'on_catalog_goods',
        'on_good_attributes',
        'on_catalog_images',
        'on_prices',*/
    ];
}
