<?php
namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use InvalidArgumentException;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $products) {}

    public function create(array $payload): Product
    {
        [$data, $categories] = $this->normalize($payload);
        return $this->products->create($data, $categories);
    }

    public function update(Product $product, array $payload): Product
    {
        [$data, $categories] = $this->normalize($payload);
        return $this->products->update($product, $data, $categories);
    }

    /** @return array{0: array, 1: array} */
    private function normalize(array $payload): array
    {
        $data = [
            'name'        => $payload['name'],
            'price'       => $payload['price'],
            'description' => $payload['description'],
            'image_url'   => $payload['image'] ?? null,
        ];

        // API pede "category" (string) mas aceitamos também array categories
        $categories = [];
        if (isset($payload['category'])) {
            $categories = is_array($payload['category']) ? $payload['category'] : [$payload['category']];
        } elseif (isset($payload['categories'])) {
            $categories = (array) $payload['categories'];
        }

        if (count($categories) > 3) {
            throw new InvalidArgumentException('Um produto pode ter no máximo 3 categorias.');
        }

        return [$data, $categories];
    }
}
