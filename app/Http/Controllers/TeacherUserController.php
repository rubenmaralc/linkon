<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourseEnrollment;

class TeacherUserController extends Controller
{
    public function index()
    {
        $users = User::with('courseEnrollment', 'course')->paginate(20);
        $enrollments = CourseEnrollment::with('course')->get();
        $courses = Course::with('teacher')->get();
        return view('Teacher.users-index', ['users' => $users, 'enrollments' => $enrollments, 'courses' => $courses]);
    }

    public function edit($id)
    {
        $user = User::with('courseEnrollment', 'course')->find($id);
        $courses = Course::all();
        return view('Teacher.users-edit', ['user' => $user, 'courses' => $courses]);
    }

    public function assignCourse(Request $request, $id)
    {
        $enrollment = new CourseEnrollment();
        $enrollment->user_id = $id;
        $enrollment->course_id = $request->course_id;
        $enrollment->enrollment_date = now();
        $enrollment->status = 'true';
        try {
            $enrollment->save();
            return redirect()->route('teacher.users.edit', $id)->with('success', 'Course assigned successfully');
        } catch (\Exception $e) {
            return redirect()->route('teacher.users.edit', $id)->with('error', 'An error occurred while assigning the course');
        }
    }

    public function unassignCourse($id)
    {
        $enrollment = CourseEnrollment::find($id);
        try {
            $enrollment->delete();
            return redirect()->route('teacher.users')->with('success', 'Course unassigned successfully');
        } catch (\Exception $e) {
            return redirect()->route('teacher.users')->with('error', 'An error occurred while unassigning the course');
        }
    }
}
