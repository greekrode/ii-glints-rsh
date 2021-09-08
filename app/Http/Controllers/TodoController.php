<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FileController;

class TodoController extends Controller
{

    protected $user;
    protected $FileController;


    public function __construct(FileController $filecontroller)
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
        $this->FileController = $filecontroller;

    }

    public function index()
    {
        $todos = $this->user->Todos()->get(['id','title','body','completed','created_by']);
        return response()->json($todos->toArray());
    }
/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string',
                'body' => 'required|string',
                'completed' => 'required|boolean',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );

        }

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->body = $request->body;
        $todo->completed = $request->completed;


        if ($this->user->todos()->save($todo)) {  
            if ($request->image != null) {
                $this->FileController->store($request, $todo->id);
            }
            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, this to do list could not be saved.',
                ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */

    public function destroy(Todo $todo)
    {
        if ($todo->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the todo could not be deleted.',
                ]
                );
        }

    }//end destroy()


    protected function guard()
    {
        return Auth::guard();

    }//end guard()


}//end class
