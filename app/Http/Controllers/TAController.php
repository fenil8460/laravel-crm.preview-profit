<?php

namespace App\Http\Controllers;
use App\Models\StockModels;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TAController extends Controller
{
    protected $records_per_page = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $view_data = [];

        if(!$request->ajax()) {

            return view('adminDash.admin')->with($view_data);

        } else {

            $request_data = $request->all();
            // return $request_data;

            $page = $request_data['page'];

            $query = StockModels::orderBy('created_at', 'desc');
            
            if ($request->has('status') && $request_data['status'] != '') {
                $status = trim($request_data['status']);
                $query->where('status', $is_active);
            }

            if ($request->has('title') && !empty($request_data['title'])) {
                $title = trim($request_data['title']);
                $query->where('title', 'like', '%' . $title . '%');
            }

            $records = $query->paginate($this->records_per_page);
            // return $records;

            $record_starts = $this->records_per_page * ($page - 1) + 1;
            $record_ends = $this->records_per_page * ($page - 1) + count($records);

            $view_data['records'] = $records;
            $view_data['page'] = $page;
            $view_data['record_starts'] = $record_starts;
            $view_data['record_ends'] = $record_ends;

            return view('adminDash.list')->with($view_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view_data = [];
        return view('adminDash.create')->with($view_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title|min:5',
            'content' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
        }

        $record = new StockModels();
        $record->title = trim($request->input('title'));
        $record->content = trim($request->input('content'));
        $record->status = 1;
        $record->user_id = 1;
        $record->created_at = Carbon::now();
        $record->updated_at = Carbon::now();
        $record->save();

        return response()->json([
            'status' => 'OK',
            'message' => 'Saved Successfully',
            'data' => @$record->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = StockModels::find($id);
        $view_data['record'] = $record;
        return view('adminDash.edit')->with($view_data);
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title|min:5',
            'content' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Input Data Errors!!',
                'errors' => $validator->errors()
            ]);
        }

        $record = StockModels::find($id);
        $record->title = trim($request->input('title'));
        $record->content = trim($request->input('content'));
        $record->status = 1;
        $record->user_id = 1;
        $record->updated_at = Carbon::now();
        $record->save();

        return response()->json([
            'status' => 'OK',
            'message' => 'Saved Successfully',
            'data' => @$record->id
        ]);
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