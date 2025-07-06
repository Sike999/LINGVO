<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalAccountController extends Controller
{
    function index(Request $request)
    {
        $rubrics = Rubric::all();

        $filter = $request->input('filter');
        if ($filter === 'active') {
            $posts = Course::with('users')->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->where('date', '>=', now())->get();
            $add = 'Активные курсы';
        } elseif ($filter === 'past') {
            $posts = Course::with('users')->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->where('date', '<', now())->get();
            $add = 'Завершенные курсы';
        } elseif ($filter === 'empty') {
            $all = Course::withCount('users')->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->where('date', '>=', now('UTC'))->get();
            $posts = $all->filter(function ($course) {
                return $course->users_count >= $course->capacity;
            });
            $add = 'Заполненные курсы';
        } else {
            $posts = Course::with('users')->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->get();
            $add = '';
        }

        return view('lk', ['header' => $add, 'posts' => $posts, 'rubrics' => $rubrics]);
    }

    function storeReg(int $rubrics_id, $post_id)
    {
        $post = Course::withCount('users')->findOrFail($post_id);
        // Auth::user()->id;
        if ($post['users_count'] < $post['capacity'] && now()->lt($post['date']) && !$post->users->contains(Auth::id())) {
            $post->users()->attach(Auth::user()->id);
        }

        return redirect(route('statya', [$rubrics_id, $post_id]));
    }

    function cancelReg(int $rubrics_id, $post_id)
    {
        $post = Course::withCount('users')->findOrFail($post_id);
        // Auth::user()->id;
        if ($post->users->contains(Auth::id()) && now()->addDay()->lt($post['date'])) {
            $post->users()->detach(Auth::user()->id);
        }

        return redirect(route('statya', [$rubrics_id, $post_id]));
    }
}
