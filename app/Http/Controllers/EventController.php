<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //

    private $user;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->user = \Auth::user();
        $this->middleware('author');
    }

    public static function getLatestEvents()
    {
        return Event::query()->limit(6);
    }
//    public function create(){
//        //
//        return view('author.events.create');
//    }

    public static function getAllEvents()
    {
        return Event::query()->paginate(20);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        //
        return view('author.events.index', ['events' => Event::all()->where('user_id', '=', \Auth::id())->reverse()]);
    }

    public function show(Event $event)
    {
        $events = Event::all()->reverse();
        return view('author.events.show', compact('event', 'events'));
    }

//$this->validate($request,$this->validationRules($key,$value));
    public function store(Request $request)
    {


        $this->validate($request,
            [
                'title' => 'required|min:2|string',
                'venue' => 'required|min:2|string',
                'event_image_file' => 'sometimes|file|image',
                'note' => 'required|min:2|string',
                'starting_time' => 'required|string',
                'end_time' => 'sometimes|string',
                'starting_date' => 'required|date',
                'end_date' => 'sometimes|date',
                'price' => 'sometimes|numeric',
            ]
        );
//        dd($request);
        $event = Event::query()->create([
            'user_id' => \Auth::id(),
            'title' => $request->get('title'),
            'venue' => $request->get('venue'),
            'poster_image' => "add later",
//            'poster_image'=>$request->input('event_image_file'),
            'note' => $request->get('note'),
            'starting_time' => $request->get('starting_time'),
            'ending_time' => $request->get('end_time'),
            'starting_date' => $request->get('starting_date'),
            'ending_date' => $request->get('end_date'),
            'price' => 0,
        ]);
        PostController::storeFile($event, 'event_image_file');
        return redirect()->back()->with('success', ' successfully added,Thank you you');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('author.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $eventUpdate = $event->update($this->updateDataList($request));
        if ($eventUpdate) {
            PostController::storeFile($event, 'event_image_file');
            return redirect()->back()->with('eventUpdated', 'This inform you that your update has been successful');
        }

        return back()->withInput();
    }

    private function updateDataList(Request $request)
    {
        $array1 = [
            'title' => $request->get('title'),
            'venue' => $request->get('venue'),
            'note' => $request->get('note'),
            'starting_time' => $request->get('starting_time'),
            'ending_time' => $request->get('end_time'),
            'starting_date' => $request->get('starting_date'),
            'ending_date' => $request->get('end_date'),
            'price' => 0,
        ];
        if ($request->input('event_image_file') != null) {
            $array1 = array_merge($array1, ['poster_image' => 'changed']);
        }
        return $array1;

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
        Event::destroy($id);
        return redirect()->back()
            ->with('success', ' successfully deleted,Thank you you');
    }
}
