<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use App\Model\Package;
use App\Mail\SendWelcomeMail;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    |  Admin user Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles user related information functionality for the applications.
    |
    */

    /**
     * List out validation for user
     *
    */
   private $validationRules = [
       'name' => 'required|string|min:3|max:50',
   ];

    /**
     * Show User List
     *
     * @return view
     */
    public function index()
    {   
        return view('admin.admins.admin_list');
    }
    
   /**
    * Show the form for creating a new  user .
    *
    */
    public function create()
    {
        $packages = Package::where('isActive',1)->get();
        return view('admin.admins.admin_create',['packages' => $packages ] );
    }

    /**
     * Search Admins.
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
            $query = User::selectRaw('users.id as userInfoId,users.name,users.email,users.isActive,
                         users.userType,users.profilePic,DATE_FORMAT(users.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as joinDate')->where('users.userType','Admin');
                           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                
                $query->orWhere('users.name', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.email', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.userType', 'like', '%'.$request->search['value'].'%');
            });

            $admins = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $admins['recordsFiltered'] = $admins['recordsTotal'] = $admins['total'];
            
            foreach ($admins['data'] as $key => $admin) {
                
                $params = [
                    'admin' => $admin['userInfoId']
                ];
                
                $admins['data'][$key]['sr_no'] = $startNo+$key;
                
                $viewRoute = route('admins.show', $params);
                $statusRoute = route('admins.status', $params);
                $editRoute = route('admins.edit', $params);
                //$deleteRoute = route('admins.destroy', $params);
                $userProfileImage = (!empty($admin['profilePic'])) ?  url('images/profiles/'.$admin['profilePic'] )  :  url('images/default.jpg');
                
                $status = ($admin['isActive']== 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
                $admins['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Admin Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $admins['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit admin Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
              //  $admins['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Delete admin Information" title="Delete"><i class="fas fa-trash"></i></a>';
                $admins['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
                $admins['data'][$key]['image'] =  !empty($admin['profilePic'])  ?  '<img src="'.url(config('constant.PROFILES').'/'.$admin['profilePic']).'>' : '<img src="'.url('images/default_profile.jpg').'" >' ;
                $admins['data'][$key]['name'] = ' <div class="user-img"> <img src="'.$userProfileImage.'" class="img-circle " height="30px;" /> &nbsp;&nbsp;'. $admin['name'] .'</div>';
                $admins['data'][$key]['joinDate'] = $admin['joinDate'] == '' ? '-' : $admin['joinDate'];
            }
            
            return response()->json($admins);
        }
    }

    /**
     * Store a new  admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check validation
        $this->validationRules['password'] = 'required|max:15|min:6';
        $this->validationRules['email'] = 'required|email|unique:users,deletedAt,NULL|max:190';
        $this->validate($request, $this->validationRules);
        
        $user = new User();
        $user->fill($request->all());
        $user->isActive = $request->status;
        $user->showPassword =$request->password;
        $user->userType = 'Admin';
        $user->packageId = $request->package;
        
        $user->password =bcrypt($request->password);

        $iconImagePath = public_path('images/profiles');

        // Check file is exist or not
        if($request->hasFile('profilePic'))
        {
            $user->profilePic = self::savePhoto($request->file('profilePic'), 'images/profiles', $iconImagePath, 'user_profile', 100, 100);    
        }
      
        if($user->save()) {
             
            // send welcome mail
            \Mail::to($request->email)->send(new SendWelcomeMail($user));
                
            return redirect()->route('admins.index')->with('success',trans('messages.admins.create.success'));
        }
        return redirect()->route('admins.index')->with('error',trans('messages.admins.create.error'));

    }

    /**
     * 
     * View User Information
     * 
     * @param Request $request
     * @param string $userId
     * 
     * @return view 
    */
    public function show(Request $request, User $admin)
    {    
        $adminInfo = User::selectRaw('users.id as userInfoId,users.name,users.packageId,users.email,users.isActive,users.showPassword,
                        users.userType,users.profilePic,
                        DATE_FORMAT(users.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as joinDate')
                          ->where('users.userType','Admin')
                          ->where('users.id',$admin->id)
                          ->first();
        return view('admin.admins.admin_view',[
            'adminInfo' => $adminInfo,
        ]);
    }

    /**
     * Show the form for editing the user info.
     *
     * @param  $user
     * @return $user
     */
    public function edit(User $admin)
    {   
        $packages = Package::where('isActive',1)->get();
        return view('admin.admins.admin_create', [
            'admin'=> $admin,
            'packages' => $packages
        ]);
    }

    /**
     * Update the specified resource in database storage.
     *
     * @param Request $request
     * @param object $user
     * 
     * @return Redirect
    */
    public function update(Request $request,User $admin)
    {
        $this->validationRules['email'] = 'required|email|max:150|unique:users,email,'.$admin->id.',id';
        $this->validate($request, $this->validationRules);
            
        $admin->fill($request->all());
        $admin->isActive = $request->status;
        $admin->showPassword =$request->password;
        $admin->password =bcrypt($request->password);
        $admin->packageId = $request->package;

        if($request->hasFile('profilePic'))
        {
             $iconImagePath = public_path('images/profiles');
             $admin->profilePic = self::savePhoto($request->file('profilePic'), 'images/profiles', $iconImagePath, 'user_profile', 100, 100);    
        }
        
        if($admin->save())
        {
            return redirect()->route('admins.index')->with('success',trans('messages.admins.update.success'));
        }

        return redirect()->route('admins.index')->with('error',trans('messages.admins.update.error'));
    }

    /**
     * Destroy user information.
     *
     * @param object $user
     * 
     * @return Redirect
    */
    public function destroy(User $user)
    {
        $userImage = $user->profilePic;
        if($user->delete())
        {
            $file = public_path('images/profiles/'.$userImage);
            is_file($file) ? unlink($file) : '';

            return redirect(route('admins.index'))->with('success', trans('messages.admins.delete.success'));
        }
        
        return redirect(route('admins.index'))->with('error', trans('messages.admins.delete.error'));
    }

    /**
     * Change status of the user.
     *
     * @param object $user
     * 
     * @return Redirect
    */
    public function changeStatus($user)
    {
//        $decryptedId = CommonHelper::decrypt($user);
        $user = User::find($user);

        if (empty($user)) 
        {
            return redirect(route('admins.index'))->with('error', trans('messages.admins.not_found'));
        }

        $user->isActive = !$user->isActive;

        if($user->save())
        {
            $status = $user->isActive ? 'active' : 'inactive';
       
            return redirect(route('admins.index'))->with('success', trans('messages.admins.status.success', ['status' => $status]));
        }

        return redirect(route('admins.index'))->with('error', trans('messages.admins.status.error'));
    }

     /**
     * Save user image.
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
            if(!empty($imageName))
            {
                // param =  image,path,thumailImagePath,prefix,height,width
                ImageHelper::imageResize($imageName,$path,$thumailImagePath,$height,$width);
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