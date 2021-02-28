<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Model\PageContent;
use Illuminate\Pagination\Paginator;
use Validator;

class AdminPageContentController extends Controller
{   
      /**
     * Display a listing of the page content
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.page_contents.page_content_list');
    }

    /**
     * Get list of page content
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

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"','',$request->columns[$orderColumnId]['name']);

            $pagecontents=PageContent::select('*');

            $pagecontents=$pagecontents->where(function ($query) use ($request){
                $query->orWhere('name', 'like', '%'.$request->search['value'].'%');
            });

            $pagecontents = $pagecontents->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $pagecontents['recordsFiltered'] = $pagecontents['recordsTotal'] = $pagecontents['total'];

            foreach ($pagecontents['data'] as $key=> $pagecontent){

                $params = [
                    'pageSlug' => $pagecontent['slug']
                ];
                $editRoute = route('page_contents.edit', $params);

                $pagecontents['data'][$key]['sr_no'] = $startNo+$key;
                $pagecontents['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Edit Page-Content Information"><i class="fas fa-pencil-alt"></i></a>';
                
            }
            return response()->json($pagecontents);
        }

    }

    /*
     * Show page content edit page
     * @param $pageSlug
     * 
     */
    public function edit($pageSlug)
    {
        $pageContent = PageContent::where('slug', $pageSlug)->first();
        //dd($pageContent);
        if(!empty($pageContent))
        {
            return view('admin.page_contents.page_content_edit', [
                'pageContent' => $pageContent
            ]);
        }
        return abort(404);
    }
    
    /**
     * 
     * Update page content feature
     * 
     * @param Request $request
     * @param PageContent $pageContent
     * @return 
     */
    public function update(Request $request, PageContent $pageContent)
    {

        $validatedData = $request->validate([
            'content' => 'required',
        ]);

        $pageCntent = PageContent::where('id', $pageContent->id)->update(['title' => $request->title, 'content' => $request->content]);

        if($pageCntent) 
        {
            return redirect(route('page_contents.index'))->with('success', trans('messages.page_content.update.success'));
        }
            
        return redirect(route('page_contents.index'))->with('error', trans('messages.page_content.update.error'));
    }
}
