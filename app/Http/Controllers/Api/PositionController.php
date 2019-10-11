<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;
use App\Department;
use App\Position;

class PositionController extends Controller
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

        $position=Position::Select('id','name')
                            ->Where([['department_id',$requestData["id"]],
                                    ['status',true]])
                            ->get();
        return $position;
    }
}
