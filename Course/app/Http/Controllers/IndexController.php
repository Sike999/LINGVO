<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rubric;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IndexController extends Controller
{
    //
    function index(Request $request)
    {
        $rubrics = Rubric::all();
        // $posts = Course::all();
        $filter = $request->input('filter');

        if ($filter === 'active') {
            $posts = Rubric::where('date', '>=', now())->get();
            $add = 'Активные курсы';
        } elseif ($filter === 'past') {
            $posts = Rubric::where('date', '<', now())->get();
            $add = 'Завершенные курсы';
        } elseif ($filter === 'empty') {
            $all = Rubric::withCount('users')->get();
            $posts = $all->filter(function ($course) {
                return $course->users_count >= $course->capacity;
            });
            $add = 'Заполненные курсы';
        } else {
            $rubrics = Rubric::all();
            $add = '';
        }

        return view('index', ['rubrics' => $rubrics, 'posts' => $posts, 'header' => $add]);
    }

    function rubric(Request $request, int $rubrics_id)
    {
        $rubric_name = Rubric::findOrFail($rubrics_id)->name;
        $rubrics = Rubric::all();

        $filter = $request->input('filter');

        if ($filter === 'active') {
            $posts = Course::where('rubric_id', '=', $rubrics_id)->where('date', '>', now())->get();
            $add = ' (активные курсы)';
        } elseif ($filter === 'past') {
            $posts = Course::where('rubric_id', '=', $rubrics_id)->where('date', '<=', now())->get();
            $add = ' (завершенные курсы)';
        } elseif ($filter === 'empty') {
            $all = Course::withCount('users')->where('rubric_id', '=', $rubrics_id)->get();
            $posts = $all->filter(function ($course) {
                return $course->users_count >= $course->capacity;
            });
            $add = ' (заполненные курсы)';
        } else {
            $posts = Course::where('rubric_id', '=', $rubrics_id)->get();
            $add = '';
        }

        // $posts = Course::where('rubric_id', '=', $rubrics_id)->get();
        return view('rubrika', ['rubrics' => $rubrics, 'header' => $rubric_name, 'posts' => $posts, 'id' => $rubrics_id, 'add' => $add]);
    }

    function post(int $rubrics_id, int $post_id)
    {
        $rubric_name = Rubric::findOrFail($rubrics_id)->name;
        $post = Course::withCount('users')->findOrFail($post_id);
        // $post = Course::where('rubric_id', $rubrics_id)->where('id', $post_id)->firstOrFail();

        $capacity = $post['capacity'] - $post['users_count'];
        $date = now()->lt($post['date']);
        $is_registered = $post->users->contains(Auth::id());

        $is_registrable = $capacity > 0 && $date && !$is_registered;

        $is_date_cancelable = now()->addDay()->lt($post['date']);
        $is_cancelable = $is_date_cancelable && $is_registered;

        $rubrics = Rubric::all();

        return view('statya', ['post' => $post, 'rubrics' => $rubrics, 'header' => $rubric_name, 'capacity' => $capacity, 'registrable' => $is_registrable, 'cancelable' => $is_cancelable, 'rubrics_id' => $rubrics_id, 'post_id' => $post_id]);
    }

    function login()
    {
        $rubrics = Rubric::all();

        return view('auth', ['rubrics' => $rubrics]);
    }

    function loginUser(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string|exists:users,login',
            'password' => 'required|string',
        ]);

        $is_auth = Auth::attempt($validated);

        if ($is_auth) {
            if (Auth::user()->isAdmin()) {
                return redirect(route('admin.index'));
            }
            return redirect(route('lk.index')); // TODO редирект на админа или личный кабинет
        } else {
            return back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    function registration()
    {
        $rubrics = Rubric::all();

        return view('registration', ['rubrics' => $rubrics]);
    }

    function registerUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'regex:/^[А-Яа-яЁё\s\-]+$/u'],
            // 'email' => ['required', 'email', 'unique:users,email'],
            'email' => ['required', 'email'],
            'login' => ['required', 'unique:users,login'],
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])[a-zA-Z]+$/',
            ],
            'password_confirmation' => ['required', 'same:password'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg', 'max:2048'],
        ]);

        $user = new User();
        $user['name'] = $validated['name'];
        $user['email'] = $validated['email'];
        $user['login'] = $validated['login'];
        $user['password'] = bcrypt($validated['password']);
        $user['image'] = $request['image']->store('images', 'public');
        $user['admin'] = false;

        $user->save();

        Auth::attempt(['email' => $validated['email'], 'login' => $validated['login'], 'password' => $validated['password']]);

        return redirect(route('home'));
    }

    function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
