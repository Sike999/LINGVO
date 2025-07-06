<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rubric;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function index(Request $request)
    {
        $rubrics = Rubric::all();

        $filter = $request->input('filter');

        if ($filter === 'active') {
            $posts = Course::where('date', '>=', now())->get();
            $add = 'Активные курсы';
        } elseif ($filter === 'past') {
            $posts = Course::where('date', '<', now())->get();
            $add = 'Завершенные курсы';
        } elseif ($filter === 'empty') {
            $all = Course::withCount('users')->get();
            $posts = $all->filter(function ($course) {
                return $course->users_count >= $course->capacity;
            });
            $add = 'Заполненные курсы';
        } else {
            $posts = Course::all();
            $add = '';
        }
        return view('admin', ['header' => $add, 'posts' => $posts, 'rubrics' => $rubrics]);
    }

    function post(int $id)
    {
        $post = Course::withCount('users')->with('users:id,login')->findOrFail($id);
        $rubrics = Rubric::all();

        $capacity = $post['capacity'] - $post['users_count'];

        return view('admin-post', ['post' => $post, 'capacity' => $capacity, 'rubrics' => $rubrics, 'data' => $post->users]);
    }

    function create()
    {
        $rubrics = Rubric::all();

        return view('create', ['rubrics' => $rubrics]);
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lid' => 'required|string|max:255',
            'content' => 'required|string|max:2048',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'date' => 'required|date',
            'image' => 'required|image|max:2048',
            'capacity' => 'required|integer|min:1',
        ]);

        // $userTz = new DateTimeZone($validated['timezone']);
        // $scheduledAtUtc = Carbon::createFromFormat('Y-m-d\TH:i', $validated['date'], $userTz)->setTimezone('UTC');
        // $scheduled = Carbon::createFromFormat('Y-m-d\TH:i', $validated['date']);
        // $now = now();

        // if ($scheduled->lt($now)) {
        //     return back()->withErrors(['scheduled_at' => 'The date must be in the future.']);
        // }

        $post = new Course();

        $post['title'] = $request['title'];
        $post['lid'] = $request['lid'];
        $post['content'] = $request['content'];
        $post['rubric_id'] = $request['rubric_id'];
        $post['date'] = $validated['date'];
        $post['image'] = $validated['image']->store('images', 'public');
        $post['capacity'] = $validated['capacity'];

        $post->save();

        return redirect(route('statya', [$post['rubric_id'], $post['id']]));
    }

    function destroy(Request $request, int $post_id)
    {
        $user_id = $request->input('user_id');
        $post = Course::findOrFail($post_id);
        if ($post->users->contains($user_id)) {
            $post->users()->detach($user_id);
        }
        return redirect()->back();
    }

    function destroyPost(int $rubrics_id, int $post_id)
    {
        // $post = Course::findOrFail($post_id);
        // $post->delete();
        // return redirect(route('home'));

        $post = Course::withCount('users')->findOrFail($post_id);

        if ($post->users_count === 0) {
            $post->delete();
            return redirect()->back()->with('success', 'Курс успешно удалён');
        }

        return redirect()->back()->withErrors([$post_id => 'Нельзя удалить курс с участниками']);
    }

    function createRubric()
    {
        $rubrics = Rubric::all();

        return view('rubric', ['rubrics' => $rubrics]);
    }

    function storeRubric(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rubrics,name',
        ]);

        $rubric = new Rubric();
        $rubric['name'] = $validated['name'];

        $rubric->save();

        return redirect(route('rubrika', ['rubrics_id' => $rubric['id']]));
    }
}
