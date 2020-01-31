<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;

class OperationController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function getSumPonderation(Request $request)
    {
        
        $va =  \App\Definition::where('definition_type','App\Operation')->
                                where('department_id',$request->id)->
                                pluck('dep_ponderation')->
                                sum();
        return 100-$va;
    }
}
