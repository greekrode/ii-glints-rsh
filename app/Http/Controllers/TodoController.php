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

    }//end __construct()


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = $this->user->todos()->get(['id', 'title', 'body', 'completed', 'created_by']);
        return response()->json($todos->toArray());

    }//end index()


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */



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
