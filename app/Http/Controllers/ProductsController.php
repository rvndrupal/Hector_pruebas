<?php

namespace App\Http\Controllers;

use App\Products;
use App\Cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function crear(){

         return view('products.crear');
    }


    public function index()
    {
        $productos=Products::with('cursos')->orderBy('id','DESC')->paginate(9);
        //dd($productos);
        return view('products.index',compact('productos'));
    }

    public function indexlog()
    {
        $productos=Products::onlyTrashed()->with('cursos')->orderBy('id','DESC')->paginate(9);
        //dd($productos);
        return view('products.indexlog',compact('productos'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    //   $data= $request->validate([

    //     'nombre_producto'=>'required',
    //     'slug_producto'=>'required',
    //     'ap_producto'=>'required',
    //     'nombre_curso'=>'required',
    //     'descripcion_curso'=>'required'
    //   ],[
    //       'nombre_producto.required'=>'El campo nombre es obligatorio'
    //   ]);

     DB::transaction(function () use ($request) {
        //  $producto=Products::create([
        //     'nombre_producto'=>$data['nombre_producto'],
        //     'ap_producto'=>$data['ap_producto'],
        //     'slug_producto'=>$data['slug_producto']
        //  ]);

       // dd($request);

        $producto = Products::create($request->all());


        if($request->file('imagen_producto')){

            $path = Storage::disk('public')->put('productos',  $request->file('imagen_producto'));
            //put es el nombre de la carpeta
            $producto->fill(['imagen_producto' => asset($path)])->save();
        }



        //IMAGE


        //se inserta ahora lo de cursos
         //se utiliza la relacion de una vexx
        foreach($request->nombre_curso as $item=>$v)
           {
                $producto->cursos()->create([
                'nombre_curso'=>$request->nombre_curso[$item],
                'descripcion_curso'=>$request->descripcion_curso[$item]
            ]);

           }
     });
       return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products, $slug_producto)
    {
        $buscar= Products::where('slug_producto','=',$slug_producto)->get();
        // dd($buscar->products->id);
         foreach ($buscar as $rid) {
            $rid=$rid->id;
            //dd($rid);        // dd($rid);
        }
        $products = Products::with('cursos')->find($rid);

        //dd($products);

        return view('products.editar', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products, $slug_producto)
    {
        $buscar= Products::where('slug_producto','=',$slug_producto)->get();
        // dd($buscar->products->id);
         foreach ($buscar as $rid) {
            $rid=$rid->id;
           // dd($rid);        // dd($rid);
        }

        // $request->validate([

        //     'nombre_producto'=>'required',
        //     'ap_producto'=>'required',
        //     'nombre_curso'=>'required',
        //     'descripcion_curso'=>'required'
        //   ],[
        //       'nombre_producto.required'=>'El campo nombre es obligatorio'
        //   ]);



        DB::transaction(function () use ($request, $rid) {
            $producto = Products::with('cursos')->find($rid);

            //dd($producto->cursos);

            // $producto->nombre_producto=$request->get('nombre_producto');
            // $producto->ap_producto=$request->get('ap_producto');
            // $producto->slug_producto=$request->get('slug_producto');
            // $producto->save();

            $producto->fill($request->all())->save();
            if($request->file('imagen_producto')){

                $path = Storage::disk('public')->put('productos',  $request->file('imagen_producto'));
                //put es el nombre de la carpeta
                $producto->fill(['imagen_producto' => asset($path)])->save();
            }

            //bloque cursos

            $producto->cursos()->delete($rid);
            //se inserta ahora lo de cursos
            //se utiliza la relacion de una vexx
           foreach($request->nombre_curso as $item=>$v)
              {

                  //dd($item);
                $producto->cursos()->create([
                    'nombre_curso'=>$request->nombre_curso[$item],
                    'descripcion_curso'=>$request->descripcion_curso[$item]
                ]);

              }

        });

        return redirect()->route('index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products, $slug_producto)
    {
         $buscar= Products::where('slug_producto','=',$slug_producto)->get();
         foreach ($buscar as $rid) {
            $rid=$rid->id;
            //dd($rid);      // dd($rid);
        }
        $producto = Products::find($rid)->delete();
        return redirect()->route('index');
    }

    public function restaurar($slug_producto)
    {

        $buscar= Products::onlyTrashed()->where('slug_producto','=',$slug_producto)->get();
        //dd($buscar);
         foreach ($buscar as $rid) {
            $rid=$rid->id;
            //dd($rid);      // dd($rid);
        }
        $producto = Products::onlyTrashed()->find($rid)->restore();
        return redirect()->route('indexlog');

    }

    public function destroy_permanente(Products $products, $slug_producto)
    {
         $buscar= Products::onlyTrashed()->where('slug_producto','=',$slug_producto)->get();
         foreach ($buscar as $rid) {
            $rid=$rid->id;
            //dd($rid);      // dd($rid);
        }
        $producto = Products::onlyTrashed()->find($rid)->forceDelete();
        return redirect()->route('indexlog');
    }

    //PDF
    public function pdf(Request $request){

         $productos=Products::with('cursos')->get();
         //dd($productos);

         $pdf = \PDF::loadView('products.pdf', compact('productos') );
         return $pdf->download('productos.pdf');
     }


     public function excelExportar(Request $request)
     {

        Excel::create('Laravel Excel', function($excel) {

            $excel->sheet('Productos', function($sheet) {

                $products = Products::all();

                $sheet->fromArray($products);

            });
        })->export('xls');
     }

}
