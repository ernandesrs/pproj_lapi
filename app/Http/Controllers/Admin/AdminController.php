<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Index
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'users' => [
                'total' => \App\Models\User::all()->count(),
                'verified' => \App\Models\User::whereNotNull('email_verified_at')->count(),
                'not_verified' => \App\Models\User::whereNull('email_verified_at')->count(),
                'deleted' => \App\Models\User::where('status', '=', \App\Models\User::STATUS_DELETED)->count(),
                'admin' => \App\Models\User::where('level', '=', \App\Models\User::LEVEL_ADMIN)->count(),
                'common' => \App\Models\User::where('level', '=', \App\Models\User::LEVEL_COMMON)->count(),
                'super' => \App\Models\User::where('level', '=', \App\Models\User::LEVEL_SUPER)->count()
            ],
            'roles' => [
                'total' => \App\Models\Role::all()->count()
            ]
        ]);
    }
}