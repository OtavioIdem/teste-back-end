<?php
namespace App\Jobs;

use App\Services\ImportProductsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public int $externalId) {}

    public function handle(ImportProductsService $service): void
    {
        $service->importOneByExternalId($this->externalId);
    }
}
