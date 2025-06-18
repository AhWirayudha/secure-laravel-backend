<?php

namespace App\Modules\MasterData\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        private MasterDataService $masterDataService
    ) {
        $this->middleware('auth:api');
        $this->middleware('permission:view-permissions')->only(['index', 'show']);
        $this->middleware('permission:create-permissions')->only(['store']);
        $this->middleware('permission:edit-permissions')->only(['update']);
        $this->middleware('permission:delete-permissions')->only(['destroy']);
    }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $permissions = $this->masterDataService->getAllPermissions($request->all());

        return response()->json([
            'message' => 'Permissions retrieved successfully',
            'data' => $permissions,
        ]);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'sometimes|string|max:255',
        ]);

        $permission = $this->masterDataService->createPermission($request->validated());

        return response()->json([
            'message' => 'Permission created successfully',
            'data' => $permission
        ], 201);
    }

    /**
     * Display the specified permission.
     */
    public function show(string $id): JsonResponse
    {
        $permission = $this->masterDataService->getPermissionById($id);

        return response()->json([
            'message' => 'Permission retrieved successfully',
            'data' => $permission
        ]);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => "sometimes|string|max:255|unique:permissions,name,{$id}",
            'guard_name' => 'sometimes|string|max:255',
        ]);

        $permission = $this->masterDataService->updatePermission($id, $request->validated());

        return response()->json([
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->masterDataService->deletePermission($id);

        return response()->json([
            'message' => 'Permission deleted successfully'
        ]);
    }
}
