<?php

namespace App\Http\Controllers;

use App\Enums\ClassworkType;
use App\Events\ClassworkCreated;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\LazyCollection;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{
    public function __construct()
    {
//        $this->authorizeResource(Classwork::class, 'classwork');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Classroom $classroom)
    {
        // need to add foreign key
//        dd(Classroom::with('user:id,name', 'streams:id,classroom_id,content')->get()
//            ->toArray());
        //user policy yu can user(viewAny)
        $this->authorize('view-any', [Classwork::class, $classroom]);
//        header('Content-Type: image/jpeg');
//       dd( Comment::first()->commentable);

//        dd(DB::query()->orderBy('id'));
//        dd(Comment::whereHasMorph('commentable', ['classwork'])->dd());
//        $classroom->classworks()->chunk(100, function ($classworks){
//
//            foreach($classworks as $classwork){
//
//            }
//        });die;
//        dd(ini_get('memory_limit'));

//        foreach($classroom->classworks()->lazy() as $item ){
//            dump($item);
//            $item = null;
////            unset($item);
//            dump($item);
//        }; die;
//        foreach($classroom->classworks()->lazy() as $value){
//dump($value);
//        }

//        die;

//        $classworks = Classwork::where('classroom_id', '=', $classroom->id)
//            ->where('type', '=', Classwork::TYPE_ASSIGNMENT)->get();
//
//        $classworks = $classroom->classworks;
//
//        $classworks = $classroom->classworks()
//            ->where('type', '=', Classwork::TYPE_ASSIGNMENT)->get();


        $type = $this->getType();
        // lazy good for memory
        $classworks = $classroom->classworks()->with('topic')
            ->filter($request->query())
            ->latest('published_at')
            ->withCount([
                'users as assigned_count',
                'users as turnedin_count' => function ($query) {
                    $query->where('classwork_user.status', '=', 'submitted');
                },
                'users as graded_count' => function ($query) {
                    $query->whereNotNull('classwork_user.grade');
                }
            ])
            ->where(function ($query) {
                $query->whereHas('users', function ($query) {
                    $query->where('id', '=', Auth::id());
                })->orWhereHas('classroom.teachers', function ($query) {
                    $query->where('id', '=', Auth::id());
                });
            })
            /*->where(function ($query) {
                $query->whereRaw(
                    'EXISTS (SELECT 1 FROM classwork_user
            WHERE classwork_user.classwork_id = classworks.id
            AND classwork_user.user_id = ?)',
                    [Auth::id()]
                );

                $query->orWhereRaw(
                    'EXISTS (SELECT 1 FROM classroom_user WHERE
                    classroom_user.classroom_id = classworks.classroom_id
                    AND
                 classroom_user.user_id = ? AND role = ?)',
                    [Auth::id(), 'teacher']
                );
            })*/
            ->paginate(2);
//        ->simplePaginate(2);
//            ->get();
//            ->lazy();

//        dd($classworks->groupBy('type'));
//        event('classwork-created', [$classroom, $classworks]);

//        event(new ClassworkCreated($classworks->first()));
//        ClassworkCreated::dispatch($classworks->first());


//        dd($classworks->groupBy('type'));
        return view('classworks.index', [
            'classroom' => $classroom,
//            'classworks' => $classworks->groupBy('topic_id'),
            'classworks' => $classworks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Classroom $classroom)
    {
        //used queue
        ClassworkCreated::dispatch(Classwork::first());

//        $response = Gate::inspect('classworks.create', [$classroom]);
//        if (!$response->allowed()) {
//            abort(403, $response->message() ?? '');
//        }

//        if(!Gate::allows('classworks.create', $classroom)){
//            abort(403);
//        }
//        abort_if(Gate::denies('classworks.create', [$classroom]), 403);
//        Gate::authorize('classworks.create', [$classroom]);

        //use policies
        $this->authorize('create', [Classwork::class, $classroom]);

        $type = $this->getType();
        $classwork = new Classwork();
        return view('classworks.create', compact('classroom', 'type', 'classwork'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
        //policy return message with unauthorized vs gate return message forbidden
        $this->authorize('create', [Classwork::class, $classroom]);
        $type = $this->getType();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'students' => ['array'],
            'options.grade' => [Rule::requiredIf(fn() => $type == 'assignment'), 'numeric', 'min:0'],
            'options.due' => ['nullable', 'date', 'after:published_at']
        ]);

//        dd($request);

        $request->merge([
            'user_id' => Auth::id(),
            'type' => $this->getType(),
//            'classroom_id' => $classroom->id
//            'options' => json_encode([
//                'due' => $request->get('due'),
//                'grade' => $request->get('grade')
//            ])
        ]);

        try {
            DB::transaction(function () use ($classroom, $request) {
                $classwork = $classroom->classworks()->create($request->all());
//        $classwork = Classwork::create($request->all());
//            dd($request->all());

                $classwork->users()->attach($request->input('students'));

                //event
                ClassworkCreated::dispatch($classwork);
            });
        } catch (QueryException $e) {
            return back()->with('error', $e->getMessage());
        }


        /*
         * use localization when set locale ar it search for ar folder for key if key not exists print this key
                key is case-sensitive
            laravel first search in json file then search in other files so make attention when name key if key name as file laravel
        return file
         */
        return Redirect::route('classrooms.classworks.index', $classroom->id)
            ->with('success', __('Classworks Created!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
        //you can pass object
        $this->authorize('view', [$classwork, $classroom]);

//        if(Gate::denies('classworks.view', [$classwork])){
//            abort(403);
//        }


//        Gate::authorize('classworks.view', [$classwork]);

        $submissions = Auth::user()->submissions()->where('classwork_id', $classwork->id)->get();
//        $classroom->load('comments.user');
        return view('classworks.show', compact('classroom', 'classwork', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Classroom $classroom, Classwork $classwork)
    {
//        $type = $this->getType();
//        dd($classwork->published_at);

        $assigned = $classwork->users()->pluck('id')->toArray();
        return view('classworks.edit', compact('classroom', 'classwork', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
//        return strip_tags($request->description, ['a', 'h1', 'p', 'ol', 'li']);

//        $type = $this->getType();
        $type = $classwork->type->value;
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'students' => ['array'],
            'options.grade' => [Rule::requiredIf(fn() => $type == 'assignment'), 'numeric', 'min:0'],
            'options.due' => ['nullable', 'date', 'after:published_at']
        ]);

        $classwork->update($request->all());
//$classwork->users()->syncWithoutDetaching(); //making error if duplicate ids
        $classwork->users()->sync($request->input('students'));

        return Redirect::back()->with('success', __('Classwork updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, Classwork $classwork)
    {
        //
    }

    protected function getType()
    {
        $type = ClassworkType::tryFrom(request()->query('type', ''));
//        $allowed_types = [
//            Classwork::TYPE_ASSIGNMENT,
//            Classwork::TYPE_MATERIAL,
//            Classwork::TYPE_QUESTION
//        ];
//
//
//        if (!in_array($type, $allowed_types)) {
//            $type = Classwork::TYPE_ASSIGNMENT;
//        }

        return $type->value ?? ClassworkType::tryFrom('assignment');
    }
}
