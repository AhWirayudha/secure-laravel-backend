<?php

namespace App\Modules\MasterData\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private MasterDataService $masterDataService
    ) {
        $this->middleware('auth:api');
        $this->middleware('permission:view-roles')->only(['index', 'show']);
        $this->middleware('permission:create-roles')->only(['store']);
        $this->middleware('permission:edit-roles')->only(['update']);
        $this->middleware('permission:delete-roles')->only(['destroy']);
    }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request): JsonResponse
    {
        $roles = $this->masterDataService->getAllRoles($request->all());

        return response()->json([
            'message' => 'Roles retrieved successfully',
            'data' => $roles,
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'sometimes|string|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = $this->masterDataService->createRole($request->validated());

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified role.
     */
    public function show(string $id): JsonResponse
    {
        $role = $this->masterDataService->getRoleById($id);

        return response()->json([
            'message' => 'Role retrieved successfully',
            'data' => $role
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => "sometimes|string|max:255|unique:roles,name,{$id}",
            'guard_name' => 'sometimes|string|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = $this->masterDataService->updateRole($id, $request->validated());

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    /**
     * Remove the specified role.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->masterDataService->deleteRole($id);

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }
}
