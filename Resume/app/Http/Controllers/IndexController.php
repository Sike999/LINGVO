<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Firm;
use App\Models\Staff;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Storage;
class IndexController extends Controller
{
    function Index() {
        $res = Person::getMembers();
        return view('page',['res'=>$res,'header' => 'Резюме и вакансии']);
    }
    function getFiveToFift() {
        $res = Person::first();
        return view('firstQuery',['res'=>$res]);
    }
    function getFioStage() {
        $res = Person::second();
        return view('secondQuery',['res'=>$res]);
    }
    function getTotalQuan() {
        $res = Person::third();
        return view('thirdQuery',['res'=>$res]);
    }
    function getProfHas() {
        $res = Person::fourth();
        return view('fourthQuery',['res'=>$res]);
    }
    
    function delete($id) {
        $candidate = Person::findOrFail($id);
        $candidate->delete();
        return redirect()->back()->with('success', 'Кандидат удален');
    }
    function openEdit($id) {
        $person = Person::findOrFail($id);
        $staffs = Staff::select('id','staff')->get();
        return view('update',['res' => $person,'staffs' => $staffs]);
    }
    function update(Request $request,$id) {

        $validated = $request->validate([
            'FIO' => 'required|string|max:255',
            'Staff' => 'required|integer|min:1|exists:staff,id',
            'Phone' => 'required|string|max:12',
            'Stage' => 'required|integer|min:1|max:50',
            'Image' => 'nullable|image|mimes:jpeg,gif,png,jpg|max:2048'
        ], [
            'Image.image' => 'Файл должен быть изображением',
            'Image.mimes' => 'Допустимы только JPG, PNG, JPEG, GIF',
            'Image.max' => 'Максимальный размер 2MB'
        ]);

        $person = Person::findOrFail($id);

        if ($request->hasFile('Image')) {
            if ($person->Image) {
                Storage::disk('public')->delete($person->Image);
            }
            $path = $request->file('Image')->store('people', 'public');
            $validated['Image'] = $path;
        } else {
            if ($person->Image) {
                Storage::disk('public')->delete($person->Image);
            }
            $validated['Image'] = null;
        }

        $person->change($validated['FIO'],$validated['Staff'],$validated['Phone'],$validated['Stage'],$validated['Image'] ?? null);
        return redirect('/')->with('success', 'Данные обновлены');
    }
    function openCreate() {
        $staffs = Staff::select('id','staff')->get();
        return view('create',['staffs' => $staffs]);
    }
    function create(Request $request) {
            $validated = $request->validate([
                'FIO' => 'required|string|max:255',
                'Staff' => 'required|integer|min:1|exists:staff,id',
                'Phone' => 'required|string|max:12',
                'Stage' => 'required|integer|min:1|max:50',
                'Image' => 'nullable|image|mimes:jpeg,gif,png,jpg|max:2048'
            ], [
                'Image.image' => 'Файл должен быть изображением',
                'Image.mimes' => 'Допустимы только JPG, PNG, JPEG, GIF',
                'Image.max' => 'Максимальный размер 2MB'
            ]);

        $person = new Person;

        if ($request->hasFile('Image')) {
            $path = $request->file('Image')->store('people', 'public');
            $validated['Image'] = $path;
        }

        $person = $person->add($validated);
        return redirect('/')->with('success', 'Новая запись добавлена!');
    }
}
