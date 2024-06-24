<?php

namespace App\Http\Controllers;
use App\Models\Bookkeeping;
use App\Models\BankCategories;
use App\Models\BankDetails;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;
use Auth;

class accountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function balance(){
        return view('agency.accounting.balance');
    }
    public function index(){
        return view('agency.accounting.index');
    }
    public function events(){
        return view('agency.accounting.events');
    }
    public function expenses(){
        return view('agency.accounting.expenses');
    }
    public function filling(){
        return view('agency.accounting.filling');
    }
    public function payments(){
        return view('agency.accounting.payments');
    }
    public function reconciliation(){
        return view('agency.accounting.reconciliation');
    }
    public function margin(){
        // $projects=project::where('user_id',16)->get();
        // $temp_data = [];
        // $temp_items = [];

        // if($projects)
        // {
        //     foreach($projects as $data)
        //     {
        //         $record = \DB::table('template_data')->where('project_id',$data->id)->where('template_id',3)->first();
        //         if($record)
        //         {
        //           $temp_data[]=$record;

        //           $temp_item = \DB::table('template_items')->where('data_id',$record->id)->get();
        //           if($temp_item)
        //           {
        //               $temp_items[]=$temp_item;
        //           }
        //         }
        //     }
        // }
        $podata = DB::table('projects')
                        ->join('template_data','projects.id', '=' ,'template_data.project_id')
                        ->join('template_items','template_data.id','=','template_items.data_id')
                        ->select('projects.id','projects.project_title','template_data.id as d_id','template_items.total_net','template_items.data_id','template_data.invoice_no')
                        ->where('projects.user_id',Auth::user()->id)
                        ->where('template_data.template_id',3)
                       // ->where('template_data.project_id',57)
                      //  ->where('projects.id','template_data.project_id')
                        ->get();
                        $data = DB::table('projects')
                        ->join('template_data','projects.id', '=' ,'template_data.project_id')
                        ->join('template_items','template_data.id','=','template_items.data_id')
                        ->select('projects.id','projects.project_title','template_data.id as d_id','template_items.total_net','template_data.invoice_no')
                        ->where('projects.user_id',Auth::user()->id)
                        ->where('template_data.template_id',1)
                       ->orWhere('template_data.template_id',4)
                      //  ->where('projects.id','template_data.project_id')
                        ->get();
        // return [
        //     'projects' => $projects,
        //     'tempData' => $temp_data,
        //     'tempItems' => $temp_items,
        // ];

        return view('agency.accounting.margin',compact('podata','data'));
    }
    public function work_force(){
        return view('agency.accounting.work_force');
    }
    public function sales(){
        return view('agency.accounting.sales');
    }
    public function trips(){
        return view('agency.accounting.trips');
    }
    public function p_and_L(){
        $bank = Bookkeeping::where('user_id',Auth::user()->id)->get();
       
         return view('agency.accounting.p_and_L',compact('bank'));
    }
    public function vat(){
        $bank = Bookkeeping::where('user_id',Auth::user()->id)->get();

        return view('agency.accounting.vat',compact('bank'));
    }
    public function filter_vat_month(Request $request){
        // ->whereMonth('date',date('m',strtotime($filter_date)))
        // ->whereYear('date',date('Y',strtotime($filter_date)))

        $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        $vat_month_filter = [];
        foreach ($bank as $bank_item) {
            $data = BankDetails::where('date', 'like', '%' . $request->month . '%')
                ->where('bank_id', $bank_item->id)
                ->get();
            $vat_month_filter[] = $data;
        }
        return view('agency.accounting.vat',compact('vat_month_filter'));
    }
    public function filter_vat_year(Request $request){
        $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        $vat_year_filter = [];
        foreach ($bank as $bank_item) {
            $data = BankDetails::where('date', 'like', '%' . $request->year . '%')
                ->where('bank_id', $bank_item->id)
                ->get();
            $vat_year_filter[] = $data;
        }
        return view('agency.accounting.vat',compact('vat_year_filter'));
    }
    public function filter_pl_month(Request $request){
        // ->whereMonth('date',date('m',strtotime($filter_date)))
        // ->whereYear('date',date('Y',strtotime($filter_date)))

        $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        $pl_month_filter = [];
        foreach ($bank as $bank_item) {
            $data = BankDetails::where('date', 'like', '%' . $request->month . '%')
                ->where('bank_id', $bank_item->id)
                ->get();
            $pl_month_filter[] = $data;
        }
        return view('agency.accounting.p_and_L',compact('pl_month_filter'));
    }
    public function filter_pl_year(Request $request){
        $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        $pl_year_filter = [];
        foreach ($bank as $bank_item) {
            $data = BankDetails::where('date', 'like', '%' . $request->year . '%')
                ->where('bank_id', $bank_item->id)
                ->get();
            $pl_year_filter[] = $data;
        }
        return view('agency.accounting.p_and_L',compact('pl_year_filter'));
    }
}
