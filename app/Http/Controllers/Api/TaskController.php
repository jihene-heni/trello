<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //  Liste des tâches
    public function index()
    {
        return response()->json(Task::with('user')->get());
    }

    //  Détails d’une tâche
    public function show($id)
    {
        $task = Task::with('user')->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    //  Créer une nouvelle tâche
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => 'nullable|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,done',
            'due_date'    => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    // 📌 Mettre à jour une tâche
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validated = $request->validate([
            'user_id'     => 'nullable|exists:users,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:pending,in_progress,done',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // 📌 Supprimer une tâche
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}