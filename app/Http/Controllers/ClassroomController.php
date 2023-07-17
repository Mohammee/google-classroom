<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    public function index()
    {
        session()->put('successess', 'value');

//        dd(session()->all());
//        session()->reflash();
//        return redirect()->to('/classrooms/1');
        $classrooms = Classroom::all();
        return view('classrooms.index')->with('classrooms', $classrooms);
    }

    public function create()
    {
        return view('classrooms.create', ['classroom' => new Classroom]);
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function show(Classroom $classroom)
    {
        dd(session()->all());
        return $classroom;
    }

    public function store(Request $request)
    {


//        dd($request->all());
//        try {
           $validated = $request->validate([
                'name' => 'required|string|max:255',
                'section' => 'nullable|string|max:255',
                'subject' => 'nullable|string|max:255',
                'room' => 'nullable|string|max:255',
                'image' => [
                    'nullable',
                    'image',
                    Rule::dimensions([
                        'min_width' => 600,
                        'min_height' => 300
                    ])
                ]
            ]);

           dd($request->all(), $validated);
//        }catch(ValidationException $e){
            //you can use withInput flash with form wizard
//            return redirect()->back()->withInput()->withErrors($e->errors());
//        }
//        $classroom = new Classroom();
//        $classroom->name = $request->post('name');
//        $classroom->subject = $request->post('subject');
//        $classroom->section = $request->post('section');
//        $classroom->room = $request->post('room');
//        $classroom->code = Str::random(8);
//        $classroom->save();


        if($request->hasFile('image')){
            $file = $request->file('image');
//            $fileName = $file->store('/images', 'uploads');
//            $fileName = $file->storeAs('/images', 'image.jpg', 'uploads');

            $validated['image'] = Classroom::uploadImage($file);

        }

        //mass assigment
//        $request->merge(['code' => Str::random(8)]);
        $validated['code'] = Str::random(8);
//        Classroom::query()->create($request->all());

//        $classroom = new Classroom($request->all());
//        $classroom->save();

        $classroom = new Classroom();
        $classroom->fill($validated)->save();

        //Flash Message
        session()->flash('success', 'Item create successfully.');

        //PRG post redirect get
        return redirect()->to(url('/classrooms'));

    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
//        $rules = [
//            'name' => 'required|string|max:255',
//            'section' => 'nullable|string|max:255',
//            'subject' => 'nullable|string|max:255',
//            'room' => 'nullable|string|max:255',
//            'image' => [
//                'nullable',
//                'image',
//                'max:1024',
//                Rule::dimensions([
//                    'min_width' => 600,
//                    'min_height' => 300
//                ])
//            ]
//        ];
//
//        $messages = [
//            'required' => ':attribute Important',
//            'name.required' => 'The name is required.',
//            'image.max' => 'Image size greater than 1M.'
//        ];
//        $validated = $request->validate($rules, $messages);

        $validated = $request->validated();

        $classroom->update($validated);

        session()->flash('error', 'Something errors.');
        return Redirect::back()->with('success', 'Item updated successfully.');

    }

    public function destroy($id)
    {

//        Classroom::destroy($id);

        $item = Classroom::query()->find($id);
        $item->delete();

        return redirect()->back()->with('success', 'Classroom deleted successfully.');
    }
}
