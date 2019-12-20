<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class EventsController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(['role:Administrador|Supervisor']);

        $this->data = array(
        'active' => 'events',
        'url1' => '/administrator/events',
        'url2' => ''
        );
    }
    public function index($model = ''){
        $header = ([
            [
                "text" => '#',
                "align" => 'center'
            ], [
                "text" => 'id',
                "align" => 'center'
            ], [
                "text" => 'ESTADO',
                "align" => 'center'
            ], [
                "text" => 'GESTIÓN',
                "align" => 'left'
            ], [
                "text" => 'MES',
                "align" => 'left'
            ], [
                "text" => 'FECHA LÍMITE',
                "align" => 'left'
            ]
        ]);
        if($model == ''){
            $mod = '\App\Configuration';
            $model = 'configuration';
        }else{
            $mod  = StringParce($model);
        }
        $audits = $mod::find(1)->audits;
        return view('layouts.administrator.events.index', compact('header','audits','model'))->with('title', 'Eventos del Sistema')->with('data', $this->data);
    }
}
