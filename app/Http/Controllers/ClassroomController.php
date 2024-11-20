<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with(['students', 'teachers'])->get();
        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $classroom = Classroom::create($validated);

        if($request->ajax()) {
            return response()->json($classroom);
        }

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom created successfully.');
    }

    public function show(Classroom $classroom)
    {
        $classroom->load(['students', 'teachers']);

        if(request()->ajax()) {
            return response()->json($classroom);
        }

        return view('classrooms.show', compact('classroom'));
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $classroom->update($validated);

        if($request->ajax()) {
            return response()->json($classroom);
        }

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        if(request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }

    public function getStudents(Classroom $classroom)
    {
        return response()->json($classroom->students);
    }

    public function getTeachers(Classroom $classroom)
    {
        return response()->json($classroom->teachers);
    }
}
