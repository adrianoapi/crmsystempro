<?php

namespace App\Http\Controllers;

use App\BankChequeTrading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankChequeTradingController extends Controller
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
            $result = DB::table('bank_cheque_tradings')->where('bank_cheque_id', $request->bank_cheque_id)->exists();
            if(!empty($result))
            {
                die('Você não tem permissão editar uma negociação!');
            }
        }

        BankChequeTrading::where('bank_cheque_id', $request->bank_cheque_id)->delete();

        $i = 0;
        if(!empty($request->parcela)){
            foreach($request->parcela as $value):
                $model = new BankChequeTrading();
                $model->user_id        = Auth::id();
                $model->bank_cheque_id = $request->bank_cheque_id;

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

        return redirect()->route('bankCheques.show', ['bankCheque' => $request->bank_cheque_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\bankChequeTrading  $bankChequeTrading
     * @return \Illuminate\Http\Response
     */
    public function show(bankChequeTrading $bankChequeTrading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\bankChequeTrading  $bankChequeTrading
     * @return \Illuminate\Http\Response
     */
    public function edit(bankChequeTrading $bankChequeTrading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\bankChequeTrading  $bankChequeTrading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, bankChequeTrading $bankChequeTrading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\bankChequeTrading  $bankChequeTrading
     * @return \Illuminate\Http\Response
     */
    public function destroy(bankChequeTrading $bankChequeTrading)
    {
        //
    }
}
