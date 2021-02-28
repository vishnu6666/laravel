<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Model\Notification;
use App\Model\User;
class AdminNotificationController extends Controller
{   

    /**
     * Display a listing of the notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.notification.index');
    }

    /**
     * Display a listing of the notification.
     *
     * @param Notification $notification
     * @return notification
     */
    public function show(Notification $notification)
    {
        $gameImagePath = url(config('constant.GAMEIMAGES'));
        return view('admin.notification.view',[
            'notification' => $notification,
            'mediapath' => $gameImagePath
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
           
            $query = Notification::join('users', 'users.id', 'notifications.userId')
                           ->selectRaw('users.name, notifications.*,
                           DATE_FORMAT(notifications.createdAt,"%d-%M-%Y %H:%i:%s") as createdAtDate');
 
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function($query) use ($request){
                    $query->orWhere('title', 'like', '%'.$request->search['value'].'%')
                          ->orWhere('content', 'like', '%'.$request->search['value'].'%')
                          ->orWhere('name', 'like', '%'.$request->search['value'].'%')
                          ->orWhere('users.name', 'like', '%'.$request->search['value'].'%');
            }); 
            
            $notifications = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $notifications['recordsFiltered'] = $notifications['recordsTotal'] = $notifications['total'];

            foreach ($notifications['data'] as $key => $notification) {

                $params = [
                    'notification' => $notification['id']
                ];

                $notifications['data'][$key]['sr_no'] = $startNo+$key;

                $viewRoute = route('admin.show.notification.detail', $params);

                if($notifications['data'][$key]['title'] == 'Alert!'){
                    $notifications['data'][$key]['title'] = $notifications['data'][$key]['content'];
                }

                $notifications['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-success" title="View Notification"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
            }
            
            return response()->json($notifications);
        }
         return abort(404); 
    }

}
