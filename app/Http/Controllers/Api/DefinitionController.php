<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;
use App\Definition;
use App\Goal;

class DefinitionController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $requestData = $request->all();
        
        $definition=Definition::Where([['definition_id',$requestData["id"]],['definition_type','App\\'. $requestData["type"]]])->pluck('description')->first();
        
        return $definition;
    }
}
