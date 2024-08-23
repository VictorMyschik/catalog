<?php

namespace App\Orchid\Layouts\System;

use App\Models\System\FailedJobs;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FailedJobsListLayout extends Table
{
    protected $target = 'failed-jobs';

    public function striped(): bool
    {
        return true;
    }

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('uuid', 'Unique ID')->width(100),
            TD::make('queue', 'Queue')->sort(),
            TD::make('payload', 'Payload')
                ->render(function (FailedJobs $job) {
                    return View('admin.failed_jobs')->with([
                        'data' => unserialize(json_decode($job->payload)->data->command, ['allowed_classes' => false]),
                    ]);
                })->width(500),

            TD::make('exception', 'Exception')
                ->render(function (FailedJobs $job) {
                    return View('admin.failed_jobs')->with(['data' => substr($job->exception, 0, 2000) . '......']);
                }),

            TD::make('failed_at', 'Failed')->sort()
                ->render(fn(FailedJobs $job) => $job->failed_at->format('d.m.Y')),

            TD::make('#', 'Действия')->render(function (FailedJobs $job) {
                return DropDown::make()->icon('options-vertical')->list([
                    Button::make('Retry')
                        ->confirm('Retry this job?')
                        ->icon('action-undo')
                        ->method('retryFailedJob')
                        ->parameters(['failed_job_id' => $job->id()]),

                    Button::make('Delete')
                        ->confirm('Delete this job?')
                        ->icon('trash')
                        ->method('deleteFailedJob')
                        ->parameters(['failed_job_id' => $job->id()]),
                ]);
            }),
        ];
    }

    protected function iconNotFound(): string
    {
        return '';
    }

    protected function textNotFound(): string
    {
        return '';
    }

    protected function subNotFound(): string
    {
        return 'Jobs not found';
    }
}
