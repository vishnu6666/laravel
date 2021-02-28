<?php
namespace App\Http\Controllers\Admin;

use App\Model\GameTip;
use App\Model\TmpGameTip;
use App\Model\Game;
use App\Model\PackagesAsignToGame;
use App\Model\Package;
use App\Model\Spreadsheet;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;


class AdminManageTipsController extends Controller
{   
    /**
     * Display a listing of the tips.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = package::where('isActive',1)->get();
        $games = game::where('isActive',1)->get();
        return view('admin.manage_tips.manage_tips_list_filter',['packages' => $packages,'games' => $games]);
    }

    /**
     * Display a listing of the tips with filter by startdate and enddate , package ,game.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if($request->ajax()) {
            
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
                
            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
            $query = GameTip::selectRaw('games_tips.id,games_tips.gameId,games.gameName,games.gameFullName,games_tips.date,
                                                        games_tips.tips,games_tips.percentage,
                                                        games_tips.IsWin,games_tips.odds,
                                                        games_tips.units,
                                                        games_tips.createdAt,
                                                        packages.packageName
                                                        ')
                                                        ->leftJoin('games',function($query){
                                                            $query->on('games.id','=','games_tips.gameId');
                                                        })
                                                        ->leftJoin('packages_asign_to_game',function($query){
                                                            $query->on('packages_asign_to_game.gameId','=','games_tips.gameId');
                                                        })
                                                        ->leftJoin('packages',function($query){
                                                            $query->on('packages.id','=','packages_asign_to_game.packageId');
                                                        });
            if(!empty($request->filter_game)){
                $query = $query->where('games_tips.gameId',$request->filter_game);
            }

            if(!empty($request->start_date) && !empty($request->end_date)){
                $query = $query->whereRaw("date(games_tips.createdAt) >= '" . $request->start_date . "' AND date(games_tips.createdAt) <= '" . $request->end_date . "'");
            }else if(!empty($request->start_date) && empty($request->end_date)){
                $query = $query->whereRaw("date(games_tips.createdAt) >= '" . $request->start_date."'");
            }else if(empty($request->start_date) && !empty($request->end_date)){
                $query = $query->whereRaw("date(games_tips.createdAt) <= '" . $request->end_date . "'");
            }

            if(!empty($request->filter_package)){
                $packageGameId = PackagesAsignToGame::where('packages_asign_to_game.packageId',$request->filter_package)->pluck('gameId');
                $query = $query->whereIn('games_tips.gameId',$packageGameId);
            }                                            
           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('games_tips.date', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.tips', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.odds', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.createdAt', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.units', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.IsWin', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games_tips.percentage', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games.gameName', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('packages.packageName', 'like', '%'.$request->search['value'].'%');
            });
            
            $tips = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $tips['recordsFiltered'] = $tips['recordsTotal'] = $tips['total'];

            foreach ($tips['data'] as $key => $tip) {
                
                $params = [
                    'manage_tip' => $tip['id']
                ];
                
                $tips['data'][$key]['sr_no'] = $startNo+$key;
                $viewRoute = route('manage-tips.show', $params);
                $deleteRoute = route('manage-tips.destroy', $params);
                $statusRoute = route('manage-tips.status', $params);
                $editRoute = route('manage-tips.edit', $params);
                
                // $status = if($tip['IsWin'] == 'win'){
                //     '<span class="label label-success">Win</span>' 
                // }else if($tip['IsWin'] == 'loss'){
                //     '<span class="label label-danger">Loss</span>'
                // }else if($tip['IsWin'] == 'pending'){
                //     '<span class="label label-success">pending</span>'
                // };
                $tips['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Tips Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $tips['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit Game Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
                $tips['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Tips" title="Delete"><i class="fa fa-trash"></i></a>';
                //$tips['data'][$key]['IsWin'] = '<p></p>'.$status.'</p>';
                $tips['data'][$key]['date'] = date('jS F Y', strtotime($tip['date']));
                $tips['data'][$key]['createdAt'] = date('jS F Y', strtotime($tip['createdAt']));
                $tips['data'][$key]['tips'] =  str_limit($tip['tips'],100,'...');
          }
            return response()->json($tips);
        }
    }

    /**
    * 
    * View tips Information
    * 
    * @param Request $request
    * @param string $id
    * 
    * @return view 
    */
    public function show(Request $request, GameTip $manage_tip)
    {    
        $gameTipInfo = GameTip::selectRaw('games_tips.id,games.gameName,games.gameFullName,games_tips.date,
                                            games_tips.weeklyProfitLos,
                                            games_tips.weeklyPot,
                                            games_tips.monthlyProfitLos,
                                            games_tips.monthlyPot,
                                            games_tips.allTimeProfitLos,
                                            games_tips.allTimePot,
                                            games_tips.text,
                                            games_tips.tips,games_tips.percentage,
                                            games_tips.IsWin,games_tips.odds,
                                            games_tips.units,
                                            games_tips.profitLosForTips,
                                            games_tips.createdAt,
                                            packages.packageName
                                            ')
                                            ->leftJoin('games',function($query){
                                                $query->on('games.id','=','games_tips.gameId');
                                            })
                                            ->leftJoin('packages_asign_to_game',function($query){
                                                $query->on('packages_asign_to_game.gameId','=','games_tips.gameId');
                                            })
                                            ->leftJoin('packages',function($query){
                                                $query->on('packages.id','=','packages_asign_to_game.packageId');
                                            })

                                            ->where('games_tips.id',$manage_tip->id)
                                            ->first();
        return view('admin.manage_tips.manage_tips_view',[
            'gameTipInfo' => $gameTipInfo,
        ]);
    }


    /**
     * Remove the specified resource from tips.
     *
     * @param  GameTip $manage_tip
     * @return \Illuminate\Http\Response
     */
    public function destroy( GameTip $manage_tip)
    {
        if($manage_tip->delete())
        {
            return redirect(route('manage-tips.index'))->with('success', trans('messages.manage_tips.delete.success'));
        }
        return redirect(route('manage-tips.index'))->with('error', trans('messages.manage_tips.delete.error'));
    }

    /**
     * Display google sheets upload page.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateresult()
    {
        $packages = Package::where('isActive',1)->get();
        return view('admin.manage_tips.update_result_sheets',['packages' => $packages]);
    }

    /**
     * update tips by google sheets.
     *
     * @param  GameTip $manage_tip
     * @return \Illuminate\Http\Response
    */
    public function excelImport(Request $request)
    {
        ini_set('max_execution_time', 180);

        $client = new \Google_Client();
        $client->setApplicationName('My PHP App');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        // $client->setAuthConfig(__DIR__ . './../google-sheets-tips-update.json');

        $client->setAuthConfig(__DIR__ . '/google-sheets-tips-update.json');

  
        $sheets = new \Google_Service_Sheets($client);

        try {
  
        $spreadsheeturl = $request->spreadsheetId;
        $uri_segments = explode('/', $spreadsheeturl);

        $spreadsheetId = $uri_segments[5];

        $response = $sheets->spreadsheets->get($spreadsheetId);


        $fileTitle = $response['sheets'][0]['properties']['title'];
 
        if(!empty($spreadsheetId) && $uri_segments[4] =='d'){
            $range = $fileTitle.'!A1:Z';
           
            // try {
            $rows = $sheets->spreadsheets_values->get($spreadsheetId, $range);
            // } catch (\Exception $e) {
            //     return redirect(route('updateresult'))->with('error', trans('messages.manage_tips.updateGoogleSheets.error'));
            // }
            
            $values = $rows->getValues();
 
            $limitRecord = 100;
            $increaseRecord = 100;
            $totalRecord = count($values);
            $totalRecordCount = count($values);
            $totalRecord = $totalRecord / $limitRecord;
            $totalRecord =  ceil($totalRecord);
            $updateArray = array();
            $startRecord = 1;

            $games = array_change_key_case(collect(\DB::table('packages_asign_to_game')
                                    ->leftJoin('games', 'games.id', '=', 'packages_asign_to_game.gameId')
                                    ->where('packages_asign_to_game.packageId',$request->package)
                                    ->get() 
                            )->keyBy('gameName')->all(),CASE_LOWER);

            $userId =  \Auth::guard('superadmin')->user()->id;

            $spreadsheet = new Spreadsheet;
            $spreadsheet->spreadsheetId = $spreadsheeturl;
            $spreadsheet->packageId = $request->package;
            $spreadsheet->userId = $userId;

            $spreadsheet->save();

            for ($i = 0; $i < $totalRecord; $i++) {
                if ($limitRecord > $totalRecordCount) {
                    $range = $fileTitle.'!A' . $startRecord . ':Z' . $totalRecordCount . '';
                } else {
                    $range = $fileTitle.'!A' . $startRecord . ':Z' . $limitRecord . '';
                }

                $rows = $sheets->spreadsheets_values->get($spreadsheetId, $range);
                $values = $rows->getValues();

                if ($values) {
                    if($startRecord == 1 ){
                        unset($values[0]);
                        unset($values[1]);
                    }

                    $updateArray = array();
                    foreach ($values as $key => $val) {
                            if (!empty($val)) {

                            $status = "pending";

                            if(isset($val[5])){
                                if(strtolower($val[5]) == 'yes'){ $status = 'win'; }

                                if(strtolower($val[5]) == 'no'){ $status = 'loss'; }

                                if(strtolower($val[5]) == 'pending'){ $status = 'pending'; }
                            }
                            
                            $updateArray[$key]['spreadsheetsId'] = isset($spreadsheet->id) ? $spreadsheet->id : '';
                            $updateArray[$key]['userId'] = $userId;
                            $updateArray[$key]['date'] = isset($val[0]) ? date("Y-m-d", strtotime($val[0])) : '';
                            $updateArray[$key]['gameId'] = array_key_exists(strtolower($val[1]), $games) ? $games[strtolower($val[1])]->id : '';
                            $updateArray[$key]['competition'] = isset($val[1]) ? $val[1] : '';
                            $updateArray[$key]['tips'] = !empty($val[2]) ? $val[2] : '';
                            $updateArray[$key]['odds'] = !empty($val[3]) ? $val[3] : '0';
                            $updateArray[$key]['units'] = !empty($val[4]) ? $val[4] : '0';
                            $updateArray[$key]['IsWin'] = $status;
                            $updateArray[$key]['profitLosForTips'] = !empty($val[6]) ? $val[6] : '0';

                            $updateArray[$key]['weeklyProfitLos'] = !empty($val[7]) ? $val[7] : '0';
                            $updateArray[$key]['weeklyPot'] = !empty($val[8]) ? rtrim($val[8],'%').'%' : '0.00%';
                            $updateArray[$key]['monthlyProfitLos'] = !empty($val[9]) ? $val[9] : '0';
                            $updateArray[$key]['monthlyPot'] = !empty($val[10]) ? rtrim($val[10],'%').'%' : '0.00%';
                            $updateArray[$key]['allTimeProfitLos'] = !empty($val[11]) ? $val[11] : '0';
                            $updateArray[$key]['allTimePot'] = !empty($val[12]) ? rtrim($val[12],'%').'%' : '0.00%';

                            $updateArray[$key]['tipsImage'] = !empty($val[13]) ? $val[13] : 'NULL';
                            $updateArray[$key]['text'] = isset($val[14]) ? $val[14] : '';

                            // $updateArray[$key]['profitLosForDay'] = isset($val[7]) ? $val[7] : '';
                            // $updateArray[$key]['dailyPot'] = isset($val[8]) ? $val[8] : '';
                            // $updateArray[$key]['profitLossCumulative'] = isset($val[9]) ? $val[9] : '';
                            // $updateArray[$key]['pot'] = isset($val[10]) ? $val[10] : '';
                            // $updateArray[$key]['profitOneUnit'] = isset($val[11]) ? $val[11] : '';
                            // $updateArray[$key]['profitTwoUnit'] = isset($val[12]) ? $val[12] : '';
                            // $updateArray[$key]['profitThreeUnit'] = isset($val[13]) ? $val[13] : '';
                        }
                    }
                }

                if(!empty($updateArray) && count($updateArray) > 0 )
                {
                    \DB::table('tmp_games_tips')->insert($updateArray);
                    $updateArray = array();
                    $tmpGameTipsId = GameTip::where(['spreadsheetsId' => $spreadsheet->id, 'userId' => $userId])->pluck('tmpGameTipsId')->toArray();

           


                   if(!empty($tmpGameTipsId)){
                    TmpGameTip::whereIn('id', $tmpGameTipsId)->update([
                                'isResultUpdate' => 1
                                ]);
                    }
                }

                $startRecord = $startRecord + $increaseRecord;
                $limitRecord = $limitRecord + $increaseRecord;
            }

            return redirect(route('updateresult'))->with('success', trans('messages.manage_tips.updateGoogleSheets.success'));
        }
        } catch (\Exception $e) {
            return redirect(route('updateresult'))->with('error', trans('messages.manage_tips.updateGoogleSheets.error'));
        }

        return redirect(route('updateresult'))->with('error', trans('messages.manage_tips.updateGoogleSheets.error'));
    }

    /**
     * Display a listing of the tips with filter by startdate and enddate , package ,game.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchspreadsheets(Request $request)
    {
        if($request->ajax()) {
            
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
                
            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;

            $userId =  \Auth::guard('superadmin')->user()->id;

            // if(\Auth::guard('superadmin')->check()){
            //         $userId =  \Auth::guard('superadmin')->user()->id;
            // }elseif(\Auth::guard('admin')->check()){
            //         $userId =  \Auth::guard('admin')->user()->id;
            // }

            $query = Spreadsheet::selectRaw('spreadsheets.id,spreadsheets.createdAt,spreadsheets.spreadsheetId,
                                                        packages.packageName
                                                        ')
                                                        ->leftJoin('packages',function($query){
                                                            $query->on('packages.id','=','spreadsheets.packageId');
                                                        })
                                                        ->where('spreadsheets.userId',$userId);

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('spreadsheets.spreadsheetId', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('packages.packageName', 'like', '%'.$request->search['value'].'%');
            });
            
            $spreadsheets = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $spreadsheets['recordsFiltered'] = $spreadsheets['recordsTotal'] = $spreadsheets['total'];

            foreach ($spreadsheets['data'] as $key => $spreadsheet) {
                
                $params = [
                    'manage_result_tip' => $spreadsheet['id']
                ];
                
                $spreadsheets['data'][$key]['sr_no'] = $startNo+$key;
                $viewRoute = route('resulttips', $params);
                $spreadsheets['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="View Result Tips"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $spreadsheets['data'][$key]['createdAt'] = date('jS F Y', strtotime($spreadsheet['createdAt']));
               // $tips['data'][$key]['spreadsheetId'] =  str_limit($tip['spreadsheetId'],100,'...');
          }
            return response()->json($spreadsheets);
        }
    }

    /**
     * Display result update tips view page.
     *
     */
    public function resulttips(Spreadsheet $manage_result_tip)
    {
        return view('admin.manage_tips.update_result_tips',['spreadsheetsId' => $manage_result_tip->id ] );
    }

    /**
     * Display a listing of the tips which is updated by result tips.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchresultupdatetips(Request $request)
    {
        if($request->ajax()) {
            $userId =  \Auth::guard('superadmin')->user()->id;
            
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
                
            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
            $query = TmpGameTip::selectRaw('tmp_games_tips.id,tmp_games_tips.date,tmp_games_tips.competition,tmp_games_tips.odds,tmp_games_tips.gameId,games.gameName,games.gameFullName,tmp_games_tips.date,
                                            tmp_games_tips.tips,tmp_games_tips.percentage,
                                            tmp_games_tips.IsWin,
                                            tmp_games_tips.units,
                                            tmp_games_tips.profitLosForTips,
                                            tmp_games_tips.weeklyProfitLos,
                                            tmp_games_tips.weeklyPot,
                                            tmp_games_tips.profitOneUnit,
                                            tmp_games_tips.monthlyProfitLos,
                                            tmp_games_tips.monthlyPot,
                                            tmp_games_tips.allTimeProfitLos,
                                            tmp_games_tips.allTimePot,
                                            tmp_games_tips.createdAt,
                                            tmp_games_tips.text,
                                            tmp_games_tips.isResultUpdate,
                                            packages.packageName')
                                            ->leftJoin('games',function($query){
                                                $query->on('games.id','=','tmp_games_tips.gameId');
                                            })
                                            ->leftJoin('packages_asign_to_game',function($query){
                                                $query->on('packages_asign_to_game.gameId','=','tmp_games_tips.gameId');
                                            })
                                            ->leftJoin('packages',function($query){
                                                $query->on('packages.id','=','packages_asign_to_game.packageId');
                                            })
                                            //->where('tmp_games_tips.userId',$userId)
                                            ->where('tmp_games_tips.spreadsheetsId',$request->spreadsheetsId);                                           
           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

 

            $query->where(function ($query) use ($request) {
                $query->orWhere('tmp_games_tips.date', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.tips', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.odds', 'like', '%'.$request->search['value'].'%');
                //$query->orWhere('tmp_games_tips.pot', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.createdAt', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.units', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.IsWin', 'like', '%'.$request->search['value'].'%');
                //$query->orWhere('tmp_games_tips.percentage', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('games.gameName', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('packages.packageName', 'like', '%'.$request->search['value'].'%');
             
                $query->orWhere('tmp_games_tips.text', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('tmp_games_tips.isResultUpdate', 'like', '%'.$request->search['value'].'%');
            });
            
            $tips = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

 

            $tips['recordsFiltered'] = $tips['recordsTotal'] = $tips['total'];

 

            foreach ($tips['data'] as $key => $tip) {
                
                $params = [
                    'manage_tip' => $tip['id']
                ];
                
                $tips['data'][$key]['sr_no'] = $startNo+$key;
               // $viewRoute = route('manage-tips.show', $params);
              
                if($tip['gameId']== 0){
                    $resultUpdateStatus = '<span class="label label-danger">Game not found</span>';
                }elseif($tip['isResultUpdate']== 0){
                    $resultUpdateStatus = '<span class="label label-danger">Result not found</span>';
                }else{
                    $resultUpdateStatus = '<span class="label label-success">Updated</span>';
                }

                if($tip['IsWin']== 'pending'){
                    $status = '<span class="label label-primary">Pending</span>';
                }elseif($tip['IsWin']== 'loss'){
                    $status = '<span class="label label-danger">Loss</span>';
                }elseif($tip['IsWin']== 'win'){
                    $status = '<span class="label label-success">Win</span>';
                }

 
                $isResultUpdate = ($tip['isResultUpdate']== 1) ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
                //$tips['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Tips Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $tips['data'][$key]['IsWin'] = '<p></p>'.$status.'</p>';
                $tips['data'][$key]['isResultUpdate'] = '<p></p>'.$isResultUpdate.'</p>';
                $tips['data'][$key]['resultUpdateStatus'] = '<p></p>'.$resultUpdateStatus.'</p>';
                $tips['data'][$key]['date'] = date('jS F Y', strtotime($tip['date']));
                $tips['data'][$key]['createdAt'] = date('jS F Y', strtotime($tip['createdAt']));
                $tips['data'][$key]['tips'] =  str_limit($tip['tips'],300,'...');
                $tips['data'][$key]['text'] =  str_limit($tip['text'],300,'...');
          }
            return response()->json($tips);
        }
    }

    public function create(Request $request)
    {
        $packages = package::where('isActive', 1)->get();
        $games = game::where('isActive', 1)->get();
        return view('admin.manage_tips.game_tips_create',[
            'games' => $games,
            'packages' => $packages,
        ]);
    }



    public function getPackageGame(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $packageId = $request['packageId'];
        $hiddenCityValue = $request['gameId'];

        $getGame = PackagesAsignToGame::selectRaw('packages_asign_to_game.*,games.gameName')
        ->join('games', 'games.id', 'packages_asign_to_game.gameId')
        ->where('isActive', 1)
        ->where('packageId', $packageId)->get();
        
        $html = '';
        if (!empty($getGame)) {
            foreach ($getGame as $value) {
                $selected = '';
                if (isset($hiddenCityValue) && $hiddenCityValue == $value->gameId) {
                    $selected = 'selected';
                }
                $html .= '<option value="' . $value->gameId . '" ' . $selected . '>' . $value->gameName . '</option>';
            }
        }
        return response()->json(['status' => '1', 'html' => $html]);
    }


    public function store(Request $request)
    {
        $manage_tip = new GameTip();
        $manage_tip->fill($request->all());
        $manage_tip->packageId = $request->packageId;
        $manage_tip->date = $request->date;

        $manage_tip->odds = isset($request->odds) ? $request->odds : '0.00';
        $manage_tip->profitLosForTips = isset($request->profitLosForTips) ? $request->profitLosForTips : '0';
        $manage_tip->weeklyProfitLos = isset($request->weeklyProfitLos) ? $request->weeklyProfitLos : '0';
        $manage_tip->monthlyProfitLos = isset($request->monthlyProfitLos) ? $request->monthlyProfitLos : '0';
        $manage_tip->allTimeProfitLos = isset($request->allTimeProfitLos) ? $request->allTimeProfitLos : '0';

        $manage_tip->weeklyPot = isset($request->weeklyPot) ? $request->weeklyPot.'%' : '0.00%';
        $manage_tip->monthlyPot = isset($request->monthlyPot) ? $request->monthlyPot.'%' : '0.00%';
        $manage_tip->allTimePot = isset($request->allTimePot) ? $request->allTimePot.'%' : '0.00%';
        
        $manage_tip->text = isset($request->text) ? $request->text : '';
        $manage_tip->IsWin = isset($request->IsWin) ? $request->IsWin : 'pending';
       
        // $this->validate($request, $this->validationRules);
        // $game = new Game();
        // $game->fill($request->all());
        // $game->gameName = $request->gameName;
        // $game->gameFullName = $request->gameFullName;
        // $game->isActive = $request->status;
        // $game->launchDate = $request->launchDate;
        // $iconImagePath = public_path('images/gameimage');

        if ($request->hasFile('tipsImage')) {
            $gameImagePath = public_path('images/gameimage');
            $ImageName = self::savePhoto($request->file('tipsImage'), 'images/gameimage',$gameImagePath,'game_tip_image',550,500);
            $manage_tip->tipsImage  = url('/images/gameimage/' . $ImageName);
        }
        //dd($manage_tip);

        if ($manage_tip->save()) {

            $gameName = Game::where('id', $request->gameId)->first();

            // $title = $gameName->gameName . ' has uploaded new tips';
            // $content = $gameName->gameName . ' has uploaded new tips';

            // $title = ' ' . $gameName->gameName . '  has uploaded new tips';
            // $content = '' . $gameName->gameName . ' has uploaded new tips';


            if (isset($request->gameId)) {
                $gameId = $request->gameId;
                $notificationType = 5;
                $module = 'game trip';
                $senderId = 1;

                // $title = '<b style="color: #03a9f3;">' . $gameName->gameName . '</b>  has uploaded new tips';
                // $content = '<b style="color: #03a9f3;">' . $gameName->gameName . '</b>  has uploaded new tips';

                $title =   'New tips added in <b style="color: #03a9f3;">' . $gameName->gameName . '</b>';
                $content =  'New tips added in <b style="color: #03a9f3;">' . $gameName->gameName . '</b>';


                $gameImage = (!empty($gameName->gameImage)) ?  url('images/gameimage/' . $gameName->gameImage)  :  url('admin-assets/images/logo/vp.png');

                $dbQuery = "INSERT INTO notifications(senderId, userId,gameTripId,notificationType, module, title,content,media,createdAt)
            SELECT DISTINCT
            '" . $senderId . "',
             userId,
             '" . $manage_tip->id . "',
            '" . $notificationType . "',
            '" . $module . "',
            '" . $title . "',
            '" . $content . "',
            '" . $gameImage . "',
            '" . Carbon::now() . "'
            FROM 
            user_game_history where gameId ='" . $gameId . "' AND  isSubscribed = 1 AND isKeepNotification = 1;";

                \DB::select($dbQuery);

                // thiis command send push notification to all users 
                $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notificationType . '" "' . $manage_tip->id . '" ';
                //\Log::debug($cmd);
                exec($cmd . '> /dev/null &');
            }

            return redirect()->route('manage-tips.index')->with('success', trans('messages.manage_tips.create.success'));
        }
        return redirect()->route('manage-tips.index')->with('error', trans('messages.manage_tips.create.error'));
    }

    public function edit(GameTip $manage_tip)
    {
        $packages = package::where('isActive', 1)->get();
        $games = game::where('isActive', 1)->get();
        return view('admin.manage_tips.game_tips_create', [
            'gameTip' => $manage_tip,
            'games' => $games,
            'packages' => $packages,
        ]);
    }

    public function update(Request $request, GameTip $manage_tip)
    {

        // $this->validate($request, $this->validationRules);
        $manage_tip->fill($request->all());
        $manage_tip->packageId = $request->packageId;
        
        $manage_tip->odds = isset($request->odds) ? $request->odds : '0.00';
        $manage_tip->units = isset($request->units) ? $request->units : '0';
        $manage_tip->profitLosForTips = isset($request->profitLosForTips) ? $request->profitLosForTips : '0';
        $manage_tip->weeklyProfitLos = isset($request->weeklyProfitLos) ? $request->weeklyProfitLos : '0';
        $manage_tip->monthlyProfitLos = isset($request->monthlyProfitLos) ? $request->monthlyProfitLos : '0';
        $manage_tip->allTimeProfitLos = isset($request->allTimeProfitLos) ? $request->allTimeProfitLos : '0';

        $manage_tip->weeklyPot = isset($request->weeklyPot) ? rtrim($request->weeklyPot,'%').'%' : '0.00%';
        $manage_tip->monthlyPot = isset($request->monthlyPot) ? rtrim($request->monthlyPot,'%').'%' : '0.00%';
        $manage_tip->allTimePot = isset($request->allTimePot) ? rtrim($request->allTimePot,'%').'%' : '0.00%';
        
        $manage_tip->text = isset($request->text) ? $request->text : '';
        $manage_tip->IsWin = isset($request->IsWin) ? $request->IsWin : 'pending';

        if ($request->hasFile('tipsImage')) {
            $gameImagePath = public_path('images/gameimage');
            $ImageName = self::savePhoto($request->file('tipsImage'), 'images/gameimage', $gameImagePath, 'game_tip_image', 550, 500);
            $manage_tip->tipsImage  = url('/images/gameimage/'. $ImageName);
        }

        if ($manage_tip->save()) {
            return redirect()->route('manage-tips.index')->with('success', trans('messages.manage_tips.update.success'));
        }

        return redirect()->route('manage-tips.index')->with('error', trans('messages.manage_tips.update.error'));
    }


    private function savePhoto($image, $path, $thumailImagePath, $prefix, $height, $width)
    {
        if (!empty($image)) {
            $imageName = ImageHelper::imageSave($image, $path, $prefix);
            if (!empty($imageName)) {
                // param =  image,path,thumailImagePath,prefix,height,width
                ImageHelper::imageResize($imageName, $path, $thumailImagePath, $height, $width);
            }
            return $imageName;
        }
        return null;

        /*        if(!empty($image))
     {
         return ImageHelper::imageSave($image,$path,$prefix);
     }
     return null;*/
    }

}

