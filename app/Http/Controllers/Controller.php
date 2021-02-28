<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $perPage =10;
    
    /**
     * send response to user.
     *
     * @return json
     */
    public function toJson($result = [], $message = '', $status = 1)
    {
        return response()->json([
            'status' => $status,
            'result' => !empty($result) ? $result : new \stdClass(),
            'message' => $message,
        ]);
    }

    /**
     * Show the check unique value.
     *
     * @return \Illuminate\Http\Response
     */

    public function gameTipUnique(Request $request, $table){

        if($request->ajax()) {

            if(!empty($request->gameId) && !empty($request->value)) {

                $where = [
                    ['tips', '=', $request->value],
                    ['gameId', '=', $request->gameId],
                ];
              
                if(!empty($request->id)) {
                    $where[] = ['id', '!=', $request->id];
                }
                $count = \DB::table('games_tips')
                     ->where($where)
                     ->whereDate('date', date('Y-m-d'))
                     ->count();
                   
                return $count > 0 ?  'false' : 'true';
            }
        }

    }

    public function checkUnique(Request $request, $table, $columnName)
    {

        if ($request->ajax()) {

            if (!empty($request->value)) {

                $where = [
                    [$columnName, '=', $request->value],
                ];

                if (!empty($request->id)) {
                    $where[] = ['id', '!=', $request->id];
                }

                $count = \DB::table($table)
                    ->where($where)
                    ->count();

                return $count > 0 ?  'false' : 'true';
            }
        }
    }

    public function checkUniquebothtable(Request $request, $table, $columnName, $table1, $columnName1)
    {

        if ($request->ajax()) {

            if (!empty($request->value)) {

                $where = [
                    [$columnName, '=', $request->value],
                ];

                $where1 = [
                    [$columnName1, '=', $request->value],
                ];

                if (!empty($request->id)) {
                    $where[] = ['id', '!=', $request->id];
                }

                $count = \DB::table($table)
                    ->where($where)
                    ->count();

                 if($count > 0)   
                 {
                     return 'false';
                 }else{

                    $count = \DB::table($table1)
                    ->where($where1)
                    ->count();

                    return $count > 0 ?  'false' : 'true';
                    // return 'true';
                 }

                // return $count > 0 ?  'false' : 'true';
            }
        }
    }
    
}
