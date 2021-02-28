<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminUser;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide it's functionality to your applications.
    |
    */

    /**
     * Validation rules for update profile
     * 
     * @var array
     * 
    */
    private $validationRules = [
        'name' => 'required|string|max:50|min:3',
        'email' => 'required|string|email|max:150',
    ];

    /**
     * View  admin profile
     * 
     * @return view
    */
    public function showProfile()
    {
        $admin = \Auth::guard('superadmin')->user();
        $adminImage = !empty($admin->profilePic) ? url(config('constant.PROFILES').'/'.$admin->profilePic ) : url('images/default.jpg');
        
        return view('admin.profile',
        [
            'adminImage' => $adminImage,
            'admin' => $admin,
        ]);
    }

    /**
     * Update admin profile
     *
     * @param Request $request
     * 
     * @return Redirect
    */
    public function updateProfile(Request $request){

        $user = Auth::user();
        
        $admin = AdminUser::find($user->id);

        $this->validationRules['email'] = 'required|string';

        $this->validate($request,$this->validationRules);
        
        $admin->fill($request->all());
        
        if($request->file('profilePic'))
        {
             $iconImagePath = public_path('images/profiles');
            
             $admin->profilePic = self::savePhoto($request->file('profilePic'), 'images/profiles', $iconImagePath, 'admin_profile', 100, 100);    
        }
        
        if($admin->save()) {
            
            return redirect(route('EditAdminProfile'))->with('success', trans('messages.profile.update.success'));
        }

        return redirect(route('EditAdminProfile'))->with('error', trans('messages.profile.update.error'));
    }

    /**
     * Save admin image.
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
    }

    /**
     *
     * Delete admin Profile Photo
     * 
     * @return Redirect
    */
    public function profileImageDelete()
    {
        $admin = Auth::user();

        $file= $admin->profilePic;

        if(!empty($file)) {
            $filename = public_path('images/profiles/'.$admin->profilePic);
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        $admin->profilePic = '';
        if($admin->save())
        {
            return redirect()->route('EditAdminProfile')->with('success', trans('messages.profile.delete.success'));
        }

        return redirect()->route('EditAdminProfile')->with('error', trans('messages.profile.delete.error'));
    }

    /**
     * Load change password
     *
     * @return view
    */
    public function EditAdminChangePassword(){
        return view('admin.change_password');
    }

    /**
     * Change admin password
     *
     * @param Request $request
     * 
     * @return Redirect
    */
    public function updateAdminChangePassword(Request $request){

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $admin = Auth::guard('superadmin')->user();
        $currentPassword = $admin->password;

        // Check old password is current or not
        if (\Hash::check($request->current_password, $currentPassword)) {

            $admin->password = bcrypt($request->password);
            $admin->showPassword = $request->password;
            $admin->save();

            return redirect(route('EditAdminChangePassword'))->with('success', trans('messages.changePassword.success'));
        }

        return redirect(route('EditAdminChangePassword'))->with('error', trans('messages.changePassword.error'));
    }
}
