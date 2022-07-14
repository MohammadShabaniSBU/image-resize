<?php

namespace App\Http\Controllers;

use App\Jobs\ResizeImageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request) 
    {
        $name = Str::random(5);
        $request->file('file')->storeAs('tmp/', "$name.png");

        ResizeImageJob::dispatch($name);

        return redirect('/');
    }
}
