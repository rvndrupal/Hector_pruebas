<?php

namespace App\Http\Controllers;

use App\Correos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CorreosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $correo=Correos::orderBy('id','DESC')->paginate(5);
        return view('correos.index',compact('correo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('correos.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = Correos::create($request->all());

        //enviar correos.
        \Mail::send('correos.envio',[], function($mes){
            $mes->from('Admin@gmail.com','Rodrigo Villanueva');
            $mes->to('rodrigodrupal1@gmail.com','Para rodrigo');
            $mes->subject('Demo del primer correo desde Laravel');
        });

        return redirect()->route('correos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Correos  $correos
     * @return \Illuminate\Http\Response
     */
    public function show(Correos $correos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Correos  $correos
     * @return \Illuminate\Http\Response
     */
    public function edit(Correos $correos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Correos  $correos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Correos $correos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Correos  $correos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Correos $correos)
    {
        //
    }
}
