<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherUserController extends Controller
{
    public function index()
    {
        return view('Teacher.users-index');
    }
}