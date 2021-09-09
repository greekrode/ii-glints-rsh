<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }

    public function index()
    {
        $todos = $this->user->Todos()->get();
        if (count($todos)==0) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, you have no todo lists',
                ]);
        }
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
        if ($request->image != null) {
            $imgname = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->store('public/images');
            $todo = new Todo();
            $todo->title = $request->title;
            $todo->body = $request->body;
            $todo->completed = $request->completed;
            $todo->img_title = $imgname;
            $todo->img_url = $path;
        }
        else {
            $todo = new Todo();
            $todo->title = $request->title;
            $todo->body = $request->body;
            $todo->completed = $request->completed;
            $todo->img_title = null;
            $todo->img_url = null;
        }

        if ($this->user->todos()->save($todo)) {  
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
                ],400);
        }
    }

    public function update(Request $request, $id)
    {
        if (Todo::where('id','=',$id)->first()) {
            $todo = Todo::find($id);
            if ($request->title == null) {
                $title = $todo->title;
            }
            else{
                $title = $request->title;
            }
            if ($request->body == null) {
                $body = $todo->body;
            }
            else{
                $body = $request->body;
            }
            if ($request->completed == null) {
                $completed = $todo->completed;
            }
            else{
                $completed = $request->completed;
            }
            if ($request->image != null) {
                $imgname = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->store('public/images');
                $todo->update([
                    'title'=>$title,
                    'body'=>$body,
                    'completed'=>$completed,
                    'img_title'=>$imgname,
                    'img_url'=>$path,
                    ]);
            }
            else {
                $todo->update([
                    'title'=>$title,
                    'body'=>$body,
                    'completed'=>$completed,
                    'img_title'=>$todo->img_title,
                    'img_url'=>$todo->img_url,
                    ]);
            }
            $todo = Todo::find($id);

            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        }
        else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, this to do list could not be updated.',
                     ],400);
                
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
                ],400
                );
        }

    }//end destroy()

    public function show($created_by){
        return Todo::where('created_by','=',$created_by);
    }
    protected function guard()
    {
        return Auth::guard();

    }//end guard()


}//end class
