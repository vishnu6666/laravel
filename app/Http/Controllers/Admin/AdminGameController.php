<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Game;
use App\Helpers\ImageHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;


class AdminGameController extends Controller
{
/**
 * List out validation for games
 *
 */
private $validationRules = [
    'gameName' => 'required|string|min:3|max:10',
    'gameFullName' => 'required|string|min:3|max:50',
    'launchDate' => 'required',
];

/**
 * Show game List view
 *
 * @return view
 */
 public function index()
 {   
     return view('admin.games.game_list');
 }
 
/**
 * Show the form for creating a new game .
 *
 */
 public function create()
 {
     return view('admin.games.game_create');
 }

 /**
  * Search games.
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
         $query = Game::selectRaw('games.id,games.gameName,games.gameFullName,games.isActive,games.gameImage,DATE_FORMAT(games.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate');
                        
         $orderDir = $request->order[0]['dir'];
         $orderColumnId = $request->order[0]['column'];
         $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

         $query->where(function ($query) use ($request) {
             
             $query->orWhere('games.gameName', 'like', '%'.$request->search['value'].'%')
                    ->orWhere('games.gameFullName', 'like', '%'.$request->search['value'].'%');
         });

         $games = $query->orderBy($orderColumn, $orderDir)
                         ->paginate($request->length)->toArray();

         $games['recordsFiltered'] = $games['recordsTotal'] = $games['total'];
         
         foreach ($games['data'] as $key => $game) {
             $params = [ 'game' => $game['id'] ];
             $games['data'][$key]['sr_no'] = $startNo+$key;
             $viewRoute = route('games.show', $params);
             $statusRoute = route('games.status', $params);
             $editRoute = route('games.edit', $params);
             $deleteRoute = route('games.destroy', $params);
             $gameImage = (!empty($game['gameImage'])) ?  url('images/gameimage/'.$game['gameImage'] )  :  url('images/default.jpg');
             
             $status = ($game['isActive']== 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
             $games['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Game Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
             $games['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit Game Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
             $games['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Delete Game Information" title="Delete"><i class="fas fa-trash"></i></a>';
             $games['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
             $games['data'][$key]['image'] =  !empty($game['gameImage'])  ?  '<img src="'.url(config('constant.GAMEIMAGES').'/'.$game['gameImage']).'>' : '<img src="'.url('images/default_profile.jpg').'" >' ;
             $games['data'][$key]['gameName'] = ' <div class="user-img"> <img src="'.$gameImage.'" class="img-circle " height="30px;" /> &nbsp;&nbsp;'. $game['gameName'] .'</div>';
             $games['data'][$key]['createDate'] = $game['createDate'] == '' ? '-' : $game['createDate'];
         }
         
         return response()->json($games);
     }
 }

 /**
  * Store a new game.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
   
     $this->validate($request, $this->validationRules);


     $game = new Game();
     $game->fill($request->all());
     $game->gameName =$request->gameName;
     $game->gameFullName =$request->gameFullName;
     $game->isActive = $request->status;
     $game->launchDate = $request->launchDate;
     $iconImagePath = public_path('images/gameimage');

     if($request->hasFile('gameImage'))
     {
        $game->gameImage = self::savePhoto($request->file('gameImage'), 'images/gameimage', $iconImagePath, 'game_image', 200, 200);    
     }

     if($game->save()) {
            $notificationType = 1;
            $module = 'game create';
            $senderId = 1;
            // $title = $request->gameName . ' game upload';
            // $content = $request->gameName . ' game upload';


            $title =   '<b style="color: #03a9f3;">' . $request->gameName . '</b>   new game added';
            $content = '<b style="color: #03a9f3;">' . $request->gameName . '</b>   new game added';


           
            $gameImage = (!empty($game->gameImage)) ?  url('images/gameimage/' . $game->gameImage)  :  url('admin-assets/images/logo/full_logo_new.png');


            $dbQuery = "INSERT INTO notifications(senderId, userId,gameId,notificationType, module, title,content,media,createdAt)
            SELECT DISTINCT
            '" . $senderId . "',
            id,
             '" . $game->id . "',
            '" . $notificationType . "',
            '" . $module . "',
            '" . $title . "',
            '" . $content . "',
            '" . $gameImage . "',
            '" . Carbon::now() . "'

            FROM 
            users where   userType = 'User';";

            \DB::select($dbQuery);

            // thiis command send push notification to all users 
            $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notificationType . '" "' . $game->id . '" ';
            //\Log::debug($cmd);
            exec($cmd . '> /dev/null &');


        return redirect()->route('games.index')->with('success',trans('messages.games.create.success'));
     }
     return redirect()->route('games.index')->with('error',trans('messages.games.create.error'));
 }

 /**
  * 
  * View game Information
  * 
  * @param Request $request
  * @param string $id
  * 
  * @return view 
  */
 public function show(Request $request, Game $game)
 {    
     $gameInfo = Game::selectRaw('games.id,games.gameName,games.gameFullName,games.isActive,games.gameImage,DATE_FORMAT(games.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate,DATE_FORMAT(games.launchDate,"%d %b %Y") as launchDate')
                       ->where('games.id',$game->id)
                       ->first();
     return view('admin.games.game_view',[
         'gameInfo' => $gameInfo,
     ]);
 }

