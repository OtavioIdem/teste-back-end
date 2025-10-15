<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryRepositoryInterface $categories) {}

    public function index()
    {
        return CategoryResource::collection($this->categories->all());
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = $this->categories->create($request->validated());
        return (new CategoryResource($category))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(CategoryStoreRequest $request, Category $category)
    {
        $category = $this->categories->update($category, $request->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->categories->delete($category);
        return response()->noContent();
    }
}
