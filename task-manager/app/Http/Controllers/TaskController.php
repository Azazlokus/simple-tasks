<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use App\Notifications\TaskStatusUpdated;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->query('group_by') === 'status') {
                $tasks = Task::with('users')->get()->groupBy('status');

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => "Tasks grouped by status",
                    'data' => $tasks
                ], Response::HTTP_OK);
            }

            $tasks = Task::with('users')->paginate(10);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => "Tasks list",
                'data' => $tasks
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Failed to retrieve tasks',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            /* if (User::whereIn('id', $request->assigned_users)->where('status', 'vacation')->exists()) {
                return response()->json([
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error' => 'Cannot assign tasks to employees on vacation'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } */

            $task = Task::create($request->validated());
/* 
            if ($request->has('assigned_users')) {
                $task->users()->sync($request->assigned_users);
            } */

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
            $oldStatus = $task->status->value; 
            $task->update($request->validated());
            
            $newStatus = $task->status->value;
            
            if ($oldStatus !== $newStatus && in_array($newStatus, [TaskStatus::IN_PROGRESS->value, TaskStatus::DONE->value])) {
                Log::info("Task #{$task->id} status changed: {$oldStatus} → {$newStatus}");
            
                foreach ($task->users as $user) {
                    $user->notify(new TaskStatusUpdated($task));
                }
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Task updated successfully',
                'data' => $task
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            Log::error("Error updating task #{$task->id}: " . $e->getMessage());

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

    /* public function groupedTasks(): JsonResponse
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
    } */
}
