<?php

namespace App\Http\Controllers;

use App\GraphicTrading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GraphicTradingController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->level <= 1)
        {
            $result = DB::table('graphic_tradings')->where('graphic_id', $request->graphic_id)->exists();
            if(!empty($result))
            {
                die('Você não tem permissão editar uma negociação!');
            }
        }

        GraphicTrading::where('graphic_id', $request->graphic_id)->delete();

        $i = 0;
        if(!empty($request->parcela)){
            foreach($request->parcela as $value):
                $model = new GraphicTrading();
                $model->user_id     = Auth::id();
                $model->graphic_id  = $request->graphic_id;

                $model->vencimento = $request->vencimento[$i];
                $model->pagamento  = $request->pagamento[$i];
                $model->valor      = $request->valor[$i];
                $model->parcela    = $request->parcela[$i];

                if(strlen($request->dt_pagamento[$i]))
                {
                    $model->dt_pagamento = $request->dt_pagamento[$i];
                }
                if(strlen($request->valor_pago[$i]))
                {
                    $model->valor_pago = $request->valor_pago[$i];
                }
                $model->save();
                $i++;
            endforeach;
        }

        return redirect()->route('graphics.show', ['graphic' => $request->graphic_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GraphicTrading  $graphicTrading
     * @return \Illuminate\Http\Response
     */
    public function show(GraphicTrading $graphicTrading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GraphicTrading  $graphicTrading
     * @return \Illuminate\Http\Response
     */
    public function edit(GraphicTrading $graphicTrading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GraphicTrading  $graphicTrading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GraphicTrading $graphicTrading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GraphicTrading  $graphicTrading
     * @return \Illuminate\Http\Response
     */
    public function destroy(GraphicTrading $graphicTrading)
    {
        //
    }
}
