<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class TaskController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with('users')->paginate(10);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            if (User::whereIn('id', $request->assigned_users)->where('status', 'vacation')->exists()) {
                return response()->json([
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error' => 'Cannot assign tasks to employees on vacation'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $task = Task::create($request->validated());

            if ($request->has('assigned_users')) {
                $task->users()->sync($request->assigned_users);
            }

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'Task created successfully',
                'data' => $task
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Failed to create task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = Task::with('users')->find($id);

        if (!$task) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Task not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Task retrieved successfully',
            'data' => $task
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        try {
            if ($request->has('assigned_users')) {
                if (User::whereIn('id', $request->assigned_users)->where('status', 'vacation')->exists()) {
                    return response()->json([
                        'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'error' => 'Cannot assign tasks to employees on vacation'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $task->update($request->validated());

            if ($request->has('assigned_users')) {
                $task->users()->sync($request->assigned_users);
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Task updated successfully',
                'data' => $task
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Failed to update task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Task not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $task->delete();

            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => 'Task deleted successfully'
            ], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Failed to delete task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function groupedTasks(): JsonResponse
    {
        try {
            $tasks = Task::with('users')->get()->groupBy('status');

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Tasks grouped by status',
                'data' => $tasks
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Failed to retrieve grouped tasks'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
