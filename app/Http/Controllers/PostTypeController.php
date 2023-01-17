<?php

namespace App\Http\Controllers;

use App\Models\PostType;
use Illuminate\Http\Request;

class PostTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|min:2|string',
            ]
        );

        PostType::query()->create([
            'name' => $request->get('new'),
        ]);

        return redirect()->back()
            ->with('success', ' successfully added,Thank you you');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public static function types(){
       return PostType::all(['id','name']);
    }

    public static function typeAudio()
    {
        $postType=PostType::query()->select('id')->audio()->first()['id'];
        return empty($postType)?null:$postType;
    }

    public static function typeArticle()
    {
        return PostType::query()->select('id')->article()->first()['id'];
    }
}
