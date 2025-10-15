<?php
namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function create(array $data, array $categoryNames = []): Product;
    public function update(Product $product, array $data, array $categoryNames = []): Product;
    public function delete(Product $product): void;

    public function findById(int $id): ?Product;
    public function findByExternalId(?string $extId): ?Product;

    public function search(?string $name, ?string $category, ?bool $hasImage, int $perPage = 15): LengthAwarePaginator;

    /** @return Collection<int,Product> */
    public function allByCategory(string $categoryName): Collection;
}
