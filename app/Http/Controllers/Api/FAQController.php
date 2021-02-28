<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | FAQController Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles FAQ related apis & features.
    */

    /**
     * FAQ list
     *
     * @param Request null
     *
     * @return Response Json
     *
     */
    public function getFAQs()
    {
        $faqsList = Faq::selectRaw('id,question,title,answer')->where('isActive',1)->get();
        
        if($faqsList->isNotEmpty())
        {
            return $this->toJson(['faqsList' => $faqsList], trans('api.faq.faq_found'), 1);
        }

        return $this->toJson([], trans('api.faq.faq_not_found'), 0);
    }

}
