<?php

namespace App\Http\Controllers;

use App\Models\Todo;
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
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $todo = new Todo();
        $todo->task = $request->task;
        $todo->save();

        return response()->json(['message' => 'Task added successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $todo = Todo::find($id);
        $todo->task = $request->task;
        $todo->save();

        return response()->json(['message' => 'Task updated successfully!']);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo = Todo::latest('id')->get();
        $todo->delete();
        return response()->json(['message' => 'Task deleted successfully!']);
    }
}
