<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Model\UserMessage;
use App\Model\User;
use App\Model\Package;
use App\Model\Notification;
use App\Model\PackagesAsignToGame;
use App\Model\Game;
use App\Model\UserPackagesSubscriptionHistories;
use Illuminate\Support\Carbon;

class AdminMessageController extends Controller

{   
    private $validationRules = [
        'content' => 'required'
     ];

    /**
     * Display a listing of the Message.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.message.index');
    }

    /**
     * Display a listing of the message.
     *
     * @param UserMessage $message
     * @return UserMessage
     */
    public function show(Package $package)
    {
        
        return view('admin.message.message',[
            'package' => $package
        ]);
    }

    /**
     * Search notification.
     *
     * @param Request $request
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
           
            $query = Package::selectRaw('packages.id,packages.packageName,DATE_FORMAT(packages.createdAt,"%d-%M-%Y %H:%i:%s") as createdAt');
 
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

                $params = [
                    'package' => $package['id']
                ];

                $packages['data'][$key]['sr_no'] = $startNo+$key;

                $viewRoute = route('show.message.detail', $params);
                $create = route('show.message.create', $params);

                $packages['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-success" title="View Messages"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $packages['data'][$key]['action'] .= '<a href="'.$create.'" class="btn btn-success" title="Send Message"><i class="fa fa-plus-circle"></i></a>&nbsp&nbsp';
                $packages['data'][$key]['createdAt'] = date('jS F Y', strtotime($package['createdAt']));
            }
            
            return response()->json($packages);
        }
         return abort(404); 
    }

     /**
     * Search notification.
     *
     * @param Request $request
     * @return json
     */
    public function messagesearch(Request $request)
    {   

        
      
        if($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
           
            $query = UserMessage::selectRaw('user_messages.id,user_messages.userId,
                                            user_messages.content,
                                            user_messages.isRead,
                                            users.name,
                                            DATE_FORMAT(user_messages.createdAt,"%d-%M-%Y %H:%i:%s") as createdAt')
                                ->leftJoin('users', 'users.id', 'user_messages.userId')
                                ->where('user_messages.sportPackageId',$request->packageId)->groupBy('user_messages.createdAt');
//  ->leftJoin('users', 'users.id', 'user_messages.userId')

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function($query) use ($request){
                    $query->orWhere('user_messages.content', 'like', '%'.$request->search['value'].'%');
                    $query->orWhere('users.name', 'like', '%'.$request->search['value'].'%');
            }); 
            
            $messages = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $messages['recordsFiltered'] = $messages['recordsTotal'] = $messages['total'];

            foreach ($messages['data'] as $key => $message) {

                $params = [
                    'package' => $message['id']
                ];

                $messages['data'][$key]['sr_no'] = $startNo+$key;
                // $messages['data'][$key]['content'] = strlen($message['content']) > 125 ? substr($message['content'],0, 125)."..." : $message['content'];

                //$viewRoute = route('show.message.detail', $params);

                //$messages['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-success" title="View Messages"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
            }
            
            return response()->json($messages);
        }
         return abort(404); 
    }

    /**
     * Display a listing of the message.
     *
     * @param UserMessage $message
     * @return UserMessage
     */
    public function create(Package $package)
    {

        $getGame = PackagesAsignToGame::selectRaw('packages_asign_to_game.*,games.gameName')
        ->join('games', 'games.id', 'packages_asign_to_game.gameId')
        ->where('isActive', 1)
        ->where('packageId', $package->id)->get();


        // $html = '';
        // if (!empty($getGame)) {
        //     foreach ($getGame as $value) {
        //         $selected = '';
        //         if (isset($hiddenCityValue) && $hiddenCityValue == $value->gameId) {
        //             $selected = 'selected';
        //         }
        //         $html .= '<option value="' . $value->gameId . '" ' . $selected . '>' . $value->gameName . '</option>';
        //     }
        // }
        
        return view('admin.message.message_create',[
            'package' => $package,
            'getGame' => $getGame
        ]);
    }

    /**
    * Store a new messages.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $this->validate($request, $this->validationRules);
        $userSubscribePackages = UserPackagesSubscriptionHistories::where('sportPackageId',$request->id)->groupBy('userId')->pluck('userId')->toArray();
        $message = new UserMessage();
        $userMessageLastId= UserMessage::orderBy('id', 'desc')->first();
        $userMessageLastId = isset($userMessageLastId->id) ? $userMessageLastId->id : 0;

        $notificationType = 6;

        $gameName = Game::where('id', $request->gameId)->first();
        $messageId = $userMessageLastId + count($userSubscribePackages);
        $message = [];
        $notification = [];
        $gameImage = (!empty($gameName->gameImage)) ?  url('images/gameimage/' . $gameName->gameImage)  :  url('admin-assets/images/logo/full_logo_new.png');

        //  'title'      => 'You have received one message for ' . $gameName->gameName,
        //         'content'       => 'You have received one message for ' . $gameName->gameName,
        foreach ($userSubscribePackages as $userid) {
            $message[] = [
                'userId'       => $userid,
                'sportPackageId' => $request->id,
                'gameId' => $request->gameId,
                'content'      => $request->content,
                'isRead'       => 0,
            ];
            
            $notification[] = [
                'senderId' =>1,
                'userId'       => $userid,
                'notificationType' => $notificationType,
                'module' => 'message',
                'title'      => 'You have received one message for <b style="color: #03a9f3;">' . $gameName->gameName . '</b>',
                'content'     => 'You have received one message for <b style="color: #03a9f3;">' . $gameName->gameName . '</b>',
                'messageId' => $messageId,
                'media' => $gameImage,
                'createdAt'=> Carbon::now(),
            ];
        }

        if(!empty($userSubscribePackages)) {
            $userMessage = UserMessage::insert($message);
            Notification::insert($notification);

            // thiis command send push notification to all users 
            $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notificationType . '" "' . $messageId . '" ';
            //\Log::debug($cmd);
            exec($cmd . '> /dev/null &');

            // dd($userMessage->insertGetId());

            // echo $userMessage->id;exit;
            return redirect()->route('show.message.detail',['package' => $request->id])->with('success',trans('messages.message.create.success'));
        }
        return redirect()->route('show.message.detail',['package' => $request->id])->with('error',trans('messages.message.create.error'));
    }

}
