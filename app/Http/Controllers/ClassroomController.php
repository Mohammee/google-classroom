<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\Scopes\UserClassroomScope;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->authorizeResource(Classroom::class);
    }

    public function download()
    {

        $path = request()->query('linkFile');

       if($path && Storage::disk('uploads')->exists($path)){
//          $file =  public_path('uploads/'. $path);
//          dd($file);
//           $file = Storage::disk('uploads')->path($path);
           $file = Storage::disk('uploads')->get($path);
           return response()->download(file: $file);
       }

       //flash message

        //redirect

    }
    public function index(): Renderable|View
    {
//        $this->authorize('view-any', Classroom::class);
//        dd(\DB::table('classrooms')->whereNotNull('deleted_at')->orderBy('created_at', 'desc')->get());
//        dd(Classroom::latest()->dd());

        //ui
        //breeze
        //fortify => only backend
        //stream => use veu and liveware

//        (Auth::logout());
//        dd(auth()->user());
//session()->regenerate(true);
//dd(session()->getId(), auth()->user(), session()->all());
        //        session()->put('success', 'value');

//        session()->forget('success');
//        dd(session()->all());
//        session()->reflash();
//        return redirect()->to('/classrooms/1');
        $classrooms = Classroom::status()->latest()->recent()
//            ->withGlobalScopes()
//            ->withoutGlobalScope('user')
//                ->withoutGlobalScope(UserClassroomScope::class)
            ->get();
//dd($classrooms);
        //return response: view, redirect, json, file, string

//        return Redirect::to('/users');
//        return Redirect::away('https://www.google.com');
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
//        Classroom::withTrashed()->findOrFail($id);
//        dd(session()->all());

        $invitation_link = (URL::temporarySignedRoute('classrooms.join', now()->addMinute(), ['classroom' => $classroom->id, 'code' => $classroom->code]));
        return view('classrooms.show', compact('classroom', 'invitation_link'));
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
                    'dimensions:min_width=100,min_height=120',
//                    Rule::dimensions([
//                        'min_width' => 600,
//                        'min_height' => 300
//                    ])
                ]
            ]);

//           dd($request->all(), $validated);
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
        //form creating listener
//        $validated['code'] = Str::random(8);
//        $validated['user_id'] = Auth::id(); //Auth::user()->id
//        Classroom::query()->create($request->all());

//        $classroom = new Classroom($request->all());
//        $classroom->save();

        try {
            DB::beginTransaction();

//            DB::transaction();

            $classroom = new Classroom();
            $classroom->fill($validated)->save();

            $classroom->join(Auth()->id(), 'teacher');

            DB::commit();
        }catch(QueryException $e){
            DB::rollBack();

            return back()->with('error', $e->getMessage())->withInput();
        }


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

        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $classroom->image ?? Str::random(40) . $file->getClientOriginalExtension();

            $path = $file->storeAs('/images', basename($path), ['disk' => 'uploads']);

//            Classroom::deleteImage($classroom->image);
//            $path = Classroom::uploadImage($request->file('image'));
            $validated['image'] = $path;

        }

        $classroom->update($validated);

//        session()->flash('error', 'Something errors.');
        return Redirect::back()->with('success', 'Item updated successfully.');

    }

    public function destroy($id)
    {

//        Classroom::destroy($id);

        $item = Classroom::query()->find($id);
//        Classroom::deleteImage($item->image);

        $item->delete();

        return redirect()->back()->with('success', 'Classroom deleted successfully.');
    }

    public function trashed()
    {
       $classrooms = Classroom::onlyTrashed()->latest('deleted_at')->get();

       return view('classrooms.trashed', compact('classrooms'));
    }

    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();

        return redirect()->route('classrooms.index')->with('success', "Classroom ({$classroom->name}) restored.");
    }

    public function forceDeleted($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->forceDelete();

        //form event forceDeleted
//        Classroom::deleteImage($classroom->image);

        return \redirect()->back()->with('success', "Classroom {$classroom->name} force deleted." );

    }

    public function chat(Classroom $classroom)
    {
        return view('classrooms.chat', [
            'classroom' => $classroom,
        ]);
    }
}
