<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockModels;
use App\Models\UsefulLink;
use App\Models\Newsletter;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Validator;
// admin controller*******************************************
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tadata = DB::select('select * from tadata');
        
        return view('admin.admin', ['tadata' => $tadata]);
    }

    // for del ta data
    public function destroy($id)
    {
        DB::delete('delete from tadata where id = ?', [$id]);

        return redirect()->back() ->with('alert', 'Record deleted successfully.');

    }


    // for upadate
    public function show($id)
    {
        
        $tadata = DB::select('select * from tadata where id = ?', [$id]);
        return view('admin.update_tadata', ['tadata' => $tadata]);
    }
    public function edit(Request $request, $id)
    {
        
        $posted_data = request()->all();
        $Ticker = $posted_data['ticker'];
        $CompanyName = $posted_data['CompanyName'];
        $Listed_at = $posted_data['Listed_at']; 
        $OnDate = $posted_data['OnDate']; 
        $Currency = $posted_data['Currency']; 
        $AtPrice = $posted_data['AtPrice']; 
        $CurrentPrice = $posted_data['CurrentPrice']; 
        $SL_Exit = $posted_data['SL_Exit']; 
        $TargetPrice = $posted_data['TargetPrice']; 
        $HoldingPeriod = $posted_data['HoldingPeriod']; 
        $Gain_Loss = $posted_data['Gain_Loss']; 
        $Action = $posted_data['Action']; 
        if($Action=='Exit'){
            $Gain_Loss ='realised';
            $glprice=$SL_Exit-$AtPrice;
        }
        else{
            $Gain_Loss ='Unrealised';
            $glprice=($CurrentPrice-$AtPrice)*1000/$AtPrice;
        }
        $glpercent=$glprice/1000/$AtPrice;
        // dd($Action);
        //$data=array('first_name'=>$first_name,"last_name"=>$last_name,"city_name"=>$city_name,"email"=>$email);
        //DB::table('student')->update($data);
        // DB::table('student')->whereIn('id', $id)->update($request->all());
        DB::table('tadata')
            ->where('id', $id)
            ->update(['Ticker' => $Ticker,
            'CompanyName' => $CompanyName,
            'Listed_at' => $Listed_at,
            'OnDate' => $OnDate,
            'Currency' => $Currency,
            'AtPrice' => $AtPrice,
            'SL_Exit' => $SL_Exit,
            'TargetPrice' => $TargetPrice,
            'HoldingPeriod' => $HoldingPeriod,
            'Gain_Loss' => $Gain_Loss,
            'Action' => $Action,
            'glprice' => $glprice,
            'glpercent' => $glpercent
            ]);
     
        return redirect()->route('admin') ->with('alert', 'Record updated successfully.');
    }
    // end for update



    // for hide
    public function hide($id)
    {
        
        $status="unhide";
        DB::table('tadata')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('status' => $status));  // update the record in the DB. 
    
        return redirect()->back() ->with('alert', 'Unhide Successfully');
    }
