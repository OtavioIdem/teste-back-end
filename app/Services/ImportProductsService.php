<?php
namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class ImportProductsService
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly Client $http
    ) {}

    public function importAll(): int
    {
        $res = $this->http->get('https://fakestoreapi.com/products');
        $items = json_decode((string) $res->getBody(), true) ?? [];
        $count = 0;

        foreach ($items as $item) {
            $count += (int) (bool) $this->upsertFromFakeStore($item);
        }
        return $count;
    }

    public function importOneByExternalId(int $externalId): ?Product
    {
        $res = $this->http->get("https://fakestoreapi.com/products/{$externalId}");
        $item = json_decode((string) $res->getBody(), true);
        if (!$item) return null;
        return $this->upsertFromFakeStore($item);
    }

    private function upsertFromFakeStore(array $item): Product
    {
        // FakeStore fields: id, title, price, description, category, image
        $existing = $this->products->findByExternalId((string) Arr::get($item, 'id'));
        $payload = [
            'name'        => Arr::get($item, 'title'),
            'price'       => Arr::get($item, 'price'),
            'description' => Arr::get($item, 'description'),
            'category'    => Arr::get($item, 'category'),
            'image'       => Arr::get($item, 'image'),
        ];

        if ($existing) {
            $product = $this->products->update($existing, [
                'name'        => $payload['name'],
                'price'       => $payload['price'],
                'description' => $payload['description'],
                'image_url'   => $payload['image'],
            ], [$payload['category']]);
        } else {
            $product = $this->products->create([
                'name'        => $payload['name'],
                'price'       => $payload['price'],
                'description' => $payload['description'],
                'image_url'   => $payload['image'],
                'external_id' => (string) Arr::get($item, 'id'),
            ], [$payload['category']]);
        }

        return $product;
    }
}
