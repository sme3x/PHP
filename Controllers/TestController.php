<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTestModelRequest;
use App\Http\Requests\StoreTestModelRequest;
use App\Http\Traits\AppliesFiltersTrait;
use Illuminate\Http\JsonResponse;
use App\Models\TestModel;
use Illuminate\Http\Request;

class TestModelController extends Controller
{
    use AppliesFiltersTrait;

    public function index(Request $request): JsonResponse
    {
        $testModels = TestModel::query();

        // Here would be some kinda filters before returning the data
        return new JsonResponse($request, $testModels);
    }

    public function store(StoreTestModelRequest $request): JsonResponse
    {
        $testModel = TestModel::create($request->validated());

        return new JsonResponse($testModel);
    }

    public function show(TestModel $testModel): JsonResponse
    {
        return new JsonResponse($testModel->load('wasteCollections')->load('wasteCollectionActivities'));
    }

    public function update(UpdateTestModelRequest $request, TestModel $testModel): JsonResponse
    {
        $testModel->update($request->validated());

        return new JsonResponse($testModel);
    }

    public function destroy(TestModel $testModel): JsonResponse
    {
        $testModel->delete();

        return new JsonResponse(null, 204);
    }
}
