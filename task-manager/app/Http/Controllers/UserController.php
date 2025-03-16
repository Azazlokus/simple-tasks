<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use App\Filters\UserFilters;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = new UserFilters($request);
            $users = $filters->apply(User::query())->paginate(10);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => "Users list",
                'data' => $users
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve users', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user', $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'User retrieved successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $user->update($request->validated());

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'User updated successfully',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update user', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => 'User deleted successfully'
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete user', $e);
        }
    }

    /**
     * Генерирует JSON-ответ с ошибкой.
     */
    private function errorResponse(string $message, \Exception $e): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'error' => $message,
            'details' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}