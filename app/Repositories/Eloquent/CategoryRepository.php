<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }
    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }
    public function delete(Category $category): void
    {
        $category->delete();
    }
    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }
    public function findByName(string $name): ?Category
    {
        return Category::where('name', $name)->first();
    }
}
