<?php

namespace App\Http\Controllers\Admin;

use App\Model\ContactUs;
use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminInquiryController extends Controller
{
    private $validationRules = [
        'message' => 'required'
     ];
    /**
     * Display a listing of the inquiry message.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.inquiry.inquiry_list');
    }

    /**
     * Search inquiry.
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
            $query = ContactUs::selectRaw('contact_us.id,contact_us.userId,contact_us.isReply,contact_us.subject,contact_us.message,contact_us.createdAt,users.name,users.email,users.mobileNumber')
                                    ->leftJoin('users','users.id','contact_us.userId');
            
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('contact_us.subject', 'like', '%'.$request->search['value'].'%');
            });
            
            $inquiry = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $inquiry['recordsFiltered'] = $inquiry['recordsTotal'] = $inquiry['total'];

            foreach ($inquiry['data'] as $key => $inq) {
                
                $params = [
                    'inquiry' => $inq['id']
                ];
                
                $inquiry['data'][$key]['sr_no'] = $startNo+$key;
                $editRoute = route('inquiry.edit', $params);
                $deleteRoute = route('inquiry.destroy', $params);
                $statusRoute = ($inq['isReply']== 1) ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
                $inquiry['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Inquiry reply"><i class="fas fa-paper-plane"></i></a>&nbsp&nbsp';
                $inquiry['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Inquiry" title="Delete"><i class="fa fa-trash"></i></a>';
                $inquiry['data'][$key]['isReply'] = '<p href="javascript:void(0);"  class="btnChangeUserStatus">'.$statusRoute.'</p>';
                $inquiry['data'][$key]['createdAt'] = date('jS F Y', strtotime($inq['createdAt']));
          }
            return response()->json($inquiry);
        }
    }
    
    /**
     * Show the form for inquery reply 
     *
     * @param ContactUs $inquiry 
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUs $inquiry)
    {    
        $userdata = User::selectRaw('email')->where('id',$inquiry->userId)->first();
          return view('admin.inquiry.inquiry_create', [
            'inquiry'=> $inquiry,
            'userdata' =>$userdata
        ]);
    }

    /**
     * Reply inquiry mail
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ContactUs $inquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,ContactUs $inquiry)
    {
        $this->validate($request, $this->validationRules);
        $inquiry->reply_message = $request->message;
        $inquiry->isReply = 1;

        if($inquiry->save())
        {
           $cmd = 'cd ' . base_path() . ' && php artisan mail:sendinuiryreply "' . $inquiry->userId . '" "'. $request->id . '"';
exec($cmd . ' > /dev/null &');

            return redirect()->route('inquiry.index')->with('success',trans('messages.inquiry.update.success'));
        }
        return redirect()->route('inquiry.index')->with('error',trans('messages.inquiry.update.error'));
    }

    /**
     * Remove inquiry
     *
     * @param ContactUs $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUs $inquiry)
    {
        if($inquiry->delete())
        {
            return redirect(route('inquiry.index'))->with('success', trans('messages.inquiry.delete.success'));
        }
        return redirect(route('inquiry.index'))->with('error', trans('messages.inquiry.delete.error'));
    }
}
