<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'classroomsCount' => Classroom::count(),
            'teachersCount' => Teacher::count(),
            'studentsCount' => Student::count(),
            'classrooms' => Classroom::withCount(['students', 'teachers'])->get(),
            'recentActivities' => $this->getRecentActivities()
        ];

        return view('home', $data);
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Get latest students
        $latestStudents = Student::with('classroom')
            ->latest()
            ->take(3)
            ->get();

        foreach ($latestStudents as $student) {
            $activities[] = "New student {$student->name} joined {$student->classroom->name}";
        }

        // Get latest teachers
        $latestTeachers = Teacher::with('classroom')
            ->latest()
            ->take(3)
            ->get();

        foreach ($latestTeachers as $teacher) {
            $activities[] = "New teacher {$teacher->name} assigned to {$teacher->classroom->name}";
        }

        return $activities;
    }
}
