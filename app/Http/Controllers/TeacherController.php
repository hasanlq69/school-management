<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('classroom')->get();
        $classrooms = Classroom::all();
        return view('teachers.index', compact('teachers', 'classrooms'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('teachers.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $teacher = Teacher::create($validated);

        if($request->ajax()) {
            return response()->json($teacher->load('classroom'));
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load('classroom');

        if(request()->ajax()) {
            return response()->json($teacher);
        }

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $classrooms = Classroom::all();
        return view('teachers.edit', compact('teacher', 'classrooms'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $teacher->update($validated);

        if($request->ajax()) {
            return response()->json($teacher->load('classroom'));
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        if(request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    public function getByClassroom($classroom_id)
    {
        $teachers = Teacher::where('classroom_id', $classroom_id)->get();
        return response()->json($teachers);
    }
}
