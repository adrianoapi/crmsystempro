<?php

namespace App\Http\Controllers;

use App\DefaultingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultingHistoryController extends Controller
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
        $model = new DefaultingHistory();
        $model->user_id = Auth::id();
        $model->defaulting_id = $request->defaulting_id;
        $model->observacao = $request->observacao;
        $model->schedule = $request->schedule == 'true' ? 'open' : 'none';

        if(!empty($request->data))
        {
            $date = str_replace('/', '-', $request->data);
            $model->dt_retorno = date("Y-m-d", strtotime($date))." {$request->hora}:{$request->minuto}:00";
        }else{
            $model->dt_retorno = date('Y-m-d h:i:s');
        }

        if($model->save())
        {
            $hora = date('Y-m-d H:i:s', strtotime("$model->created_at -180 minutes"));
            return response()->json([
                'status' => true,
                'attributes' => $model->getAttributes()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DefaultingHistory  $defaultingHistory
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultingHistory $defaultingHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DefaultingHistory  $defaultingHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(DefaultingHistory $defaultingHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DefaultingHistory  $defaultingHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DefaultingHistory $defaultingHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DefaultingHistory  $defaultingHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultingHistory $defaultingHistory)
    {
        //
    }
}
