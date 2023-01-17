<?php

namespace App\Http\Controllers;

use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
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
        $tags = Tag::all()->pluck('name', 'id');
        return view('author.tags.create', compact('tags'));
    }


    public static function allTags()
    {
        $tags = Tag::all();

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
        $tagNames = explode(',', $request->get('tags'));
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            //$post->tags()->create(['name'=>$tagName]);
            //Or to take care of avoiding d
            $tagName = TagController::generateTagName($tagName);

            $tag = Tag::firstOrCreate(['name' => $tagName]);
//            if($tag)
//            {
//                $tagIds[] = $tag->id;
//            }
        }

        return redirect()->back()
            ->with('success', ' successfully added,Thank you you can now use them');
//        dd($tagIds);
    }


    /**
     * @param $tagName
     * @return string|string[]|null
     */
    public static function generateTagName($tagName)
    {
        /*
          Trim whitespace from beginning and end of tag
        */
        $name = trim($tagName);

        /*
          Convert tag name to lower.
        */
        $name = strtolower($name);

        /*
          Convert anything not a letter or number to a dash.
        */
        $name = preg_replace('/[^a-zA-Z0-9]/', '-', $name);

        /*
          Remove multiple instance of '-' and group to one.
        */
        $name = preg_replace('/-{2,}/', '-', $name);
        /*
          Get rid of leading and trailing '-'
        */
        $name = trim($name, '-');

        /*
          Returns the cleaned tag name
        */
        return $name;
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
        $data = PostTag::query()->where('tag_id', '=', $id)->get();
        return view('tag', ['data' => $data, 'tags' => HomeController::getTagsByGroup()]);
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

    public function getTag()
    {
        $tag = Tag::all();
        return json_encode($tag);
    }
}
