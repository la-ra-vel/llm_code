<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $status = $request->query('status');
        // $todos = Todo::when($status, function ($query, $status) {
        //     return $query->where('is_complete', $status == 'completed');
        // })->get();
        // return response()->json($todos);

        $status = $request->input('status', '');
        $page = $request->input('page', 1);

        $query = Todo::query();

        if ($status === 'completed') {
            $query->where('is_complete', 1);
        } elseif ($status === 'incomplete') {
            $query->where('is_complete', 0);
        } elseif ($status === 'all') {
            $query->get();
        }else {
            $query->where('is_complete', 0);
        }

        $todos = $query->orderBy('created_at', 'desc')->paginate(20, ['*'], 'page', $page);

        return response()->json($todos->items());

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $todo = Todo::create($validatedData);
            return response()->json($todo);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->update($request->all());
        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(['success' => true]);
    }
}
