<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\PageContent;
use App\Model\AboutUs;
use Illuminate\Http\Request;
use App\Model\ContactUs;

class AppPageContentsController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | APP Page Contents Controller Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles APP Page Contents related apis & features.
    */

    /**
     * Get Terms Of use
     *
     * Get Method
     * @return mixed
     */
    public function getTermsOfUse()
    {
        $data = PageContent::selectRaw('id,name,title,slug,content')
                           ->where('slug', 'terms-of-use')
                        ->first();

        if(!empty($data))
        {
            return $this->toJson(['data' => $data ] , trans('api.termAndUse.found'), 1);
        }

        return $this->toJson([], trans('api.termAndUse.not_found'), 0);
    }

    /**
     * Get privacy policy and terms of use pages
     *
     * Get Method
     * @return mixed
     */
    public function getPrivacyPolicy()
    {
        $data = PageContent::selectRaw('id,name,title,slug,content')
                           ->whereIn('slug', ['privacy-policy','terms-of-use'])
                        ->get();

        if(!empty($data))
        {
            return $this->toJson(['data' => $data], trans('api.privacyANDPolicy.found'), 1);
        }

        return $this->toJson([], trans('api.privacyANDPolicy.not_found'), 0);
    }

    /**
     * Get legal policy
     *
     * Get Method
     * @return mixed
     */
    public function getlegalPolicy()
    {
        $data = PageContent::selectRaw('id,name,title,slug,content')
                           ->where('slug', 'legal-policy')
                        ->first();

        if(!empty($data))
        {
            return $this->toJson(['data' => $data], trans('api.legalPolicy.found'), 1);
        }

        return $this->toJson([], trans('api.legalPolicy.not_found'), 0);
    }

    /**
     * Get cancellation policy
     *
     * Get Method
     * @return mixed
     */
    public function getcancellationPolicy()
    {
        $data = PageContent::selectRaw('id,name,title,slug,content')
                           ->where('slug', 'cancellation-policy')
                        ->first();

        if(!empty($data))
        {
            return $this->toJson(['data' => $data], trans('api.cancellationPolicy.found'), 1);
        }

        return $this->toJson([], trans('api.cancellationPolicy.not_found'), 0);
    }

    /**
     * Get refund policy
     *
     * Get Method
     * @return mixed
     */
    public function getrefundPolicy()
    {
        $data = PageContent::selectRaw('id,name,title,slug,content')
                           ->where('slug', 'refund-policy')
                        ->first();

        if(!empty($data))
        {
            return $this->toJson(['data' => $data], trans('api.refundPolicy.found'), 1);
        }

        return $this->toJson([], trans('api.refundPolicy.not_found'), 0);
    }

    /**
     *  Create contactus 
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function contactUs(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:2|max:100',
            'message' => 'required|min:10|max:500'
        ]);

        $authUser = \Auth::guard('api')->user();

        $contactUs = new ContactUs();
        $contactUs->userId = $authUser->id;
        $contactUs->subject = $request->subject;
        $contactUs->message = $request->message;

        if ($contactUs->save()) {

            return $this->toJson([], trans('api.contactus.sent'), 1);
        }

        return $this->toJson(null, trans('api.contactus.error'), 0);
    }

}
