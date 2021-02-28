<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PageContent;
use App\Model\User;

class PageController extends Controller
{
    public function getPrivacypolicy(){ 
        $pageContent = PageContent::where('slug','Privacy-policy')->first();
        return view('page/content_page',['pageContent'=> $pageContent]);
    }

    public function getTermsAndCondition(){  
        $pageContent = PageContent::where('slug','terms-of-use')->first();
        return view('page/content_page',['pageContent'=> $pageContent]);
    }

    public function getCancelPolicy(){ 
        $pageContent = PageContent::where('slug','cancellation-policy')->first();
        return view('page/content_page',['pageContent'=> $pageContent]);
    }

    public function getRefundPolicy(){  
        $pageContent = PageContent::where('slug','refund-policy')->first();
        return view('page/content_page',['pageContent'=> $pageContent]);
    }

    public function updateTermAndCondition(Request $request)
    {
        User::where('id',$request->userId)->update(['termsAndConditions'=> 1]);
        return redirect()->route('userDashboard')->with('success',trans('messages.users.update.success'));
    }
}
