<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\news;
use App\Models\categories;
use Illuminate\Validation\Rule;
class IndexController extends Controller
{
    function welcome($sorted = NULL) {
        if($sorted != NULL){
            $exists = news::where('cat_id', $sorted)->exists();
            if (!$exists) {
                return redirect('/');
            }
            $news = news::getSorted($sorted);
        }
        else {
        $news = news::getAll();
        }
        $few = news::orderBy('created_at','desc')->take(6)->get(['img','id']);
        $nav = news::forNav();
        return view('index',['news' => $news,'imgs' => $few,'nav' => $nav]);
    }
    function in($id) {
        $news = news::findOrFail($id);
        $nav = news::forNav();
        return view('article',['news' => $news,'nav' => $nav]);
    }
    function delete($id) {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/');
        }
        $news = news::findOrFail($id);
        $news->delete();
        return redirect('/');
    }
    function openCreate() {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/');
        }
        $nav = news::forNav();
        $cat = categories::forCreate();
        return view('create',['nav' => $nav, 'cat' => $cat]);
    }
    function add(Request $request) {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/');
        }
        if (News::where('link', $request->link)->where('cat_id', $request->cat_id)->exists()) {
            return back()->withErrors(['link' => 'Заголовок с такой рубрикой уже существует!'])->withInput();
        }
        else {
        $validated = $request->validate([
                'link' => 'required','string','max:255',
                'head' => 'required|string|max:700',
                'text' => 'required|string|max:2048',
                'cat_id' => 'required|int|min:1|exists:categories,id',
                'img' => 'nullable|image|mimes:jpeg,gif,png,jpg|max:2048']);
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('images', 'public');
            $validated['img'] = $path;
        }
            $news = new news;
            $news = $news->add($validated);
            return redirect('/'); }
    }
    function addCat(Request $request) {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/');
        }
        $validated = $request->validate([
            'cat' => 'required|string|max:255|unique:categories,category']);
        categories::newCat($validated['cat']);
        return redirect('/');
    }
}
