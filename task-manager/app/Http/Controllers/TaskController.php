<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use App\Filters\TaskFilters;
use App\Traits\HandlesApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use HandlesApiResponse;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = Task::with('users');
            $query = TaskFilters::apply($query, $request);

            if ($request->query('group_by') === 'status') {
                return $this->successResponse("Tasks grouped by status", $query->get()->groupBy('status'));
            }

            return $this->successResponse("Tasks list", $query->paginate(10));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve tasks', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return $this->successResponse('Task created successfully', $task, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create task', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    public function show(Task $task): JsonResponse
    {
        return $this->successResponse('Task retrieved successfully', $task->load('users'));
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        return $this->taskService->updateTask($task, $request->validated());
    }

    public function destroy(Task $task): JsonResponse
    {
        return $this->taskService->deleteTask($task);
    }
}