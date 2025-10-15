<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data, array $categoryNames = []): Product
    {
        return DB::transaction(function () use ($data, $categoryNames) {
            $product = Product::create($data);
            $this->syncCategories($product, $categoryNames);
            return $product->load('categories');
        });
    }

    public function update(Product $product, array $data, array $categoryNames = []): Product
    {
        return DB::transaction(function () use ($product, $data, $categoryNames) {
            $product->update($data);
            if ($categoryNames !== []) {
                $this->syncCategories($product, $categoryNames);
            }
            return $product->load('categories');
        });
    }

    public function delete(Product $product): void
    {
        $product->categories()->detach();
        $product->delete();
    }

    public function findById(int $id): ?Product
    {
        return Product::with('categories')->find($id);
    }

    public function findByExternalId(?string $extId): ?Product
    {
        if (!$extId) return null;
        return Product::where('external_id', $extId)->first();
    }

    public function search(?string $name, ?string $category, ?bool $hasImage, int $perPage = 15): LengthAwarePaginator
    {
        $q = Product::query()->with('categories');

        if ($name) {
            $q->where('name', 'like', "%{$name}%");
        }

        if ($category) {
            $q->whereHas('categories', fn($c) => $c->where('name', 'like', "%{$category}%"));
        }

        if (!is_null($hasImage)) {
            $hasImage
                ? $q->whereNotNull('image_url')->where('image_url', '<>', '')
                : $q->where(function ($qq) {
                    $qq->whereNull('image_url')->orWhere('image_url', '=', '');
                });
        }

        return $q->paginate($perPage);
    }

    public function allByCategory(string $categoryName): Collection
    {
        return Product::whereHas('categories', fn($c) => $c->where('name', $categoryName))->get();
    }

    private function syncCategories(Product $product, array $categoryNames): void
    {
        if ($categoryNames === []) return;

        if (count($categoryNames) > 3) {
            throw new \InvalidArgumentException('Um produto pode ter no máximo 3 categorias.');
        }

        $ids = collect($categoryNames)
            ->map(fn($name) => trim($name))
            ->filter()
            ->unique()
            ->map(function ($name) {
                $cat = Category::firstOrCreate(['name' => $name]);
                return $cat->id;
            })->values()->all();

        $product->categories()->sync($ids);
    }
}
