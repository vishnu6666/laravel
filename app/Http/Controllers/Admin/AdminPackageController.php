<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Package;
use App\Model\Game;
use App\Model\PackagesAsignToGame;
use App\Model\PackagesAsignTopackage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\userGameHistory;

class AdminPackageController extends Controller
{
/**
 * List out validation for packages
 *
 */
private $validationRules = [
    'packageName' => 'required|string|min:3|max:20',
    'selectGame' => 'required'
];

/**
 * Show package List view
 *
 * @return view
 */
 public function index()
 {   
     return view('admin.packages.package_list');
 }
 
/**
 * Show the form for creating a new package .
 *
 */
 public function create()
 {
    $gameIds = DB::table('packages_asign_to_game')->distinct('gameId')->pluck('gameId')->toArray();
    $gameAsignedIds = $gameIds ? $gameIds : [];
    
    $gameList = Game::where('isActive',1)->whereNotIn('id', $gameAsignedIds)->get();

     return view('admin.packages.package_create', [
        'gameList' => $gameList
    ]);
 }

 /**
  * Search packages.
  *
  * @param Request $request
  * 
  * @return json
  */
 public function search(Request $request)
 {
     if($request->ajax()) {

         $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
         
         Paginator::currentPageResolver(function () use ($currentPage) {
             return $currentPage;
         });
             
         $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
         $query = Package::selectRaw('packages.id,packages.packageName,packages.isActive,DATE_FORMAT(packages.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate');

         $orderDir = $request->order[0]['dir'];
         $orderColumnId = $request->order[0]['column'];
         $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

        $query->where(function($query) use ($request){
            $query->orWhere('packageName', 'like', '%'.$request->search['value'].'%');
        }); 

         $packages = $query->orderBy($orderColumn, $orderDir)
                         ->paginate($request->length)->toArray();

         $packages['recordsFiltered'] = $packages['recordsTotal'] = $packages['total'];

         foreach ($packages['data'] as $key => $package) {
             $params = [ 'package' => $package['id'] ];
             $packages['data'][$key]['sr_no'] = $startNo+$key;
             $viewRoute = route('packages.show', $params);
             $statusRoute = route('packages.status', $params);
             $editRoute = route('packages.edit', $params);
             $deleteRoute = route('packages.destroy', $params);
            
             $status = ($package['isActive']== 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
             $packages['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="package Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
             $packages['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit package Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
             $packages['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Delete package Information" title="Delete"><i class="fas fa-trash"></i></a>';
             $packages['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
             $packages['data'][$key]['createDate'] = $package['createDate'] == '' ? '-' : $package['createDate'];
         }

         return response()->json($packages);
     }
 }

 /**
  * Store a new package.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
     $this->validate($request, $this->validationRules);
     $package = new Package();
     $package->fill($request->all());
     $package->packageName =$request->packageName;
     $package->isActive = $request->status;
     
     if($package->save()) {
        $gameData = [];
        foreach ($request->selectGame as $gameKey => $game) {
            $gameData[] = [
                'packageId'    => $package->id,
                'gameId'       => $game
            ];
        }
        DB::table('packages_asign_to_game')->insert($gameData);
        return redirect()->route('packages.index')->with('success',trans('messages.packages.create.success'));
     }
     return redirect()->route('packages.index')->with('error',trans('messages.packages.create.error'));
 }

 /**
  * 
  * View package Information
  * 
  * @param Request $request
  * @param string $id
  * 
  * @return view 
  */
 public function show(Request $request, package $package)
 {    
     $packageInfo = Package::selectRaw('packages.id,packages.packageName,packages.isActive,DATE_FORMAT(packages.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate')
                       ->where('packages.id',$package->id)
                       ->first();
        $getGame = PackagesAsignToGame::selectRaw('games.gameName')
        ->join('games', 'games.id', 'packages_asign_to_game.gameId')
        ->where('packageId', $package->id)->pluck('gameName')->toArray();

        $gameNameString = implode(', ', $getGame);


     return view('admin.packages.package_view',[
         'packageInfo' => $packageInfo,
         'gameName' => $gameNameString
        
     ]);
 }

 /**
  * Show the form for editing package.
  *
  * @param  $package
  * @return $package
  */
 public function edit(package $package)
 {   
    $gameIds = DB::table('packages_asign_to_game')->distinct('gameId')->pluck('gameId')->toArray();
    $gameAsignedIds = DB::table('packages_asign_to_game')->where('packageId', $package->id)->pluck('gameId')->toArray();
    $choseGamealow = array_diff($gameIds, $gameAsignedIds);
    $gameList = Game::where('isActive',1)->whereNotIn('id', $choseGamealow)->get();

    return view('admin.packages.package_create', [
       'gameList'       => $gameList,
       'package'        => $package,
       'gameAsignedIds' => $gameAsignedIds
   ]);
 }

 /**
  * Update the specified resource in database storage.
  *
  * @param Request $request
  * @param object $package
  * 
  * @return Redirect
  */
 public function update(Request $request,package $package)
 {

    // echo "<pre>";
    // print_r($request->all());
    // exit;
     $this->validate($request, $this->validationRules);
     $package->fill($request->all());
     $package->packageName =$request->packageName;
     $package->isActive = $request->status;


        //  echo "<pre>";
        // print_r($selectGame);
        $alreadyExiestGame = PackagesAsignToGame::where('packageId', $package->id)->pluck('gameId')->toArray();
        $selectGame = $request->selectGame;
            //  Game Id Insert  
            $insertGameId = array_diff($selectGame, $alreadyExiestGame);
            // Game Id Remove
            $removeGameId = array_diff($alreadyExiestGame, $selectGame);


                // echo "<pre>";
                // print_r($insertGameId);

                //     echo "<pre>";
                //     print_r($removeGameId);
                // exit;

      

    if($package->save())
    {
        DB::table('packages_asign_to_game')->where('packageId', $package->id)->delete();
        $gameData = [];
        foreach ($request->selectGame as $gameKey => $game) {
            $gameData[] = [
                'packageId'    => $package->id,
                'gameId'       => $game
            ];
        }
        DB::table('packages_asign_to_game')->insert($gameData);

        

            if (!empty($insertGameId)) {
                foreach ($insertGameId as $value) {

                    $dbQuery = "INSERT INTO user_game_history(userId, subscriptionHistoriesId,gameId,isTrial,isSubscribed,isKeepNotification)
                    SELECT 
                    userId,
                    subscriptionHistoriesId,
                    " . $value . ",
                    isTrial,
                    1,
                    '0'
                    FROM 
                    user_packages_subscription_histories where sportPackageId =" . $package->id . ";";
                    \DB::select($dbQuery);
                }
            }

            
            return redirect()->route('packages.index')->with('success',trans('messages.packages.update.success'));
    }

     return redirect()->route('packages.index')->with('error',trans('messages.packages.update.error'));
 }

 /**
  * Destroy package information.
  *
  * @param object $package
  * 
  * @return Redirect
 */
 public function destroy(package $package)
 {
     if($package->delete())
     {
        DB::table('packages_asign_to_game')->where('packageId', $package->id)->delete();
         return redirect(route('packages.index'))->with('success', trans('messages.packages.delete.success'));
     }
     
     return redirect(route('packages.index'))->with('error', trans('messages.packages.delete.error'));
 }

 /**
  * Change status of the package.
  *
  * @param object $package
  * 
  * @return Redirect
 */
 public function changeStatus($package)
 {
     $package = Package::find($package);

     if (empty($package)) 
     {
         return redirect(route('packages.index'))->with('error', trans('messages.packages.not_found'));
     }

     $package->isActive = !$package->isActive;

     if($package->save())
     {
         $status = $package->isActive ? 'active' : 'inactive';
         return redirect(route('packages.index'))->with('success', trans('messages.packages.status.success', ['status' => $status]));
     }

     return redirect(route('packages.index'))->with('error', trans('messages.packages.status.error'));
 }
    
}
