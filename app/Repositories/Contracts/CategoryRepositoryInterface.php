<?php
namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function create(array $data): Category;
    public function update(Category $category, array $data): Category;
    public function delete(Category $category): void;

    public function all(): Collection;
    public function findById(int $id): ?Category;
    public function findByName(string $name): ?Category;
}
