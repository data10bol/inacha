<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hashids;
use App\User;
use App\Position;

class DependencyController extends Controller
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

        $users=User::Join('positions','position_id','=','positions.id')
                    ->Select('users.id','users.name')
                    ->Where('positions.department_id',$requestData["id"])
                    ->get();
        return $users;
    }
}
