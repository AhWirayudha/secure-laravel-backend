<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Requests\UserFilterRequest;
use App\Modules\User\Resources\UserResource;
use App\Modules\User\Resources\UserCollection;
use App\Modules\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
        $this->middleware('auth:api');
        $this->middleware('permission:view-users')->only(['index', 'show']);
        $this->middleware('permission:create-users')->only(['store']);
        $this->middleware('permission:edit-users')->only(['update']);
        $this->middleware('permission:delete-users')->only(['destroy']);
    }

    /**
     * Display a listing of users.
     */
    public function index(UserFilterRequest $request): JsonResponse
    {
        $users = $this->userService->getAllUsers($request->validated());
        
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => new UserCollection($users),
            'meta' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(string $id): JsonResponse
    {
        $user = $this->userService->restoreUser($id);

        return response()->json([
            'message' => 'User restored successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete(string $id): JsonResponse
    {
        $this->userService->forceDeleteUser($id);

        return response()->json([
            'message' => 'User permanently deleted'
        ]);
    }

    /**
     * Activate/Deactivate user.
     */
    public function toggleStatus(string $id): JsonResponse
    {
        $user = $this->userService->toggleUserStatus($id);

        return response()->json([
            'message' => 'User status updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Assign roles to user.
     */
    public function assignRoles(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $user = $this->userService->assignRoles($id, $request->roles);

        return response()->json([
            'message' => 'Roles assigned successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove roles from user.
     */
    public function removeRoles(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $user = $this->userService->removeRoles($id, $request->roles);

        return response()->json([
            'message' => 'Roles removed successfully',
            'data' => new UserResource($user)
        ]);
    }
}
