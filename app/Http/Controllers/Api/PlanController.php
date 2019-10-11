<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;
use App\User;
use App\Plan;

class PlanController extends Controller
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

        $position = User::Select('position_id')->Where('id',$requestData["id"])
                            ->pluck('position_id')
                            ->first();

        $plan = Plan::Select('id','name')
                    ->where('position_id',$position)
                    ->get();

        return $plan;
    }
}
