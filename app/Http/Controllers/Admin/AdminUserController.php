<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
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

class AdminUserController extends Controller
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
     * create new user refferal Code
     *
    */

    public function refferal_Code($length)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
    }

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
        return view('admin.users.user_list');
    }
    
   /**
    * Show the form for creating a new  user .
    *
    */
    public function create()
    {
        return view('admin.users.user_create');
    }

    /**
     * Search Users.
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
                         users.userType,users.profilePic,DATE_FORMAT(users.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as joinDate')->where('users.userType','User');
                           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                
                $query->orWhere('users.name', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.email', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.userType', 'like', '%'.$request->search['value'].'%');
            });

            $users = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $users['recordsFiltered'] = $users['recordsTotal'] = $users['total'];
            
            foreach ($users['data'] as $key => $user) {
                
                $params = [
                    'user' => $user['userInfoId']
                ];
                
                $users['data'][$key]['sr_no'] = $startNo+$key;
                
                $viewRoute = route('users.show', $params);
                $statusRoute = route('users.status', $params);
                $editRoute = route('users.edit', $params);
                $deleteRoute = route('users.destroy', $params);
                $userProfileImage = (!empty($user['profilePic'])) ?  url('images/profiles/'.$user['profilePic'] )  :  url('images/default.jpg');
                
                $status = ($user['isActive']== 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
                $users['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="User Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                // $users['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit User Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
              //  $users['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Delete User Information" title="Delete"><i class="fas fa-trash"></i></a>';
                $users['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
                $users['data'][$key]['image'] =  !empty($user['profilePic'])  ?  '<img src="'.url(config('constant.PROFILES').'/'.$user['profilePic']).'>' : '<img src="'.url('images/default_profile.jpg').'" >' ;
                $users['data'][$key]['name'] = ' <div class="user-img"> <img src="'.$userProfileImage.'" class="img-circle " height="30px;" /> &nbsp;&nbsp;'. $user['name'] .'</div>';
                $users['data'][$key]['joinDate'] = $user['joinDate'] == '' ? '-' : $user['joinDate'];
            }
            
            return response()->json($users);
        }
    }

    /**
     * Store a new  user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check validation
        $this->validationRules['password'] = 'required|max:15|min:6';
        $this->validationRules['email'] = 'required|email|unique:users,deletedAt,NULL|max:190';
        $this->validationRules['name'] = 'required|max:255';
        $this->validate($request, $this->validationRules);
        
        $user = new User();
        $user->fill($request->all());
        $user->isActive = $request->status;
        $user->showPassword =$request->password;
        $user->referralCode = self::refferal_Code(9);
        
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
                
            return redirect()->route('users.index')->with('success',trans('messages.users.create.success'));
        }
        return redirect()->route('users.index')->with('error',trans('messages.users.create.error'));

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
    public function show(Request $request, User $user)
    {    
        $userInfo = User::selectRaw('users.id as userInfoId,users.name,users.email,users.isActive,users.showPassword,
        users.userType,users.mobileNumber,users.profilePic,DATE_FORMAT(users.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as joinDate')
                          ->where('users.userType','User')
                          ->where('users.id',$user->id)
                          ->first();
        return view('admin.users.user_view',[
            'userInfo' => $userInfo,
        ]);
    }

    /**
     * Show the form for editing the user info.
     *
     * @param  $user
     * @return $user
     */
    public function edit(User $user)
    {   

        return view('admin.users.user_create', [
            'user'=> $user
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
    public function update(Request $request,User $user)
    {
        $this->validationRules['email'] = 'required|email|max:150|unique:users,email,'.$user->id.',id';
        $this->validate($request, $this->validationRules);
            
        $user->fill($request->all());
        $user->isActive = $request->status;
        $user->showPassword =$request->password;
        $user->password =bcrypt($request->password);

        if($request->hasFile('profilePic'))
        {
             $iconImagePath = public_path('images/profiles');
             $user->profilePic = self::savePhoto($request->file('profilePic'), 'images/profiles', $iconImagePath, 'user_profile', 100, 100);    
        }
        
        if($user->save())
        {
            return redirect()->route('users.index')->with('success',trans('messages.users.update.success'));
        }

        return redirect()->route('users.index')->with('error',trans('messages.users.update.error'));
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

            return redirect(route('users.index'))->with('success', trans('messages.users.delete.success'));
        }
        
        return redirect(route('users.index'))->with('error', trans('messages.users.delete.error'));
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
            return redirect(route('users.index'))->with('error', trans('messages.users.not_found'));
        }

        $user->isActive = !$user->isActive;

        if($user->save())
        {
            $status = $user->isActive ? 'active' : 'inactive';
       
            return redirect(route('users.index'))->with('success', trans('messages.users.status.success', ['status' => $status]));
        }

        return redirect(route('users.index'))->with('error', trans('messages.users.status.error'));
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