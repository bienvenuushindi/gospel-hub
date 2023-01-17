<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingController extends Controller
{
    public function __construct()
    {
        $this->middleware('author');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('author.teaching.index', ['list' => $this->listAll()]);
        //
    }

    private function listAll()
    {
        return \Auth::user()->teachings()->get()->reverse();
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $this->validate($request,
            [
                'add_teaching' => 'required|min:2|string',
                'teaching_image_file' => 'sometimes|file|image',
            ]
        );

        $teaching = Teaching::query()->create([
            'theme' => $request->get('add_teaching'),
            'image' => $request->get('teaching_image_file'),
            'user_id' => Auth::id()
        ]);
        PostController::storeFile($teaching, 'teaching_image_file');

        return redirect()->back()
            ->with('success', ' successfully added,Thank you you');
        //
    }

    private function storeCover()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $teaching = Teaching::query()->find($id);
//        dd($teaching->posts()->orderByDesc('created_at')->get()->groupBy('post_type_id'));
        $posts = PostController::handlePost($teaching->posts()->orderByDesc('created_at')->get()->groupBy('post_type_id'));
//      dd($posts[0]);
        return view('author.teaching.show', ['item' => $teaching, 'posts' => $posts[0],'countByYears'=>PostController::posts_by_years(Auth::user())]);
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

//    public  function fetch($date,$month=null){
//        $teachings=Teaching::query();
////        dd(Auth::user()->posts()->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total")->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$date])->get());
//        if (isNull($month)) return $teachings->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$date]);
//
//         return $teachings->whereRaw("created_at = ?",[$date]);
//
//    }

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

    public function delete($id){
        $teaching=Teaching::query()->find($id);
        return view('author.teaching.delete', compact('teaching'));
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
        $teaching=Teaching::query()->select('image')->find($id);

            dd(File::exists(public_path('storage/').$teaching->image));
            if(File::exists(public_path('storage/').$teaching->image)) File::delete(public_path('storage/').$teaching->image);

        Teaching::destroy($id);
        return redirect()->back()
            ->with('success', ' successfully deleted,Thank you you');
    }
}
