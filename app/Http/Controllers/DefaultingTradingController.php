<?php

namespace App\Http\Controllers;

use App\DefaultingTrading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultingTradingController extends Controller
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
            $result = DB::table('defaulting_tradings')->where('defaulting_id', $request->defaulting_id)->exists();
            if(!empty($result))
            {
                die('Você não tem permissão editar uma negociação!');
            }
        }

        DefaultingTrading::where('defaulting_id', $request->defaulting_id)->delete();

        $i = 0;
        if(!empty($request->parcela)){
            foreach($request->parcela as $value):
                $model = new DefaultingTrading();
                $model->user_id       = Auth::id();
                $model->defaulting_id = $request->defaulting_id;

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

        return redirect()->route('defaultings.show', ['defaulting' => $request->defaulting_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DefaultingTrading  $defaultingTrading
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultingTrading $defaultingTrading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DefaultingTrading  $defaultingTrading
     * @return \Illuminate\Http\Response
     */
    public function edit(DefaultingTrading $defaultingTrading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DefaultingTrading  $defaultingTrading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DefaultingTrading $defaultingTrading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DefaultingTrading  $defaultingTrading
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultingTrading $defaultingTrading)
    {
        //
    }
}
