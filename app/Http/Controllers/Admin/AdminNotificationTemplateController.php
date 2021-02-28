<?php

namespace App\Http\Controllers\Admin;

use App\Model\NotificationTemplate;
use App\Model\AdminUser;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\RestaurantCustomer;
use App\Helpers\CommonHelper;
class AdminNotificationTemplateController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    |  Admin Notification Tempplate Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles super admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * ValidationRules for NotificationTempplate
     * @var array
     */
    private $validationRules = [
        'title' => 'required|string|max:180',
        'content' => 'required|string'
    ];

    /**
     * Display a listing of the notification template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.notification_template.index');
    }

    /**
     * Show the form for creating a new notification template.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
         return view('admin.notification_template.create');
    }

     /**
     * Show edit notification template page
     *
     * @param NotificationTemplate $notification_template
     * @return $notificationTemplate
     */
    public function edit(NotificationTemplate $notification_template)
    {   
        return view('admin.notification_template.create', [
            'notificationTemplate' => $notification_template
        ]);
    }  

    /**
     * Load notification template content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request){

        if($request->ajax()){
           
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;

            //$userId =  \Auth::guard('superadmin')->user()->id;

            $query = NotificationTemplate::select('*');
           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function($query) use ($request){
                $query->orWhere('title', 'like', '%'.$request->search['value'].'%')
                      ->orWhere('content', 'like', '%'.$request->search['value'].'%');  
            });

            $notificationTemplates = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();
           
            $notificationTemplates['recordsFiltered'] = $notificationTemplates['recordsTotal'] = $notificationTemplates['total'];

            foreach ($notificationTemplates['data'] as $key=> $notificationTemplate){

                $params = [
                    'notification_template' => $notificationTemplate['id']
                ];

                $notificationTemplates['data'][$key]['sr_no'] = $startNo+$key;

                $editRoute = route('notification-templates.edit', $params);
                $statusRoute = route('notification-templates.status', $params);
                $deleteRoute = route('notification-templates.destroy', $params);
                $sendNotificationRoute =route('send-notification',$params);

                $status = ($notificationTemplate['status']) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">
                Inactive</span>';

                $notificationTemplates['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
            
                $notificationTemplates['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$sendNotificationRoute.'" class="btn btn-primary btnSendNotification" title="Send Notification to All Customers"><i class="fas fa-paper-plane"></i></a>&nbsp&nbsp';

                $notificationTemplates['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Notification Template" title="Delete"><i class="fas fa-trash"></i></a>&nbsp&nbsp';
                $notificationTemplates['data'][$key]['status'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
                $notificationTemplates['data'][$key]['content']= str_limit($notificationTemplate['content'],35,'...');
            }
            
            return response()->Json($notificationTemplates);
        }
    }

    /**
     *send notification
     *
     * @param NotificationTemplate $admin_notification_template
     * 
     */
    public function sendNotification(NotificationTemplate $notification_template)
    {
        //$adminId = \Auth::guard('superadmin')->user()->id;
        $createdAt = Carbon::now()->toDateTimeString();

        $title = addslashes($notification_template->title);
        $content = addslashes($notification_template->content);

        // $dbQuery = "INSERT INTO notifications(senderId, userId, notificationType,module, content, title, createdAt, updatedAt)
        //             SELECT '1',users.id,'4','FromAdmin','".$content."','".$title."','".$createdAt."','".$createdAt."' FROM 
        //                users where userType='User';";
            $senderId = 1;
            $notificationType = 4;
            $module = 'FromAdmin';
            $image =  url('admin-assets/images/logo/vp.png');



        $dbQuery = "INSERT INTO notifications(senderId, userId,notificationTemplateId,notificationType, module, title,content,media,createdAt)
            SELECT DISTINCT
            '" . $senderId . "',
            id,
             '" . $notification_template->id . "',
            '" . $notificationType . "',
            '" . $module . "',
            '" . $title . "',
            '" . $content . "',
             '" . $image . "',
            '" . Carbon::now() . "'
            FROM 
            users where   userType = 'User';";

           DB::select($dbQuery);


        // this command send push notification to all users 
        $cmd = 'cd '.base_path(). ' && php artisan SendTemplateNotification:send "' . $notificationType . '" "'.$notification_template->id.'"';
        //\Log::debug($cmd);
        exec($cmd. '> /dev/null &');
        
        return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.admin_send_notification.success'));

    }

    /**
     * Store a newly created resource in notification template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        
        $userId =  \Auth::guard('superadmin')->user()->id;

        $notificationTemplate = new NotificationTemplate();
        $notificationTemplate->fill($request->all());
        $notificationTemplate->userId= $userId;

        if($notificationTemplate->save()){
           
            // $notificationType = 6;
            // $module = 'template';
            // $senderId = 1;
            // $title = "Template title";
            // $content = "Template Complated";

            // $dbQuery = "INSERT INTO notifications(senderId, userId,notificationTemplateId,notificationType, module, title,content)
            // SELECT 
            // '" . $senderId . "',
            // id,
            //  '" . $notificationTemplate->id . "',
            // '" . $notificationType . "',
            // '" . $module . "',
            // '" . $title . "',
            // '" . $content . "'
            // FROM 
            // users where   userType = 'User';";


            // \DB::select($dbQuery);

            // $cmd = 'cd ' . base_path() . ' && php artisan SendTemplateNotification:send "' . $notificationType . '" "' . $notificationTemplate->id . '"';
            // \Log::debug($cmd);
            // exec($cmd . '> /dev/null &');

            return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.create.success'));
        }
        return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.create.error'));

    }

    /**
     * Change status of notification template.
     *
     * @param NotificationTemplate $notification_template
     * @return mixed
     */
    public function changeStatus(NotificationTemplate $notification_template){

        $notification_template->status = !$notification_template->status;

        if($notification_template->save()){
            $status = $notification_template->status ? 'Active' : 'Inactive';
            return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.status.success',['status' => $status]));
        }
        return redirect(route('notification-templates.index'))->with('error',trans('messages.notification_template.status.error'));
    }
    
    /**
     * Update the specified resource in notification templates.
     *
     * @param  Request  $request
     * @param  NotificationTemplate $notification_template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,NotificationTemplate $notification_template)
    {
        $this->validate($request, $this->validationRules);

        $notification_template->fill($request->all());

        if($notification_template->save()){
            return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.update.success'));
        }
        return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.update.error'));

    }

    /**
     * Remove the specified resource from notification templates.
     *
     * @param  NotificationTemplate $admin_notification_template
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotificationTemplate $notification_template)
    {
     
        if($notification_template->delete()){
            return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.delete.success'));
        }
        return redirect(route('notification-templates.index'))->with('success', trans('messages.notification_template.delete.error'));

    }
}
