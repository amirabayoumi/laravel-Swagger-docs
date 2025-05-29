<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorepicsRequest;
use App\Http\Requests\UpdatepicsRequest;
use App\Models\pics;

class PicsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = pics::all();
        return view('testindex', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testcreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorepicsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(pics $pics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pics $pics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepicsRequest $request, pics $pics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pics $pics)
    {
        //
    }
}
