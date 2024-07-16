<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $users   = User::where('role', 'pengguna')->orderBy('created_at', 'DESC')->get();
        $page_title = 'Data Pengguna';

        return view('data.index', compact('users', 'page_title'));
    }
}
