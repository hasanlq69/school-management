<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classroom')->get();
        $classrooms = Classroom::all();
        return view('students.index', compact('students', 'classrooms'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $student = Student::create($validated);

        if($request->ajax()) {
            return response()->json($student->load('classroom'));
        }

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load('classroom');

        if(request()->ajax()) {
            return response()->json($student);
        }

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $student->update($validated);

        if($request->ajax()) {
            return response()->json($student->load('classroom'));
        }

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        if(request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function getByClassroom($classroom_id)
    {
        $students = Student::where('classroom_id', $classroom_id)->get();
        return response()->json($students);
    }
}
