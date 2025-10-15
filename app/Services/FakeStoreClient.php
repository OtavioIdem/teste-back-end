<?php

use \Illuminate\Http\Client\Factory;

class FakeStoreClient {
    public function __construct(private Factory $http) {}

    public function getProduct(int|string $id): array {
        $res = $this->http->baseUrl('https://fakestoreapi.com')->get("/products/{$id}")->throw();
        return $res->json();
    }
    public function getAll(): array {
        return $this->http->baseUrl('https://fakestoreapi.com')->get('/products')->throw()->json();
    }
}