 /**
  * Show the form for editing game.
  *
  * @param  $game
  * @return $game
  */
 public function edit(Game $game)
 {   

    return view('admin.games.game_create', [
         'game'=> $game
    ]);
 }

 /**
  * Update the specified resource in database storage.
  *
  * @param Request $request
  * @param object $game
  * 
  * @return Redirect
  */
 public function update(Request $request,Game $game)
 {
       

     $this->validate($request, $this->validationRules);
     $game->fill($request->all());
     $game->gameName =$request->gameName;
     $game->gameFullName =$request->gameFullName;
     $game->isActive = $request->status;
     $game->launchDate = $request->launchDate;
     
     if($request->hasFile('gameImage'))
     {
          $gameImagePath = public_path('images/gameimage');
          $game->gameImage = self::savePhoto($request->file('gameImage'), 'images/gameimage', $gameImagePath, 'game_image', 200, 200);
              
     }
     
     if($game->save())
     {
         return redirect()->route('games.index')->with('success',trans('messages.games.update.success'));
     }

     return redirect()->route('games.index')->with('error',trans('messages.games.update.error'));
 }

 /**
  * Destroy game information.
  *
  * @param object $game
  * 
  * @return Redirect
 */
 public function destroy(Game $game)
 {
     $gameImage = $game->gameImage;
     if($game->delete())
     {
         $file = public_path('images/gameimage/'.$gameImage);
         is_file($file) ? unlink($file) : '';

         return redirect(route('games.index'))->with('success', trans('messages.games.delete.success'));
     }
     
     return redirect(route('games.index'))->with('error', trans('messages.games.delete.error'));
 }

 /**
  * Change status of the game.
  *
  * @param object $game
  * 
  * @return Redirect
 */
 public function changeStatus($game)
 {
     $game = Game::find($game);

     if (empty($game)) 
     {
         return redirect(route('games.index'))->with('error', trans('messages.games.not_found'));
     }

     $game->isActive = !$game->isActive;

     if($game->save())
     {
         $status = $game->isActive ? 'active' : 'inactive';
         return redirect(route('games.index'))->with('success', trans('messages.games.status.success', ['status' => $status]));
     }

     return redirect(route('games.index'))->with('error', trans('messages.games.status.error'));
 }

  /**
  * Save game image.
  *
  * @param $image,
  * @param $path,
  * @param $thumailImagePath,
  * @param $prefix,
  * @param $height,
  * @param $width
  * 
  * @return $imageName
 */

 private function savePhoto($image,$path,$thumailImagePath,$prefix,$height,$width)
 {
     if(!empty($image))
     {
         $imageName= ImageHelper::imageSave($image,$path,$prefix);
        //  if(!empty($imageName))
        //  {
        //      // param =  image,path,thumailImagePath,prefix,height,width
        //      ImageHelper::imageResize($imageName,$path,$thumailImagePath,$height,$width);
        //  }
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
