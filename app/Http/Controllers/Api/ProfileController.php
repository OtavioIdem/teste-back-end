<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller {
    public function show(Request $req) { return $req->user(); }
    public function update(UpdateProfileRequest $req) {
        $u = $req->user();
        $u->fill($req->validated())->save();
        return response()->json($u);
    }
}
