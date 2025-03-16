<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Notifications\TaskStatusUpdated;
use App\Traits\HandlesApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskService
{
    use HandlesApiResponse;

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data): JsonResponse
    {
        try {
            $oldStatus = $task->status->value;
            $task->update($data);
            $newStatus = $task->status->value;

            if ($oldStatus !== $newStatus && in_array($newStatus, [TaskStatus::IN_PROGRESS->value, TaskStatus::DONE->value])) {
                Log::info("Task #{$task->id} status changed: {$oldStatus} → {$newStatus}");

                foreach ($task->users as $user) {
                    $user->notify(new TaskStatusUpdated($task));
                }
            }

            return $this->successResponse('Task updated successfully', $task);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update task', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    public function deleteTask(Task $task): JsonResponse
    {
        try {
            $task->delete();
            return $this->successResponse('Task deleted successfully', null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete task', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}