<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;
use App\Description;
use App\Goal;

class DescriptionController extends Controller
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

        if($requestData["type"] == "Goal")

          $description=Goal::Where('id', $requestData["id"])
                            ->pluck('description')
                            ->first();

        else

          $description=Description::Where([['description_id',$requestData["id"]],
                                            ['description_type','App\\'. $requestData["type"]]
                                            ])
                                    ->pluck('description')->first();

        return $description;
    }
}
