<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public static  $AUDIOTYPE='audio';
    public  static  $ARTICLETYPE='article';
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
     */
    public function show($id)
    {
        //
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
    public static function roles(){
        return Type::all(['id','name']);
    }
    public static function  admins(){
        return Type::query()->admin()->first()->users()->get();
    }
    public static function  authors(){
        return Type::query()->author()->first()->users()->get();
    }
    public static function  authors_query(){
        return Type::query()->author()->first()->users();
    }
    public static function  registeredUsers(){
        return Type::query()->registeredUser()->first()->users()->get();
    }


    public static function adminRoleId()
    {
        $ype=Type::query()->select('id')->admin()->first()['id'];
        return empty($ype)?null:$ype;
    }

    public static function authorRoleId()
    {
        return Type::query()->select('id')->author()->first()['id'];
    }

    public static function registeredUserRoleId()
    {
        return Type::query()->select('id')->registeredUser()->first()['id'];
    }
}
