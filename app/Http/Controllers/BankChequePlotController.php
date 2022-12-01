<?php

namespace App\Http\Controllers;

use App\BankChequePlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankChequePlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        BankChequePlot::where('bank_cheque_id', $request->bank_cheque_id)->delete();
        if(!empty($request->cheque))
        {
            $i = 0;
            foreach($request->cheque as $value):
                $model = new BankChequePlot();
                $model->user_id     = Auth::id();
                $model->bank_cheque_id = $request->bank_cheque_id;
                $model->vencimento = $request->vencimento[$i];
                $model->banco = $request->banco[$i];
                $model->agencia = $request->agencia[$i];
                $model->conta = $request->conta[$i];
                $model->cheque = $request->cheque[$i];
                $model->valor = $request->valor[$i];
                $model->save();
                $i++;
            endforeach;
        }

        return redirect()->route('bankCheques.show', ['bankCheque' => $request->bank_cheque_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankChequePlot  $bankChequePlot
     * @return \Illuminate\Http\Response
     */
    public function show(BankChequePlot $bankChequePlot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankChequePlot  $bankChequePlot
     * @return \Illuminate\Http\Response
     */
    public function edit(BankChequePlot $bankChequePlot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankChequePlot  $bankChequePlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankChequePlot $bankChequePlot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankChequePlot  $bankChequePlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankChequePlot $bankChequePlot)
    {
        //
    }
}
