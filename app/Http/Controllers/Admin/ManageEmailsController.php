<?php

namespace App\Http\Controllers\Admin;
use App\Models\Users;
use App\Models\Leads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\StockModels;
use App\Models\UsefulLink;
use App\Models\Newsletter;
use App\Mail\MailNotifyAdmin;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Validator;
class ManageEmailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Leads::paginate(20);
        return view('admin.manageEmails',['data'=>$res]);
    }
    public function fetchData(Request $request)
    {
       $search_data = $request->get('search');
        $output="";
        if($request->ajax())
        {
         $data = Leads::Where('id', 'like', '%' . $request->get('search') . '%')
         ->orWhere('email', 'like', '%' . $request->get('search') . '%')->get();
        
         if($data)
         {
            foreach ($data as $product) {
            $output.='<tr>'.
            '<td>'.$product->id.'</td>'.
            '<td>'.$product->email.'</td>'.
            '<td>'.$product->DoNotSend.'<a href="/admin/donotsend/on/'.$product->id.'"><i class="fa fa-toggle-off" aria-hidden="true"></i></a></td>'.
            '<td>'.$product->Unsubscribe.'</td>'.
            '<td>'.date("d-M-y", strtotime($product->created_at)).'</td>'.
            '</tr>';
            }
        }         
         return response()->json($output);
        }
    }
   
    public function donotsendOn($id)
    {
        if ($id) {
            try{
                Leads::where('id',$id)->update(array('DoNotSend' => 1,'Unsubscribe'=> 1));
                return redirect()->back();

                // return response()->json([
                //     'status' => 'OK',
                //     'message' => 'Done',
                // ]);
            }
            catch (\Exception $e) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Failed!!',
                    'data' => $e->getMessage()
                ]);
            }
        } else {
            # code...
        }
        
           
    }
    public function donotsendOff($id)
    {
        if ($id) {
            try{
                Leads::where('id',$id)->update(array('DoNotSend' => 0,'Unsubscribe'=> 0));
                return redirect()->back();
                // return response()->json([
                //     'status' => 'OK',
                //     'message' => 'Done',
                // ]);
            }
            catch (\Exception $e) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Failed!!',
                    'data' => $e->getMessage()
                ]);
            }
        } else {
            # code...
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          // $return_data = [];
          $posted_data = request()->all();

          $validator = Validator::make($posted_data, array(
              'email' => 'required|email'
          ), array(
              'email.required' => 'Enter a valid email address.'
          ));
      
          if ($validator->fails()) {
              return response()->json([
                  'status' => 'ERROR',
                  'message' => 'Please provide valid email address',
                  'errors' => $validator->errors()
              ]);
          }
          $email = $posted_data['email'];
          $name = $posted_data['name'];
          $now = Carbon::now()->toDateTimeString();
          try {
              $email_data = new Users();
              $email_data->email = $email;
              $email_data->name = $name;
              $email_data->password = '';
              $email_data->created_at = Carbon::now();
              $email_data->save();
              return response()->json([
                  'status' => 'OK',
                  'message' => 'Email Saved Successfully',
                  'redirect_url' => url('/manageEmails')
              ]);
              } 
          catch (\Exception $e){
              return response()->json([
                  'status' => 'ERROR',
                  'message' => 'Failed!!',
                  'data' => $e->getMessage()
              ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // user_data = [];
        $user_emails =  Leads::where('id', $id)->get();

        return view('admin.edit_email', ['user_emails' => $user_emails]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $posted_data = request()->all();
        $validator = Validator::make($posted_data, array(
            'email' => 'required|email'
        ), array(
            'email.required' => 'Please provide valid email address'
        ));
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please provide valid email address!!',
                'errors' => $validator->errors()
            ]);
        }

        $email = $posted_data['email'];
       
        try{
            Leads::where('id', $id)
            ->update(['email' =>$email]);
            return response()->json([
                'status' => 'OK',
                'message' => 'Email Update Successfully',
                'redirect_url' => url('/manageEmails')
            ]);
        } 
        catch (\Exception $e){
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
   
  
}
