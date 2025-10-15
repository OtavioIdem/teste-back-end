<?php
namespace App\Console\Commands;

use App\Jobs\ImportProductJob;
use App\Services\ImportProductsService;
use Illuminate\Console\Command;

class ProductsImportCommand extends Command
{
    protected $signature = 'products:import {--id=} {--queued}';
    protected $description = 'Importa produtos da FakeStore API. Use --id=123 para um único item. Use --queued para fila.';

    public function handle(ImportProductsService $service): int
    {
        $id = $this->option('id');

        if ($id) {
            if ($this->option('queued')) {
                ImportProductJob::dispatch((int)$id);
                $this->info("Produto externo {$id} enfileirado para importação.");
                return self::SUCCESS;
            }

            $product = $service->importOneByExternalId((int)$id);
            $this->info($product ? "Produto {$product->id} importado/atualizado." : "Produto não encontrado.");
            return self::SUCCESS;
        }

        $count = $service->importAll();
        $this->info("Importação concluída: {$count} produtos processados.");
        return self::SUCCESS;
    }
}
