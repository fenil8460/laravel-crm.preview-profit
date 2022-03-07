<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Models\StockModels;
use App\Models\UsefulLink;
use App\Models\Newsletter;
use App\Models\Leads;
use App\Models\Users;
use App\Mail\MailNotifyAdmin;
use App\Mail\TickerMail;
use App\Mail\UpdateRecomMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
    public function index(){
        /******************************
     ********* using pagination to show data *****************
     ***********************************/
        $tadata = StockModels::all();     
        return view('admin.admin',compact('tadata'));
    }

    // for del ta data technical data
    public function destroy($id){
        /******************************
     ********* delete technical data function *****************
     ***********************************/
        DB::delete('delete from tadata where id = ?', [$id]);
        return redirect()->back();
    }


    // for upadate technical data 
    public function show($id){
        /******************************
     *********showing existing technical data *****************
     ***********************************/
        $tadata = DB::select('select * from tadata where id = ?', [$id]);
        return view('admin.update_tadata', ['tadata' => $tadata]);
    }
    public function edit(Request $request, $id){
        /******************************
     ********* edit technical data *****************
     ***********************************/
    $tadata = DB::select('select * from tadata where id = ?', [$id]);
    $sl = $tadata[0]->SL_Exit;
    $action=$tadata[0]->Action;
        $posted_data = request()->all();
        $validator = Validator::make($posted_data, array(
            'ticker' => 'required|min:1',
            'CompanyName' => 'required'
        ), array(
            'ticker.required' => 'Enter your ticker.',
            'ticker.min' => 'Enter at least 1 characters.',
            
            'CompanyName.required' => 'Enter your Company Name.'
          
        ));
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please provide full data',
                'errors' => $validator->errors()
            ]);
        }

        $Ticker = strtoupper($posted_data['ticker']);
        $CompanyName = strtoupper($posted_data['CompanyName']);
        $Listed_at = $posted_data['Listed_at']; 
        $OnDate = $posted_data['OnDate']; 
        $Currency = strtoupper($posted_data['Currency']); 
        $AtPrice = $posted_data['AtPrice']; 
        $CurrentPrice = $posted_data['CurrentPrice']; 
        $SL_Exit = $posted_data['SL_Exit']; 
        $TargetPrice = $posted_data['TargetPrice']; 
        $HoldingPeriod = $posted_data['HoldingPeriod']; 
        $Gain_Loss = $posted_data['Gain_Loss']; 
        $Action = strtoupper($posted_data['Action']); 
        $exit_update_date = $posted_data['exit_date'];
        if ($Action == 'EXIT') {
            $Gain_Loss = 'realised';
            // $glprice = $SL_Exit - $AtPrice;
            $numofshares = 1000 / $AtPrice;
            $CurrentValue = $numofshares * $SL_Exit;
            $glprice = $CurrentValue - 1000;    
            // dd($AtPrice,$SL_Exit,$numofshares,$CurrentValue,$glprice);
            // (this is to be displayed to the user in Gain/Loss $)
        } else {
            $Gain_Loss = 'Unrealised';
            // $glprice = ($CurrentPrice - $AtPrice) * 1000 / $AtPrice;
            // $glprice = ($CurrentPrice - $AtPrice);
            $numofshares = 1000 / $AtPrice;
            $CurrentValue = $numofshares * $CurrentPrice;
            $glprice = $CurrentValue - 1000;         // (this is to be displayed to the user in Gain/Loss $)
  
        }
        try{
            if(strtoupper($Action) == "BUY"){
                $exit_date = null;
            }else{
                $exit_date = $exit_update_date;
            }
        DB::table('tadata')
            ->where('id', $id)
            ->update(['Ticker' =>$Ticker,
            'CompanyName' => $CompanyName,
            'Listed_at' => strtoupper($Listed_at),
            'OnDate' => $OnDate,
            'Currency' => $Currency,
            'AtPrice' => $AtPrice,
            'CurrentPrice'=>$CurrentPrice,
            'SL_Exit' => $SL_Exit,
            'TargetPrice' => $TargetPrice,
            'HoldingPeriod' => $HoldingPeriod,
            'Gain_Loss' => $Gain_Loss,
            'Action' => strtoupper($Action),
            'glprice' => $glprice,
            'exit_date'=>$exit_date,
            ]);
            // if($sl != $SL_Exit || ($action != $Action && $Action == "EXIT")){
            //     $user_email = Leads::where('Unsubscribe','=',0)
            //     ->get();
            //     $data = [
            //         'companyname' => $CompanyName,
            //         'subject'=> " Recommendation has been update for :".$Ticker
            //     ];
            //     $update_ticker = [];
            //     foreach($user_email as $u_email){
            //         array_push($update_ticker,$u_email->email);
            //     }
            //     Mail::to(env('MAIL_USERNAME'))->bcc($update_ticker)->send(new UpdateRecomMail($data));
            // }
            return response()->json([
                'status' => 'OK',
                'message' => 'Update Successfully',
                'companyname' => $CompanyName." recommendation was Updated to Profit180.com",
                'subject'=>  " Recommendation has been update for :".$Ticker,
                'to'=> env('MAIL_USERNAME'),
                'redirect_url' => url('/'),
                'ticker'=>$Ticker
            ]);
        } 
        catch (\Exception $e){
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
        //  dd('sd');
        // return redirect()->route('admin') ->with('alert', 'Record updated successfully.');
    }
    // end for update technical data

    // for hide technical data
    public function hide($id){
        /******************************
     ********* to hide technical data *****************
     ***********************************/
        
        $status="unhide";
        DB::table('tadata')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('status' => $status));  // update the record in the DB. 
    
        return redirect()->back();
    }
    // for unhide technical data
    public function unhide($id){
        $status="hide";
        DB::table('tadata')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('status' => $status));  // update the record in the DB. 
        
        // DB::update('update tadata set status = ? where = ?', [$status, $id]);
        return redirect()->back();
       
    }
    // for save technical data
    public function save(Request $request)
    {
        
        // $return_data = [];
        $posted_data = request()->all();

        $validator = Validator::make($posted_data, array(
            'ticker' => 'required|min:1',
            'CompanyName' => 'required'
           

        ), array(
            'ticker.required' => 'Enter your ticker.',
            'ticker.min' => 'Enter at least 1 characters.',
            
            'CompanyName.required' => 'Enter your Company Name.'
          
        ));
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please provide full data',
                'errors' => $validator->errors()
            ]);
        }


        $ticker = $posted_data['ticker'];
        $CompanyName = $posted_data['CompanyName'];
        $Listed_at = $posted_data['Listed_at'];
        $OnDate = $posted_data['OnDate'];
        $Currency = strtoupper($posted_data['Currency']);
        $AtPrice = $posted_data['AtPrice'];
        $CurrentPrice = $posted_data['CurrentPrice'];
        $SL_Exit = $posted_data['SL_Exit'];
        $TargetPrice = $posted_data['TargetPrice'];
        $HoldingPeriod = $posted_data['HoldingPeriod'];
        $Gain_Loss = $posted_data['Gain_Loss'];
        $Action = $posted_data['Action'];
        $exit_date = $posted_data['exit_date'];
        $status = 'unhide';
       
        
        if($Action=='Exit'){
            $Gain_Loss ='realised';
             }
             else{
            $Gain_Loss ='Unrealised';
            }
        $now = Carbon::now()->toDateTimeString();


        try {
            $tadata = new StockModels();
            // $lead->lead_id = 111;
            $tadata->ticker = strtoupper($ticker);
            $tadata->CompanyName = strtoupper($CompanyName);
            $tadata->Listed_at = strtoupper($Listed_at);
            $tadata->OnDate = $OnDate;
            $tadata->Currency = strtoupper($Currency);
            $tadata->AtPrice = $AtPrice;
            $tadata->previous_close = 0;
            $tadata->CurrentPrice = $CurrentPrice;
            $tadata->SL_Exit = $SL_Exit;
            $tadata->TargetPrice = $TargetPrice;
            $tadata->HoldingPeriod = $HoldingPeriod;
            $tadata->Gain_Loss = $Gain_Loss;
            $tadata->glprice = 0;
            $tadata->glpercent = 0;
            $tadata->Action = strtoupper($Action);
            $tadata->status = $status;
            if(strtoupper($Action) == "BUY"){
                $tadata->exit_date = null;
            }else{
                $tadata->exit_date = $exit_date;
            }
            $tadata->created_at = Carbon::now();
            $tadata->save();
            // $user_email = Leads::where('Unsubscribe','=',0)
            // ->get();
            // $data = [
            //     'companyname' => $tadata->CompanyName,
            //     'subject'=> " New recommendation has been added for :".$tadata->ticker
            // ];
            // $a = [];
            // foreach($user_email as $u_email){
            //     array_push($a,$u_email->email);
            // }
            //  Mail::to(env('MAIL_USERNAME'))->bcc($a)->send(new TickerMail($data));
            return response()->json([
                'status' => 'OK',
                'message' => 'Saved Successfully',
                'redirect_url' => url('/'),
                'companyname' => $tadata->CompanyName." recommendation was added to Profit180.com",
                'subject'=>  " New recommendation has been added for :".$tadata->ticker,
                'to'=> env('MAIL_USERNAME'),
                'ticker'=>strtoupper($ticker)
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
    public function mail_send(Request $request){
       $all_data=request()->all();
    $to = $all_data['emial_to'];
    $bcc = $all_data['email_bcc'];
    $subject = $all_data['subject'];
    $companyName = $all_data['email_body'];
    $ticker = $all_data['email_ticker_name'];
    $ticker_id = StockModels::select('id')->where('Ticker',$ticker)->get();
    $get_ticker_id = isset($ticker_id[0]->id) ? $ticker_id[0]->id : null;
    $email_save = new EmailTemplate();
    $email_save->tiker_id = $get_ticker_id;
    $email_save->sender = $to;
    $email_save->bcc = $bcc;
    $email_save->subject = $subject;
    $email_save->body = $companyName;
    $email_save->status = 1;
    $email_save->updated_at = Carbon::now();
    $email_save->save();

    $bcc = explode(',', $bcc);
    $data = [
                'companyname' => $companyName,
                'subject'=> $subject,
                'sender'=>''
            ];
        foreach ($bcc as $email) {
            $data['sender']=$email;
            Mail::to($to)->bcc($email)->send(new TickerMail($data));
        }
             return response()->json([
                'status' => 'OK',
                'message' => 'Send Successfully',
                'redirect_url' => url('/'),
            ]);
            
    }



    // useful controller
    public function useful_links(){

        // $useful_link = DB::select('select * from useful_links');
        $useful_link = UsefulLink::paginate(20);
        return view('admin.useful_links',compact('useful_link'));
        // return view('admin.useful_links');
    }
    
    public function savelinks(Request $request){
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
                'redirect_url' => url('/useful_links')
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
        return redirect()->back();
        // return redirect()->back() ->with('alert', 'Hide Successfully');
    }
    public function showlinks($id){
        $status="Show";
        DB::table('useful_links')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
    
        return redirect()->back();
    }
    public function destroylinks($id){
        DB::delete('delete from useful_links where id = ?', [$id]);

        return redirect()->back();

    }
     // for upadate useful_link data 
     public function edit_popup_link($id)
     {
        /******************************
         *********showing existing useful_link data *****************
        ***********************************/
        
        $useful_links = DB::select('select * from useful_links where id = ?', [$id]);
        return view('admin.update_links', ['useful_links' => $useful_links]);
    }
    public function edit_link(Request $request, $id){
        /******************************
        ********* edit useful_link data *****************
         ***********************************/
        $posted_data = request()->all();
        $title = $posted_data['title'];
        $url = $posted_data['url'];
        $description = $posted_data['description']; 
        $sequence = $posted_data['sequence']; 
      
        DB::table('useful_links')
            ->where('id', $id)
            ->update(['title' => $title,
            'url' => $url,
            'description' => $description,
            'sequence' => $sequence
            ]);
     
        return redirect('/useful_links')->with('alert', 'Record updated successfully.');
    }
    // end for update useful_links data
    // end useful links


    // start newsletter
    public function newsletter(){

        // $newsletter = DB::select('select * from newsletter');
        $newsletter = Newsletter::paginate(20);
        return view('admin.newsletter', compact('newsletter'));
        // return view('admin.newsletter');
    }
    
    public function savenewsletter(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv,jpg,jpeg,docx,xls,xlsx|max:2048',
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
            //     'redirect_url' => url('/useful_links')
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
        return back();
        // return redirect()->back() ->with('alert', 'Hide Successfully');
    }
    public function shownewsletter($id){
        $status="Show";
        DB::table('newsletter')
        ->where('id', $id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('Visible' => $status));  // update the record in the DB. 
        return back();
        
    }
    public function destroynews($id){
        DB::delete('delete from newsletter where id = ?', [$id]);

        return redirect()->back();

    }

        // for upadate newsletter data 
        public function edit_popup_news($id){
            /******************************
             *********showing existing newsletter data *****************
            ***********************************/
            
            $newsletter = DB::select('select * from newsletter where id = ?', [$id]);
            return view('admin.update_news', ['newsletter' => $newsletter]);
        }
        public function edit_news(Request $request, $id){
            /******************************
            ********* edit newsletter data *****************
             ***********************************/
            
            if($request->file==NULL){
               $fileName = DB::table('newsletter')
                ->where('id', $id)->value('filesource');
            }else{
                $fileName = time().'.'.$request->file->extension();  
                $request->file->move(public_path('uploads'), $fileName);
            }
           
            $posted_data = request()->all();
            $title = $posted_data['title'];
            $filesource = $fileName;
            $description = $posted_data['description']; 
            $sequence = $posted_data['sequence']; 
          
            DB::table('newsletter')
                ->where('id', $id)
                ->update(['title' => $title,
                'filesource' => $filesource,
                'description' => $description,
                'sequence' => $sequence
                ]);
         
            return redirect('/newsletter')->with('alert', 'Record updated successfully.');
        }
        // end for update newsletetr data
    // end newsletter



    
    public function create_email(Request $request,$id)
    {
        $data = [];
        $data_name = DB::table('tadata')->where('id',$id)->select('*')->get();
        // $json = json_decode($data, true);

        // foreach ($json as $key => $value) {
        //     $Ticker = $value['Ticker'];
        //     $Company = $value['Company'];
        // }

        $data['id']=$id;
        $data['data_name']=$data_name;
        // storing order id in session
        session(['tiker_id'=>$id]);
        // dd($data);
        return view('admin.email_template',$data);
    }

    public function adminSendEmail(Request $request,$id)
    {
        $posted_data = request()->all();
        // dd($posted_data);
        $validator = Validator::make($posted_data, array(
            'sender' => 'Email|required',
            'body' => 'required'
        ), array(
            'sender.required' => 'Enter valid Email Address.',
            'body.required' => 'Give some message.'
          
        ));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
            
        }
        $bcc = '';
        $subject = '';
        $body = '';
        $sender = $posted_data['sender'];
        if ($posted_data['bcc']) {
            $bcc = $posted_data['bcc'];
            
        }
        if ($posted_data['subject']) {
            $subject = $posted_data['subject'] ; 
        }
        if ($posted_data['body']) {
            $body = $posted_data['body'] ; 
        }
        // adding multiple bcc
        $bcc = explode(',', $bcc);
        
        $new_bcc = array();
        foreach ($bcc as $email) {
            $new_bcc[] = $email;
        }
        $bcc =  implode(', ', $new_bcc);
        $data=[
            'sender'=>$sender,
            'bcc' => $bcc,
            'subject' => $subject,
            'body' => $body
                ];
     
        $tiker_id = $request->session()->get('tiker_id');
        if(!is_null($tiker_id))
        {        
            try 
            {
                
                $email_save = new EmailTemplate();
                $email_save->tiker_id = $tiker_id;
                $email_save->sender = $sender;
                $email_save->bcc = $bcc;
                $email_save->subject = $subject;
                $email_save->body = $body;
                $email_save->status = 1;
                $email_save->updated_at = Carbon::now();
                $email_save->save();
                $sender_email = $email_save->sender;
                if ($bcc != '') {
                Mail::to($sender)->bcc($new_bcc)->send(new MailNotifyAdmin($data));
            }else{
                Mail::to($sender_email)->send(new MailNotifyAdmin($data));
            }
            
            // dd($email_save);
            return response()->json([
                'status' => 'OK',
                'message' => 'Send Successfully',
                'redirect_url' => url('/')
            ]);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
        $request->session()->forget('tiker_id');
        }
    }
    public function email_bcc_loading()
    {      
            try 
            {
                $bcc_emails = [];
                $res = Leads::where('DoNotSend',0)
                ->select('email')
                ->where('Unsubscribe',0)
                ->groupBy('email')
                ->get();
               foreach ($res as $value) {
                   array_push($bcc_emails,$value['email']);
               }
                
            // dd($bcc_emails);
            return response()->json([
                'status' => 'OKBCC',
                'message' => $bcc_emails,
            ]);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed!!',
                'data' => $e->getMessage()
            ]);
        }
        
    }
    //add data autocomplete
    function fetch(Request $request)
    {
     if($request->get('query'))
     {
      $query = $request->get('query');
      $client = new \GuzzleHttp\Client();
      $request = $client->get('https://financialmodelingprep.com/api/v3/search?query='.$query.'&limit=5&apikey=5e73cb5c198ff087f5b3e59afab15e08');
      $response = $request->getBody();
      $json = json_decode($response, true);
      $output = '<div id="search-box-result" class="stock-search-result" style="max-height: 450px;">';
      foreach($json as $row)
      {
       $output .= '<a class="ticker_name" onclick=test(this) style="color:#000!important;" href="#" tag="div" class="search-result-item"><div class="ticker_res">
       <span  class="tickerName">'.$row["symbol"].'-'.strtoupper($row["name"]).'</span><span class="currency">'.$row["currency"].'</span><span class="listed-at">'.$row["exchangeShortName"].'</span></div></a>';
      }
      $output .= '</div>';
      echo $output;
     }
    }
    
    //add data autocomplete
    function getPrice(Request $request){
        if($request->get('ticker'))
     {
        $ticker = $request->get('ticker');
        $client = new \GuzzleHttp\Client();
        $rtq = $client->get('https://financialmodelingprep.com/api/v3/quote/' . $ticker . '?apikey=5e73cb5c198ff087f5b3e59afab15e08');
        $response = $rtq->getBody();
        $json = json_decode($response, true);
        echo  number_format((float)$json[0]['price'], 2, '.', '');     
     }
    }
}