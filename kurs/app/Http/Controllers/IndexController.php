<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use App\Models\rubrics;
use App\Models\courses;
use App\Models\courseUsers;
use App\Models\User;
use Illuminate\Validation\Rule;
class IndexController extends Controller
{
    function welcome(Request $request) {

        $filter = $request->input('filter');

        if ($filter === 'active') {
            $courses = courses::where('date', '>=', now())->get();
            $head = 'Активные курсы';
        } elseif ($filter === 'past') {
            $courses = courses::where('date', '<', now())->get();
            $head = 'Завершенные курсы';
        } elseif ($filter === 'empty') {
            $courses = courses::where('capacity', '=', 0)->get();
            $head = 'Заполненные курсы';
        } else {
            $courses = courses::all();
            $head = '';
        }

        $rubrics = rubrics::getRubrics();
        return view('index',['rubrics' => $rubrics,'courses' => $courses,'head' => $head]);
    }
    function filtered($id) {
        $rubrics = rubrics::getRubrics();
        $courses = courses::getFiltered($id);
        return view('index',['rubrics' => $rubrics,'courses' => $courses]);
    }
    function in($id,$user_id = null) {
        $rubrics = rubrics::getRubrics();
        $details = courses::findOrFail($id);
        
        $cap = courses::where('id',$id)->value('capacity');

        $course = courses::find($id);
        $courseTime = $course->date;
        if(auth()->user() != null && $user_id != null) {
            $isAlready = courseUsers::where('user_id','=',$user_id)->where('course_id','=',$id)->exists();
        }
        else { 
            $isAlready = FALSE;
        }
        if ($cap > 0) {
            $registrable = TRUE;
        }
        else {
            $registrable = FALSE;
        }
        $time = now();
        if ($time > $courseTime->copy()->subHours(24)) {
            $cancelable = FALSE;
        }
        else {
            $cancelable = TRUE;
        }
        $courseUserId = null;
        if (auth()->user() && $user_id) {
            $courseUser = courseUsers::where('user_id', $user_id)->where('course_id', $id)->first();
            $courseUserId = $courseUser ? $courseUser->id : null;
        }
        return view('article',['details' => $details,'rubrics' => $rubrics,'registrable' => $registrable, 'cancelable' => $cancelable, 'isAlready' => $isAlready, 'courseUserId' => $courseUserId]);
    }

    function lk($login,Request $request) {
        $filter = $request->input('filter');
        $user = User::where('login', $login)->firstOrFail();
        $rubrics = rubrics::getRubrics();

        if ($filter === 'active') {
            $courses = courseUsers::with(['user','course'])->whereHas('course', function($query) { $query->where('date', '>=', now()); })->where('user_id', $user->id)->get();
            $head = 'Активные курсы';
        } elseif ($filter === 'past') {
            $courses = courseUsers::with(['user','course'])->whereHas('course', function($query) { $query->where('date', '<', now()); })->where('user_id', $user->id)->get();
            $head = 'Завершенные курсы';
        } elseif ($filter === 'empty') {
            $courses = courseUsers::with(['user','course'])->whereHas('course', function($query) { $query->where('capacity', '=', 0); })->where('user_id', $user->id)->get();
            $head = 'Заполненные курсы';
        } else {
            $courses = courseUsers::with(['user','course'])->where('user_id', $user->id)->get();
            $head = '';
        }
        
        if (auth()->user()->id !== $user->id) {
        abort(403);}
        return view('lk',['courses' => $courses, 'rubrics' => $rubrics,'user' => $user]);
    }
    function admin() {
        if(auth()->user()->admin === 1){
            $courses = courses::all();
            $rubrics = rubrics::getRubrics();
            $list = courseUsers::with(['user','course'])->get();
            return view('admin',['courses' => $courses,'rubrics' => $rubrics,'list' => $list]);}
        else {
            $courses = courses::all();
            $rubrics = rubrics::getRubrics();
            return view('index',['courses' => $courses,'rubrics' => $rubrics,'head' => '']);}
    }
    function delete($id) {
        if (!auth()->check() || auth()->user()->admin !==1) {
            return redirect('/');
        }
        $access = courseUsers::with(['user','course'])->where('course_id','=',$id)->exists();
        if($access) {
            return redirect()->back()->withErrors(['error' => 'Вы не можете удалить курс, так как на него существует запись']);
        }
        else {
        $courses = courses::findOrFail($id);
        $courses->delete();
        return redirect()->back();
        }
    }
    function openCreate() {
        if (!auth()->check() || auth()->user()->admin !== 1) {
            return redirect('/');
        }
        $rubrics = rubrics::getRubrics();
        return view('create',['rubrics' => $rubrics]);
    }
    function add(Request $request) {
        if (!auth()->check() || auth()->user()->admin !== 1) {
            return redirect('/');
        }

        
        $validated = $request->validate([
                'title' => 'required|string|max:255',
                'lid' => 'required|string|max:700',
                'content' => 'required|string|max:2048',
                'rubric_id' => 'required|int|min:1|exists:rubrics,id',
                'image' => 'required|image|mimes:jpeg,gif,png,jpg|max:2048',
                'date' => 'required|date|after:today',
                'capacity' => 'required|int|min:1|max:30']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images','public');
            $validated['image'] = $path;
        }
            $course = new courses;
            $course = $course->add($validated);
            return redirect('/'); 
    }
    function addLan(Request $request) {
        if (!auth()->check() || auth()->user()->admin !== 1) {
            return redirect('/');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rubrics,name']);
        $rub = new rubrics;
        $rub->add($validated);
        return redirect()->back();
    }
    function deleteCourseUser($id) {
        if (!auth()->check() || auth()->user()->admin !== 1) {
            return redirect('/');
        }
        $record = courseUsers::findOrFail($id);
        $record->delete();
        return redirect()->back();
    }
    function unsub($id) {
        $record = courseUsers::with(['user','course'])->find($id);
        if (!$record || $record->user_id !== auth()->user()->id) {
            return redirect()->back()->withErrors(['error' => 'Вы не записаны на этот курс']);
        }
        $course = courses::findOrFail($record->course->id);
        if(now() < $course->date->copy()->subHours(24)) {
            $course->capacity++;
            $course->save();
            $record->delete();
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['error' => 'Вы не можете отписаться от курса менее чем за 24 часа до его начала']);
        }
    }
    function sub($id, Request $request) {

        $validated = $request->validate([
            'FIO' => 'required|string|max:255',
            'age' => 'required|int|min:1|max:100',
            'city' => 'required|string|max:255']);
        $course = courses::findOrFail($id);
        $userId = auth()->user()->id;
        $already = courseUsers::where('user_id', $userId)->where('course_id', $id)->exists();
        if ($already) {
            return redirect()->back()->withErrors(['error' => 'Вы уже записаны на этот курс']);
        }
        if($course->capacity > 0 && now() < $course->date->copy()->subHours(24)) {
            $course->capacity--;
            $course->save();
            courseUsers::create(['user_id' => $userId, 'course_id' => $id, 'FIO' => $validated['FIO'], 'age' => $validated['age'], 'city' => $validated['city']]);
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['error' => 'Вы не можете записаться на курс.']);
        }
    }
}