// for unhide
    public function unhide($id)
    {
        $status="hide";
        DB::table('tadata')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('status' => $status));  // update the record in the DB. 
        
        // DB::update('update tadata set status = ? where = ?', [$status, $id]);
        return redirect()->back() ->with('alert', 'hide Successfully');
       
    }
    // for save tadata
    public function save(Request $request)

    {
        $return_data = [];
        $posted_data = request()->all();

        $validator = Validator::make($posted_data, array(
            'ticker' => 'required|min:3',
            'CompanyName' => 'required'
            // 'professional' => 'required',
            // 'mobile' => 'required',
            // 'city' => 'required'

        ), array(
            'ticker.required' => 'Enter your ticker.',
            'ticker.min' => 'Enter at least 3 characters.',
            // 'professional.required' => 'Choose your professional',
            'CompanyName.required' => 'Enter your Company Name.'
            // 'mobile.required' => 'Enter your mobile number.',
            // 'city' => 'Enter your city.'
        ));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
        }

        // return $posted_data;
        // exit;

        $ticker = $posted_data['ticker'];
        $CompanyName = $posted_data['CompanyName'];
        $Listed_at = $posted_data['Listed_at'];
        $OnDate = $posted_data['OnDate'];
        $Currency = $posted_data['Currency'];
        $AtPrice = $posted_data['AtPrice'];
        $CurrentPrice = $posted_data['CurrentPrice'];
        $SL_Exit = $posted_data['SL_Exit'];
        $TargetPrice = $posted_data['TargetPrice'];
        $HoldingPeriod = $posted_data['HoldingPeriod'];
        $Gain_Loss = $posted_data['Gain_Loss'];
        $Action = $posted_data['Action'];
        $status = 'unhide';

        if($Action=='Exit'){
            $Gain_Loss ='realised';
            $glprice=$SL_Exit-$AtPrice;
        }
        else{
            $Gain_Loss ='Unrealised';
            $glprice=($CurrentPrice-$AtPrice)*1000/$AtPrice;
        }
        $glpercent=$glprice/1000/$AtPrice;

        $now = Carbon::now()->toDateTimeString();


        try {
            $tadata = new StockModels();
            // $lead->lead_id = 111;
            $tadata->ticker = $ticker;
            $tadata->CompanyName = $CompanyName;
            $tadata->Listed_at = $Listed_at;
            $tadata->OnDate = $OnDate;
            $tadata->Currency = $Currency;
            $tadata->AtPrice = $AtPrice;
            $tadata->CurrentPrice = $CurrentPrice;
            $tadata->SL_Exit = $SL_Exit;
            $tadata->TargetPrice = $TargetPrice;
            $tadata->HoldingPeriod = $HoldingPeriod;
            $tadata->Gain_Loss = $Gain_Loss;
            $tadata->glprice = $glprice;
            $tadata->glpercent = $glpercent;

            $tadata->Action = $Action;
            $tadata->status = $status;

            // $lead->alt_mobile_no = $mobile;
            // $lead->status = 'new';
            // $lead->sub_status = '';
            // $lead->src = $lead_source;
            // $lead->src_param = 'worksheet';
            // $lead->is_active = 1;
            // $lead->is_new = 0;
            // $lead->info = $city;


            $tadata->created_at = Carbon::now();

            $tadata->save();

            return response()->json([
                'status' => 'OK',
                'message' => 'Saved Successfully',
                'redirect_url' => url('/admin')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
    }
// useful controller
    public function useful_links(){

        $useful_link = DB::select('select * from useful_links');
        return view('admin.useful_links', ['useful_link' => $useful_link]);
        // return view('admin.useful_links');
    }
    
    public function savelinks(Request $request)
    {
        $return_data = [];
        $posted_data = request()->all();

        $validator = Validator::make($posted_data, array(
            'Title' => 'required|min:3',
            'url' => 'required'
           

        ), array(
            'Title.required' => 'Enter your URL Title.',
            'Title.min' => 'Enter at least 3 characters.',
            'url.required' => 'Enter URL.'
          
        ));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
        }

        $Title = $posted_data['Title'];
        $url = $posted_data['url'];
        $sequence = $posted_data['sequence'];
        $description = $posted_data['description'];
        $status = 'Show';
        $now = Carbon::now()->toDateTimeString();


        try {
            $tadata = new UsefulLink();
            $tadata->title = $Title;
            $tadata->url = $url;
            $tadata->description = $description;
            $tadata->sequence = $sequence;
            $tadata->Visible = $status;
            $tadata->created_at = Carbon::now();
            $tadata->save();
            return response()->json([
                'status' => 'OK',
                'message' => 'Saved Successfully',
                'redirect_url' => url('/admin/useful_links')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
    }
    public function hidelinks($id){
        $status="hide";
        DB::table('useful_links')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
    
        return redirect()->back() ->with('alert', 'Hide Successfully');
    }
    public function showlinks($id){
        $status="Show";
        DB::table('useful_links')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
    
        return redirect()->back() ->with('alert', 'Link visible Successfully');
    }
    public function destroylinks($id)
    {
        DB::delete('delete from useful_links where id = ?', [$id]);

        return redirect()->back() ->with('alert', 'Record deleted successfully.');

    }
    // end useful links

    // start newsletter
     public function newsletter(){

        $newsletter = DB::select('select * from newsletter');
        return view('admin.newsletter', ['newsletter' => $newsletter]);
        // return view('admin.newsletter');
    }
    
    public function savenewsletter(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
  
        $fileName = time().'.'.$request->file->extension();  
   
        $request->file->move(public_path('uploads'), $fileName);
        $posted_data = request()->all();

        $validator = Validator::make($posted_data, array(
            'Title' => 'required|min:3',
        ), array(
            'Title.required' => 'Enter your URL Title.',
            'Title.min' => 'Enter at least 3 characters.',
          
        ));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
        }

        $Title = $posted_data['Title'];
        $filesource = $fileName;
        $description = $posted_data['description'];
        $sequence = $posted_data['sequence'];
        $status = 'Show';
        $now = Carbon::now()->toDateTimeString();


        try {
            $tadata = new Newsletter();
            $tadata->title = $Title;
            $tadata->filesource = $filesource;
            $tadata->description = $description;
            $tadata->sequence = $sequence;
            $tadata->Visible = $status;
            $tadata->created_at = Carbon::now();
            $tadata->save();
            return back()
            ->with('success','You have successfully upload file.')
            ->with('file',$fileName);
            // return response()->json([
            //     'status' => 'OK',
            //     'message' => 'Saved Successfully',
            //     'redirect_url' => url('/admin/useful_links')
            // ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
        
       
    }
    public function hidenewsletter($id){
        $status="hide";
        DB::table('newsletter')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
        return back()
        ->with('success','Hide successfully file.');
        // return redirect()->back() ->with('alert', 'Hide Successfully');
    }
    public function shownewsletter($id){
        $status="Show";
        DB::table('newsletter')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
        return back()
        ->with('success','File visible Successfully');
        
    }
    public function destroynews($id)
    {
        DB::delete('delete from newsletter where id = ?', [$id]);

        return redirect()->back() ->with('alert', 'Record deleted successfully.');

    }
    // end newsletter
}