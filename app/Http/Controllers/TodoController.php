<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TodoController extends Controller

{
    public function index()
    {
        $todos = Todo::all();
        return view('todo', compact('todos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255',
        ], [
            'task.required' => 'The task field cannot be empty. Please provide a task.',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all(),
            ]);
        }

        $todo = new Todo();
        $todo->task = $request->task;
        $todo->save();

        return response()->json(['message' => 'Task added successfully!', 'success' => true]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255',
        ], [
            'task.required' => 'The task field cannot be empty. Please provide a task.',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all(),
            ]);
        }

        $todo = Todo::find($id);
        $todo->task = $request->task;
        $todo->save();

        return response()->json(['message' => 'Task updated successfully!', 'success' => true]);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Task deleted successfully!']);
    }
}
