<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Helpers\ApiHelper;
use App\Helpers\BraintreeHelper;
use App\Model\City;
use App\Model\Country;
use App\Model\LogoCollection;
use App\Model\LogoCollectionCategory;
use App\Model\State;
use App\Model\Team;
use App\Model\SubscriptionPlan;
use Illuminate\Support\Str;

class CommonController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Common Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles common apis & related features.
    */

    /**
     * Upload media
     *
     * @param Request $request
     * 
     * @return json
    */
    public function uploadImage(Request $request)
    {

        $this->validate($request, [
            'type' => 'required',
            'image' => 'required',
            'moduleId' => 'required'
        ]);
        
        // Check for image is available or not
        if($request->hasFile('image'))
        {
            $image = $request->image;
            $destinationPath = public_path('images/' . $request->type);

            $filePrefix = $request->type;
            $fileName = $filePrefix.'_'.time().'_'.strtolower(Str::random(6)).'.'.$image->getClientOriginalExtension();

            // Check for image type and decide
            if($image->move($destinationPath, $fileName))
            {
                if($request->type == "TeamLogo")
                {
                    $team = Team::find($request->moduleId);
                    $team->logoImage = $fileName;
                    $team->save();
                }
                else if($request->type == "TeamBanner")
                {
                    $team = Team::find($request->moduleId);
                    $team->bannerImage = $fileName;
                    $team->save();
                }
                else if($request->type == "AvatarImage")
                {
                    $user = User::find($request->moduleId);
                    $user->avatarImage = $fileName;
                    $user->save();
                }
                else if($request->type == "BannerImage")
                {
                    $user = User::find($request->moduleId);
                    $user->bannerImage = $fileName;
                    $user->save();
                }

                $path = url('images/'.$request->type.'/'.$fileName);

                return $this->toJson(['filePath' => $path], trans('api.common.upload_media_success'), 1);
            }
        }
        return $this->toJson(null, trans('api.common.upload_media_error'), 0);
    }

    /**
     * Generate Braintree token
     * @param Request null
     * 
     * @return Response json
     * 
     */
    public function generateBraintreeToken()
    {
        $braintreeCredential = config('constant.BRAINTREE_CREDENTIALS');

        $token = BraintreeHelper::generateToken($braintreeCredential);

        return response()->json(['token' => $token]);
    }

    /**
     * Get Country
     * @param Request void
     * @return Response json
     * 
     */
    public function getCountry()
    {
        $countries = Country::where('isActive', 1)
        ->select('id', 'shortName', 'country')
        ->orderBy('country')
        ->get();

        if($countries->isNotEmpty())
        {
           return $this->toJson(['countries' => $countries], '', 1); 
        }

        return $this->toJson([], trans('api.country_not_found'), 0);
    }


    /**
     * Get States
     * @param Request $request
     * @return Response Json
     * 
     */
    public function getStates(Request $request)
    {
        $states = State::where('isActive', 1)
            ->where('countryId', $request->countryId)
            ->select('id', 'state')
            ->orderBy('state')
            ->get();

        if ($states->isNotEmpty()) {
            return $this->toJson(['states' => $states], '', 1);
        }

        return $this->toJson([], trans('api.state_not_found'), 0);
    }


    /**
     * Get Cities
     * @param Request $request
     * @return Response Json
     * 
     */
    public function getCities(Request $request)
    {
        $cities = City::where('isActive', 1)
            ->where('stateId', $request->stateId)
            ->select('id', 'city')
            ->orderBy('city')
            ->get();

        if ($cities->isNotEmpty()) {
            return $this->toJson(['cities' => $cities], '', 1);
        }

        return $this->toJson([], trans('api.city_not_found'), 0);
    }


    /**
     * Get logo collections
     * @param Request void
     * 
     * @return Response Json
     * 
     */
    public function getLogoCollections()
    {
        $path = url(config('constant.LOGOCOLLECTION'));

        $logoCollections = LogoCollectionCategory::where('isActive', 1)
        ->selectRaw('logo_collections_category.id,logo_collections_category.categoryName')
        ->with(['logoImages' => function($query) use($path){
            $query->selectRaw('logo_collections.id,logo_collections.categoryId,
            CONCAT("' . $path . '","/",logo_collections.image) AS image,logo_collections.colour');
            
        }])->orderBy('logo_collections_category.id', 'desc')->get();

        $logoCollections = $logoCollections->filter(function($logoCollection){
            return $logoCollection->logoImages->count() > 0;
        })->values();
        
        if($logoCollections->isNotEmpty())
        {
            return $this->toJson(['logoCollections' => $logoCollections], '', 1);
        }

        return $this->toJson([], trans('api.logo_collection_not_found'), 0);
    }

    /**
     * Get subscription plan list
     * @param Request void
     * 
     * @return Response Json
     * 
     */
    public function subscriptionPlanList()
    {
       $subscriptionPlans =  SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planName,subscription_plans.offerPrice as planPrice')
       ->where('isActive',1)->get();
       
       if($subscriptionPlans->isNotEmpty())
       {
            return $this->toJson(['subscriptionPlans' => $subscriptionPlans],'',1);
       }
       return $this->toJson([], trans('api.subscription_plan_not_found'),0);
    }
}
