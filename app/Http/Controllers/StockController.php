<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\StockModels;
use App\Models\Leads;
use Illuminate\Support\Facades\Auth;
use App\Models\Newsletter;
use App\Models\UsefulLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Cookie;
use Validator;
use Session;
use Carbon\Carbon;
use App\Mail\SubMail;
use Illuminate\Support\Facades\Mail;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        /******************************
         ********* Main page*****************
         ***********************************/
        $tadata = StockModels::where('status', 'unhide')->get();;

        return view('stock180.index')->with('tadata', $tadata);
    }

    public function view_by_month(Request $request)
    {
        /******************************
         ********* View by month funtion*****************
         ***********************************/
        $posted_data = request()->all();

        $datepickerOne = $posted_data['datepickerOne'];
        $datepicker = $datepickerOne . '-01';
        $d = strtotime($datepicker);
        $curMonth = date('Y-m-d', $d);
        Session::put('datedata', $datepickerOne);

        //    dd($newformat);
        $final = strtotime("+1 month", strtotime($curMonth));
        $nextMonth = date('Y-m-d', $final);
        Session::put('nextmonth', $nextMonth);


        $tadata = StockModels::whereDate('OnDate', '>=', $datepicker)->where('status', '>=', 'unhide')->whereDate('OnDate', '<=', $nextMonth)->orderBy('ondate', 'DESC')->paginate(20);
        // dd($datepicker,$tadata);

        $data = [
            'from_date' => $datepickerOne,
            'tadata' => $tadata
        ];

        if (!$tadata->isEmpty()) {
            return view('stock180.tech_analysis', $data);
        } else {
            return view('stock180.tech_analysis', $data);
        }
    }

    public function refreshapicall($id,$Ticker,$Listed_at)
    {
        
        /*********************** get quote funtion on click*******************/
        // $Listed_at0 = 'TSX';
        $Listed_at1 = 'TSXV';
        $tadata = new StockModels();
        if (Session::get('datedata')) {
            $datedata = Session::get('datedata');
            $nextmonth = Session::get('nextmonth');
           
            if ($Listed_at  == $Listed_at1) 
            {
                
            } 
            else 
            {
                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://financialmodelingprep.com/api/v3/quote/' . $Ticker . '?apikey=5e73cb5c198ff087f5b3e59afab15e08');
                $response = $request->getBody();
                $json = json_decode($response, true);
                if (isset($json[0]['price']) && isset($json[0]['previousClose'])) {
                    $CurrentPrice = $json[0]['price'];
                    $previous_close = $json[0]['previousClose'];
                    DB::table('tadata')
                        ->where('Ticker', $Ticker)
                        ->update(['CurrentPrice' => $CurrentPrice, 'previous_close' => $previous_close]);
                        
                }
            } 
            /*{
                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=' . $Ticker . '&apikey=T5FI8K6S3UB93VBK');
                $response = $request->getBody();
                $json = json_decode($response, true);
                if (isset($json['Global Quote']['05. price']) && isset($json['Global Quote']['08. previous close'])) 
                {
                    $CurrentPrice = $json['Global Quote']['05. price'];
                    $previous_close = $json['Global Quote']['08. previous close'];
                    DB::table('tadata')
                        ->where('Ticker', $Ticker)
                        ->update(['CurrentPrice' => $CurrentPrice, 'previous_close' => $previous_close]);
                }
            }*/
            $data = DB::table('tadata')->select('*')->get();
            $json = json_decode($data, true);
            foreach ($json as $key => $value) {
                $id = $value['id'];
                $Action = $value['Action'];
                $SL_Exit =  $value['SL_Exit'];
                $ondate = $value['OnDate']; 
                $AtPrice = $value['AtPrice']; //buy price
                $Gain_Loss = $value['Gain_Loss'];
                $CurrentPrice = $value['CurrentPrice'];
                $glprice = $value['glprice'];
                if (strcmp(strtolower($Action),strtolower('Exit')) == 0) {
                    $Gain_Loss = 'Realised';
                    $numofshares = 1000 / $AtPrice;
                    $CurrentValue = $numofshares * $SL_Exit;
                    $glprice = $CurrentValue - 1000;    
                   
                } else {
                    $Gain_Loss = 'Unrealised';
                    $numofshares = 1000 / $AtPrice;
                    $CurrentValue = $numofshares * $CurrentPrice;
                    $glprice =  $CurrentValue - 1000;         // (this is to be displayed to the user in Gain/Loss $)
          
                }
               
                DB::table('tadata')
                    ->where('id', $id)  // find your data by their id
                    // ->limit(1)  // optional - to ensure only one record is updated.
                    ->update(array('glprice' => $glprice,'Gain_Loss'=> $Gain_Loss, 'Action' => $Action));
            }

            $status = 'unhide';
            // $tadata = DB::select('select * from tadata where status=?',[$status]);
            $tadata = StockModels::where('status', $status)->where('Ondate', '>', $datedata)->where('Ondate', '<', $nextmonth)->orderBy('ondate', 'DESC')->paginate(20);
            return view('stock180.tech_analysis', compact('tadata'));

            // ***********************************************************
            $nextmonth = Session::forget('nextmonth');
            $datedata = Session::forget('datedata');
        } 
        else 
        {
            if(strcmp(strtolower($Listed_at),strtolower($Listed_at1)) == 0)
            {
                // do nothing
                // dd('do nothing');
            } 
            else 
            {
                //    dd($Ticker, $Listed_at);
                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://financialmodelingprep.com/api/v3/quote/' . $Ticker . '?apikey=5e73cb5c198ff087f5b3e59afab15e08');
                $response = $request->getBody();
                $json = json_decode($response, true);
                // dd($json);
                if (isset($json[0]['price']) && isset($json[0]['previousClose'])) {
                    $CurrentPrice = $json[0]['price'];
                    $previous_close = $json[0]['previousClose'];
                    // dd($previous_close);
                    DB::table('tadata')
                        ->where('id', $id)
                        ->update(['CurrentPrice' => $CurrentPrice, 'previous_close' => $previous_close]);
                }
            } 

            $data = DB::table('tadata')->where('status','unhide')->select('*')->get();
            $json = json_decode($data, true);

            foreach ($json as $key => $value) {
                $id = $value['id'];
                $Action = $value['Action'];
                $SL_Exit =  $value['SL_Exit'];
                $ondate = $value['OnDate'];
                $AtPrice = $value['AtPrice'];
                $Gain_Loss = $value['Gain_Loss'];
                $CurrentPrice = $value['CurrentPrice'];
                $glprice = $value['glprice'];
                // dd($Action);
                if (strcmp(strtolower($Action),strtolower('Exit')) == 0) {
                    $Gain_Loss = 'Realised';
                    $numofshares = 1000 / $AtPrice;
                    $CurrentValue = $numofshares * $SL_Exit;
                    $glprice = $CurrentValue - 1000;   
                    // dd($glprice);
                   
                } else {
                    $Gain_Loss = 'Unrealised';
                    $numofshares = 1000 / $AtPrice;
                    $CurrentValue = $numofshares * $CurrentPrice;
                    $glprice = $CurrentValue - 1000; 
                    // dd($glprice); 
                           
                }
               
                DB::table('tadata')
                    ->where('id', $id)->where('status','unhide')    
                    ->update(array('glprice' => $glprice, 'Gain_Loss'=> $Gain_Loss, 'Action' => $Action));  // update the record in the DB. 
                    // dd($glprice); 
            }

            $status = 'unhide';
            // $tadata = DB::select('select * from tadata where status=?',[$status]);
            
        $tadata = StockModels::where('status', $status)->orderBy('ondate', 'DESC')->paginate(20);
            return view('stock180.tech_analysis', compact('tadata'));
        }
    }
    public function tech_analysis()
    {
        if (Auth::check()) {
            // The user is logged in...
        }
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('ondate', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }


    // sorting functions
    public function sortByTicker()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Ticker', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByTickerDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Ticker', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByListedat()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Listed_at', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByListedatDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Listed_at', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }

    public function sortByonDate()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('OnDate', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByonDateDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('OnDate', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }

    public function sortBycompName()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('CompanyName', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortBycompNameDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('CompanyName', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByCurrency()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Currency', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByCurrencyDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Currency', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByBuyPrice()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('AtPrice', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByBuyPriceDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('AtPrice', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByPreviousClose()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('previous_close', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByPreviousCloseDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('previous_close', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByCurrentPrice()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('CurrentPrice', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByCurrentPriceDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('CurrentPrice', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByExit()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('SL_Exit', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByExitDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('SL_Exit', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByGL()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Gain_Loss', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByGLDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Gain_Loss', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortBy1000Invested()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('glprice', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortBy1000InvestedDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('glprice', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByAction()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Action', 'ASC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    public function sortByActionDesc()
    {
        $status = 'unhide';
        // $tadata = DB::select('select * from tadata where status=?',[$status]);
        $tadata = StockModels::where('status', $status)->orderBy('Action', 'DESC')->paginate(20);
        return view('stock180.tech_analysis', compact('tadata'));
    }
    // end sorting functions
    public function useful_links()
    {
        /******************************
         ********* useful link page*****************
         ***********************************/
        $status = 'Show';
        $useful_links = UsefulLink::where('Visible', $status)->orderByDesc("created_at")->paginate(20);
        return view('stock180.useful_links', compact('useful_links'));
    }
    public function newsletter()
    {
        /******************************
         ********* newsletter page *****************
         ***********************************/
        $status = 'Show';
        $newsletter = Newsletter::where('Visible', $status)->orderByDesc("created_at")->paginate(20);
        return view('stock180.newsletter', compact('newsletter'));
    }
    public function contact_us()
    {

        /******************************
         ********* contect us page *****************
         ***********************************/
        return view('stock180.contact_us');
    }

    public function term_condition()
    {

        /******************************
         ********* term_condition us page *****************
         ***********************************/
        return view('stock180.about-us.terms-and-conditions');
    }

    #sub email function
    public function sub_email(Request $request)
    {
        $posted_data = request()->all();
        $validator = Validator::make($posted_data, array(
            'sub_email' => 'required|email',
           

        ), array(
            'sub_email.required' => 'Enter your email address.'
        ));
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please give valid email!!',
                'errors' => $validator->errors()
            ]);
        }


        $sub_email = $posted_data['sub_email'];
        try{
            $leads_data = new Leads();
            $leads_data->email = $sub_email;
            $leads_data->DoNotSend = 0;
            $leads_data->Unsubscribe = 0;
            $leads_data->created_at = Carbon::now();
            $leads_data->save();
            $data=['sender'=>$sub_email];
            Mail::to($sub_email)
            
            ->send(new SubMail($data));
            return response()->json([
                'status' => 'OK',
                'message' => 'Thank You for Subscribing our newsletter ',
                'redirect_url' => url('/')
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
    public function unsubscribe($sub_email)
    {
        #email unsubscribe function
        $data = [];
        try {
            $res = Leads::where('email',$sub_email)
                ->limit(1)
                ->update(array('Unsubscribe' => 1));
                if ($res) {

                    $data['Unsubscribe'] = 'Unsubscribed';
                    return view('stock180.unsubcribed',$data);
                }else{
                    $data['Unsubscribe'] = 'Already unsubscribed';
                    return view('stock180.unsubcribed',$data);
                }
               
        } catch (\Throwable $th) {
            throw $th;
        }
       
    }
}
