<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $useer;

    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }
    public function index()
    {
        //
        $todo = $this->user->todos()->get(['title','body','completed','created_by']);
        return response()->json($todos->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);

        if (($validator)->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ], 400);
        }
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->body= $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)){
            return response()->json([
                'status'=>true,
                'todo'=>$todo,
            ]);
        }
        else {
            return response()->json([
                'status'=> false,
                'message' => 'Oops, the todo could not be saved.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
        return $todo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Todo $todo)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ], 400);
        }

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->body= $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)){
            return response()->json([
                'status'=>true,
                'todo'=>$todo,
            ]);
        }
        else {
            return response()->json([
                'status'=> false,
                'message' => 'Oops, the todo could not be updated.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */

    protected function guard(){
        return Auth::guard();
    }
}
