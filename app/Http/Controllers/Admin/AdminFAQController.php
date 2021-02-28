<?php

namespace App\Http\Controllers\Admin;

use App\Model\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminfaqController extends Controller
{   
     private $validationRules = [
        'question' => 'required',
        'answer' => 'required',
     ];
    /**
     * Display a listing of the faq.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.faqs.faq_list');
    }

    /**
     * Show the form for creating a new faq.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       return view('admin.faqs.faq_create');
    }

    /**
     * Search faq.
     *
     * @param Request $request
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
            $query = Faq::select('*');
            
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('faqs.question', 'like', '%'.$request->search['value'].'%');
            });
            
            $faqs = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $faqs['recordsFiltered'] = $faqs['recordsTotal'] = $faqs['total'];
            
            foreach ($faqs['data'] as $key => $faq) {
                
                $params = [
                    'faq' => $faq['id']
                ];
                
                $faqs['data'][$key]['sr_no'] = $startNo+$key;
                    
                $editRoute = route('faqs.edit', $params);
                $deleteRoute = route('faqs.destroy', $params);
                $statusRoute = route('faqs.status', $params);
                

                $faqs['data'][$key]['answer'] = strlen($faq['answer']) > 100 ? substr($faq['answer'],0,100)."..." : $faq['answer'];
                
                $status = ($faq['isActive']== 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
                
                $faqs['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Edit FAQ Information"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
                $faqs['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="FAQ" title="Delete"><i class="fa fa-trash"></i></a>';
                $faqs['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
                $faqs['data'][$key]['createdAt'] = date('jS F Y', strtotime($faq['createdAt']));
                $faqs['data'][$key]['question'] =  str_limit($faq['question'],100,'...');
          }
            return response()->json($faqs);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $this->validate($request, $this->validationRules);
        $faq = new Faq();
        $faq->fill($request->all());
        $faq->isActive = $request->status;
        
        if($faq->save()) {

            return redirect()->route('faqs.index')->with('success',trans('messages.faqs.create.success'));
        }
        return redirect()->route('faqs.index')->with('error',trans('messages.faqs.create.error'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param faq $faq 
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {    
          return view('admin.faqs.faq_create', [
            'faq'=> $faq,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  faq $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Faq $faq)
    {
        $this->validate($request, $this->validationRules);
       
        $faq->fill($request->all());
        $faq->isActive = $request->status;

        if($faq->save())
        {
            return redirect()->route('faqs.index')->with('success',trans('messages.faqs.update.success'));
        }
        return redirect()->route('faqs.index')->with('error',trans('messages.faqs.update.error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param faq $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        if($faq->delete())
        {
            return redirect(route('faqs.index'))->with('success', trans('messages.faqs.delete.success'));
        }
        return redirect(route('faqs.index'))->with('error', trans('messages.faqs.delete.error'));
    }

    /**
     * Change status of the faq.
     *
     * @param  faq $faq
     * @return Redirect
     */
    public function changeStatus(Faq $faq)
    {   
        $faq->isActive = !$faq->isActive;
        if($faq->save())
        {
            $status = $faq->isActive ? 'active' : 'inactive';
            return redirect(route('faqs.index'))->with('success', trans('messages.faqs.status.success', ['status' => $status]));
        }

        return redirect(route('faqs.index'))->with('error', trans('messages.faqs.status.error'));
    }

}