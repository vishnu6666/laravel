<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ImageHelper;
use App\Model\User;
use App\Model\UserSubscriptionHistories;


class UserController extends Controller
{
    /**
     * Edit user profile.
     *
     * @param Request $request
     *
     * @return json
     */
    public function editProfile(Request $request)
    {
        $user = Auth::guard('api')->user();

        if(!empty($request->mobileNumber)){
            $this->validate($request, [
                'name' => 'max:60',
                'email' => 'unique:users,email,'.$user->id,
                'mobileNumber' => 'unique:users,mobileNumber,'.$user->id,
            ]);
            
        }else{
            $this->validate($request, [
                'name' => 'max:60',
                'email' => 'unique:users,email,'.$user->id
            ]);
        }
        
        
        if($request->email != $user->email){
            $otp = rand(1000, 9999);
            $user->otp = $otp;
            $cmd = 'cd ' . base_path() . ' && php artisan mail:resendotp "' . $request->email . '" "' . $user->id . '" ';
            exec($cmd . ' > /dev/null &');
        } 
    
        $user->name = $request->name;
        $user->mobileNumber = $request->mobileNumber;

        if ($user->save()) {
            $userDetail = ApiHelper::getUserById($user->id)->first();
            return $this->toJson(['userDetail' => $userDetail], trans('api.edit_profile.success'), 1);
        }
        return $this->toJson(null, trans('api.edit_profile.error'), 0);

    }
    

     /**
     * Upload media
     *
     * @param Request $request
     * 
     * @return json
    */
    public function uploadProfilePhoto(Request $request)
    {
        $user = Auth::guard('api')->user();

        $this->validate($request, [
            'media' => 'required|image',
        ]);

        // Check for image is available or not
        if($request->hasFile('media'))
        {
            $request->module = 'profiles';

            $destinationPath = public_path('images/' . $request->module);

            $filePrefix = $request->module;
            
            $fileName = $this->savePhotoProfile($request->file('media'),'images/profiles',$destinationPath ,'user_profile',100,100);
            $path = url('images/'.$request->module.'/'.$fileName);
            if ($request->module == 'profiles') {
                $user->profilePic = $fileName;
                $user->save();
            }
            return $this->toJson(['profilePic' => $path], trans('api.common.upload_media_success'), 1);
        }

        return $this->toJson(null, trans('api.common.upload_media_error'), 0);
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

    private function savePhotoProfile($image, $path, $thumailImagePath, $prefix, $height, $width)
    {
        if (!empty($image)) {
            $imageName = ImageHelper::imageSave($image, $path, $prefix);

            // if (!empty($imageName)) {
            //     ImageHelper::imageResize($imageName, $path, $thumailImagePath, $height, $width);
            // }
            return $imageName;
        }
        return null;
    }

    private function savePhoto($image,$path,$thumailImagePath,$prefix,$height,$width)
    {
        if(!empty($image))
        {
            $imageName= ImageHelper::imageSave($image,$path,$prefix);

            if(!empty($imageName))
            {
                ImageHelper::imageResize($imageName,$path,$thumailImagePath,$height,$width);
            }
            return $imageName;
        }
        return null;
    }

    /**
    * Destroy profile image.
    *
    * 
    */
    public function removeProfilePhoto()
    {
        $user = Auth::guard('api')->user();

        $profileImage = $user->profilePic;
        
        if($profileImage)
        {
            User::where('id',$user->id)->update(['profilePic' => Null]);
            $profilePath = url(config('constant.PROFILES')).'/default.jpg';
            return $this->toJson(['profilePic' => $profilePath], trans('api.profile.profilePicDeleted'), 1);
        }
        return $this->toJson([], trans('api.profile.profilePicNotFound'), 0);
    }

    /**
     * get User Subscription History list
     *
     * @param Request null
     *
     * @return Response Json
     *
     */
    public function getUserSubscriptionHistory()
    {
        $user = Auth::guard('api')->user();

        $historyList = UserSubscriptionHistories::selectRaw("id,planType,planName,packageName,planAmount,DATE_FORMAT(createdAt,'%D %b %Y, %h:%i %p') as createdAt")->where(['userId'=>$user->id,'isTrial' => 0])->orderBy('id', 'DESC')->get();
        
        if($historyList->isNotEmpty())
        {
            return $this->toJson(['historyList' => $historyList], trans('api.history.history_found'), 1);
        }

        return $this->toJson([], trans('api.history.history_not_found'), 0);
        }

}
