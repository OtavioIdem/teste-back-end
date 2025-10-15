<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service,
        private readonly ProductRepositoryInterface $products
    ) {}

    public function index(Request $request)
    {
        $name     = $request->query('name');
        $category = $request->query('category');
        $hasImage = match ($request->query('has_image')) {
            '1','true','yes' => true,
            '0','false','no' => false,
            default => null
        };

        $items = $this->products->search($name, $category, $hasImage, perPage: (int) ($request->query('per_page', 15)));
        return ProductResource::collection($items);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = $this->service->create($request->validated());
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        $product->load('categories');
        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->products->delete($product);
        return response()->noContent();
    }
}
