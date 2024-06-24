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
        $data=DB::table('balance')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.balance',compact('data'));
    }
    public function index(){
        return view('agency.accounting.index');
    }
    public function events(){
        $data=DB::table('events')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.events',compact('data'));
    }
    public function expenses(){
        $data=DB::table('expenses')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.expenses',compact('data'));
    }
    public function filling(){
        $data=DB::table('filling')->where('created_by', Auth::user()->id)->orderBy('duedate','asc')->get();
        return view('agency.accounting.filling',compact('data'));
    }
    public function payments(){
        $data=DB::table('payments')->where('created_by', Auth::user()->id)->get();
        return view('agency.accounting.payments',compact('data'));
    }
    public function reconciliation(){
        $data=DB::table('reconciliation')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.reconciliation',compact('data'));
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
        $proj = DB::table('projects')->where('user_id',Auth::user()->id)->get();
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

        return view('agency.accounting.margin',compact('podata','data','proj'));
    }
    public function work_force(){
        $data=DB::table('workforce')->where('created_by', Auth::user()->id)->get();
        return view('agency.accounting.work_force',compact('data'));
    }
    public function sales(){
        $data=DB::table('sales')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.sales',compact('data'));
    }
    public function trips(){
        $data=DB::table('trips')->where('created_by', Auth::user()->id)->orderBy('date','asc')->get();
        return view('agency.accounting.trips',compact('data'));
    }
    public function p_and_L(){
        $bank = Bookkeeping::where('user_id',Auth::user()->id)->get();
       
         return view('agency.accounting.p_and_L',compact('bank'));
    }
    public function vat(){
        $bank = Bookkeeping::where('user_id',Auth::user()->id)->get();

                $vatin = DB::table('banks')
                ->join('BankDetails', 'banks.id', '=', 'BankDetails.bank_id')
                ->where('banks.user_id', Auth::user()->id)
              //  ->get();
               // ->where('BankDetails.type','MI | Money In')
                ->select(DB::raw('YEAR(BankDetails.date) as year'), DB::raw('MONTH(BankDetails.date) as month'), DB::raw('SUM(vat_amount) as total'),'BankDetails.type','BankDetails.currency') // Replace 'column_name' with the column you want to sum
                ->groupBy(DB::raw('YEAR(BankDetails.date)'),DB::raw('MONTH(BankDetails.date)'),'BankDetails.type','BankDetails.currency')
                ->get();
                $vatout = DB::table('banks')
                ->join('BankDetails', 'banks.id', '=', 'BankDetails.bank_id')
                ->where('banks.user_id', Auth::user()->id)
                ->where('BankDetails.type','MO | Money Out')
                ->select(DB::raw('YEAR(BankDetails.date) as year'), DB::raw('MONTH(BankDetails.date) as month'), DB::raw('SUM(vat_amount) as total')) // Replace 'column_name' with the column you want to sum
                ->groupBy(DB::raw('YEAR(BankDetails.date)'),DB::raw('MONTH(BankDetails.date)'))
                ->get();
                //return $vatin;

        return view('agency.accounting.vat',compact('bank','vatin','vatout'));
    }
    public function filter_vat_month(Request $request){
        // ->whereMonth('date',date('m',strtotime($filter_date)))
        // ->whereYear('date',date('Y',strtotime($filter_date)))

        $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        $vat_month_filter = [];
        $startMonth = date('Y-m-01', strtotime($request->month));
         $endMonth = date('Y-m-31', strtotime($startMonth . ' +2 months'));
               
        foreach ($bank as $bank_item) {
            $data = BankDetails::where('date', '>=', $startMonth)
                                ->where('date', '<=', $endMonth)
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
    public function add_balance(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->month));

        $bal = DB::table('balance')->where('created_by', Auth::user()->id)->orderBy('id','desc')->first();
        // if($bal){
        //     $balance = $bal->balance;
        //     if($request->type == 'Credit'){
        //         $balance = $balance + $request->amount;
        //     }else{
        //         $balance = $balance - $request->amount;
        //     }
        // }else{
        //     $balance = 0;

        //     if($request->type == 'Credit'){
        //         $balance = $balance + $request->amount;
        //     }else{
        //         $balance = $balance - $request->amount;
        //     }
        // }
        DB::table('balance')->insert([
            'date' => $yearMonth,
          //  'balance' => $balance,
            'currency' => $request->currency,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->note,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Balance Added!');

    }
    public function updatebalance($id){
        $data = DB::table('balance')->where('id', $id)->first();
        return view('agency.accounting.updatebalance',compact('data'));

    }
    public function saveupdatebalance(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->month));
        DB::table('balance')
        ->where('id', $request->id)
        ->update([
            'date' => $yearMonth,
            'balance' => $request->balance,
            'currency' => $request->currency,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->note,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/balance')->withSuccess('Balance Updated!');

    }
    public function daletebalance($id){
        $data = DB::table('balance')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Balance Deleted!');
    }
    // ------------------------------------------------------

    public function add_event(Request $request){
        DB::table('events')->insert([
            'date' => $request->date,
            'event' => $request->event,
            'currency' => $request->currency,
            'location' => $request->location,
            'amount' => $request->amount,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Event Added!');

    }
    public function updateevent($id){
        $data = DB::table('events')->where('id', $id)->first();
        return view('agency.accounting.updateevent',compact('data'));

    }
    public function saveupdateevent(Request $request){
        DB::table('events')
        ->where('id', $request->id)
        ->update([
            'date' => $request->date,
            'event' => $request->event,
            'currency' => $request->currency,
            'location' => $request->location,
            'amount' => $request->amount,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('events')->withSuccess('Event Updated!');

    }
    public function deleteevent($id){
        $data = DB::table('events')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Event Deleted!');
    }
    // ------------------------------------------------------
    public function add_expenses(Request $request){
        DB::table('expenses')->insert([
            'date' => $request->date,
            'expense' => $request->expense,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Expense Added!');
    }
    public function updateexpenses($id){
        $data = DB::table('expenses')->where('id', $id)->first();
        return view('agency.accounting.updateexpenses',compact('data'));

    }
    public function saveupdateexpenses(Request $request){
        DB::table('expenses')
        ->where('id', $request->id)
        ->update([
            'expense' => $request->expense,
            'date' => $request->date,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/expenses')->withSuccess('Expenses Updated!');

    }
    public function deleteexpenses($id){
        $data = DB::table('expenses')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Expense Deleted!');
    }
    // ------------------------------------------------------
    public function add_filling(Request $request){
    
        DB::table('filling')->insert([
            'duedate' => $request->duedate,
            'description' => $request->description,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Filling Added!');
    }
    public function updatefilling($id){
        $data = DB::table('filling')->where('id', $id)->first();
        return view('agency.accounting.updatefilling',compact('data'));

    }

    public function saveupdatefilling(Request $request){
        DB::table('filling')
        ->where('id', $request->id)
        ->update([
            'duedate' => $request->duedate,
            'description' => $request->description,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/filling')->withSuccess('Filling Updated!');

    }
    public function deletefilling($id){
        $data = DB::table('filling')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Filling Deleted!');
    }

    
    // ------------------------------------------------------
    public function add_payment(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));

        DB::table('payments')->insert([
            'ponumber' => $request->ponumber,
            'Contractor' => $request->contractor,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'status' => $request->status,
            'date' => $yearMonth,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Payment Added!');
    }

    public function updatepayments($id){
        $data = DB::table('payments')->where('id', $id)->first();
        return view('agency.accounting.updatepayment',compact('data'));

    }

    public function saveupdatepayments(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));
        DB::table('payments')
        ->where('id', $request->id)
        ->update([
            'ponumber' => $request->ponumber,
            'Contractor' => $request->contractor,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'status' => $request->status,
            'date' => $yearMonth,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/payments')->withSuccess('Payment Updated!');

    }
    public function deletepayment($id){
        $data = DB::table('payments')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Payment Deleted!');
    }
    // ------------------------------------------------------
    public function add_trip(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));

        DB::table('trips')->insert([
            'transport' => $request->transport,
            'accommodation' => $request->accommodation,
            'meals' => $request->meals,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'misc' => $request->misc,
            'location' => $request->location,
            'date' => $yearMonth,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Trips Added!');
    }
   
    public function updatetrip($id){
        $data = DB::table('trips')->where('id', $id)->first();
        return view('agency.accounting.updatetrip',compact('data'));

    }

    public function saveupdatetrip(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));
        DB::table('trips')
        ->where('id', $request->id)
        ->update([
            'transport' => $request->transport,
            'accommodation' => $request->accommodation,
            'meals' => $request->meals,
            'currency' => $request->currency,
            'amount' => $request->amount,
            'misc' => $request->misc,
            'location' => $request->location,
            'date' => $yearMonth,
            'description' => $request->description,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/trips')->withSuccess('Trips Updated!');

    }

    public function deletetrip($id){
        $data = DB::table('trips')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Trip Deleted!');
    }
    // ------------------------------------------------------
    public function add_workforce(Request $request){
        DB::table('workforce')->insert([
            'costPerHouers' => $request->costph,
            'taxes' => $request->taxes,
            'totalHoures' => $request->totalh,
            'currency' => $request->currency,
            'costassociated' => $request->costassociated,
            'unit'=> $request->unit,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Workforce Added!');
    }

    public function updateworkforce($id){
        $data = DB::table('workforce')->where('id', $id)->first();
        return view('agency.accounting.updateworkforce',compact('data'));

    }

    public function saveupdateworkforce(Request $request){
        
        DB::table('workforce')
        ->where('id', $request->id)
        ->update([
            'costPerHouers' => $request->costph,
            'taxes' => $request->taxes,
            'totalHoures' => $request->totalh,
            'currency' => $request->currency,
            'costassociated' => $request->costassociated,
            'unit'=> $request->unit,
            'description' => $request->description,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/work_force')->withSuccess('WorkForce Updated!');

    }
    public function deleteworkforce($id){
        $data = DB::table('workforce')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Workforce Deleted!');
    }
    // ------------------------------------------------------
    public function add_reconciliation(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));

        DB::table('reconciliation')->insert([
            'date' => $yearMonth,
            'department' => $request->department,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Reconciliation Added!');
    }
    public function updatereconcilation($id){
        $data = DB::table('reconciliation')->where('id', $id)->first();
        return view('agency.accounting.updatereconcilation',compact('data'));

    }

    public function saveupdatereconcilation(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));
        DB::table('reconciliation')
        ->where('id', $request->id)
        ->update([
            'date' => $yearMonth,
            'department' => $request->department,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/reconciliation')->withSuccess('Reconciliation Updated!');

    } 
    public function deletereconciliation($id){
        $data = DB::table('reconciliation')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Reconciliation Deleted!');
    }
    // ------------------------------------------------------

    public function add_sales(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));

        DB::table('sales')->insert([
            'date' => $yearMonth,
            'monthlysale' => $request->monthlysale,
            'cost' => $request->cost,
            'currency' => $request->currency,
            'grossmorgin' => $request->grossmorgin,
            'profit' => $request->profit,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ]);
        return redirect()->back()->withSuccess('Sales Added!');
    }

    public function updatesales($id){
        $data = DB::table('sales')->where('id', $id)->first();
        return view('agency.accounting.updatesales',compact('data'));

    }

    public function saveupdatesales(Request $request){
        $yearMonth = date('Y-m-01', strtotime($request->date));
        DB::table('sales')
        ->where('id', $request->id)
        ->update([
            'date' => $yearMonth,
            'monthlysale' => $request->monthlysale,
            'cost' => $request->cost,
            'currency' => $request->currency,
            'grossmorgin' => $request->grossmorgin,
            'profit' => $request->profit,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);
        return redirect('/sales')->withSuccess('Sales Updated!');

    } 
    public function deletesale($id){
        $data = DB::table('sales')->where('id', $id)->delete();
        return redirect()->back()->withSuccess('Sale Deleted!');
    }
    public function changeBalCurrency(Request $request,$id){
        $res = \DB::table('balance')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeEventCurrency(Request $request,$id){
        $res = \DB::table('events')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeExpenseCurrency(Request $request,$id){
        $res = \DB::table('expenses')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeFillingCurrency(Request $request,$id){
        $res = \DB::table('filling')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changePaymentCurrency(Request $request,$id){
        $res = \DB::table('payments')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeReconCurrency(Request $request,$id){
        $res = \DB::table('reconciliation')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeSaleCurrency(Request $request,$id){
        $res = \DB::table('sales')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeTripCurrency(Request $request,$id){
        $res = \DB::table('trips')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function changeWorkCurrency(Request $request,$id){
        $res = \DB::table('workforce')->where('id',$id)->update([
            'currency' => $request->currency
        ]);
        if($res){
            return redirect()->back()->withSuccess('Currency changed!');
        }
        else {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    public function filter_sale_month(Request $request){
        $data = DB::table('sales')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->month . '%')->get();
        return view('agency.accounting.sales',compact('data'));
    }
    public function filter_sale_year(Request $request){
        $data = DB::table('sales')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->year . '%')->get();
        return view('agency.accounting.sales',compact('data'));
    }

    public function filter_trip_month(Request $request){
        $data = DB::table('trips')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->month . '%')->get();
        return view('agency.accounting.trips',compact('data'));
    }
    public function filter_trip_year(Request $request){
        $data = DB::table('trips')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->year . '%')->get();
        return view('agency.accounting.trips',compact('data'));
    }

    public function filter_balance_month(Request $request){
        // ->whereMonth('date',date('m',strtotime($filter_date)))
        // ->whereYear('date',date('Y',strtotime($filter_date)))

        $data = DB::table('balance')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->month . '%')->get();
        // return $bank;
        // $vat_month_filter = [];
        // foreach ($bank as $bank_item) {
        //     $data = BankDetails::where('date', 'like', '%' . $request->month . '%')
        //         ->where('bank_id', $bank_item->id)
        //         ->get();
        //     $vat_month_filter[] = $data;
        // }
        return view('agency.accounting.balance',compact('data'));
    }
    public function filter_balance_year(Request $request){
        // $bank = Bookkeeping::where('user_id', Auth::user()->id)->get();
        // $vat_year_filter = [];
        // foreach ($bank as $bank_item) {
        //     $data = BankDetails::where('date', 'like', '%' . $request->year . '%')
        //         ->where('bank_id', $bank_item->id)
        //         ->get();
        //     $vat_year_filter[] = $data;
        // }
        // return view('agency.accounting.vat',compact('vat_year_filter'));
        $data = DB::table('balance')->where("created_by", Auth::user()->id)->where('date', 'like', '%' . $request->year . '%')->get();
        return view('agency.accounting.balance',compact('data'));
        
    }

}
