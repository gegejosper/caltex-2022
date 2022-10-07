<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Branchuser;
use App\Gastype;
use App\Branchgases;
use App\Branchdipping;
use App\Branchreport;
use App\Pump;
use App\Gaslog;
use App\Product;
use App\Branchproduct;
use App\Branchcredit;
use App\Branchsale;
use App\Branchdiscount;
use App\Branchother;
use App\Branchpayment;
use App\Pumplog;
use App\Account;
use App\Accountbill;
use App\Accountcredit;
use App\Pumprecord;
use App\Purchase;
use App\Starcard;
use App\Billing_date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class InchargeController extends Controller
{
    //
    public function branchaccount(){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
    }
    public function index(){
        if (Auth::check())
            {
                $userId = Auth::user()->id;
            }
            $dataBranch = Branchuser::where('userid', '=', $userId)->first();
    
        if(!session()->has('sessionid')){

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= ucwords($characters[rand(0, $charactersLength - 1)]);
        }
        
        session()->put('sessionid', $randomString);
        }
        if(!session()->has('batchcode')){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= ucwords($characters[rand(0, $charactersLength - 1)]);
        }
        
        session()->put('batchcode', $randomString);
        }
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        $dataAccount = Account::where('branchid', '=', $BranchId)->get();
        $dataBranchgas = Branchgases::where('branchid', '=', $BranchId)->with('gas.branchpump', 'branchdipping')->get();
        $dataProduct = Branchproduct::where('branchid', '=', $BranchId)->with('product')->get();
        $dataGas = Gastype::get();
        // Pump Codes
        $dataPump = Pump::where('branchid', '=', $BranchId)->with('gastype', 'pumplog', 'pumploglastfinal')->get();
        $dataPumpReading = Pumprecord::where('branchid', '=', $BranchId)->take(10)->orderBy('created_at', 'desc')->get();
        $dataBranchgasPump = Branchgases::where('branchid', '=', $BranchId)->with('gas')->get();
        $dataBranchcredit = Branchcredit::where('branchid', '=', $BranchId)->where('creditstatus', '=', 'INITIAL')->where('creditsession', "=", session()->get('sessionid'))->get();
        $dataBranchsale = Branchsale::where('branchid', '=', $BranchId)->where('status', '=', 'INITIAL')->where('salesession', "=", session()->get('sessionid'))->get();
        $dataBranchdiscount = Branchdiscount::where('branchid', '=', $BranchId)->where('status', '=', 'INITIAL')->where('discountsession', "=", session()->get('sessionid'))->get();
        $dataBranchother = Branchother::where('branchid', '=', $BranchId)->where('status', '=', 'INITIAL')->where('descsession', "=", session()->get('sessionid'))->get();
        $data_star_cards = Starcard::where('branchid', '=', $BranchId)->where('status', '=', 'INITIAL')->where('session', "=", session()->get('sessionid'))->get();
        // end Pump Code
        //dd($dataBranchcredit);
        return view('incharge.dashboard', compact('dataBranch', 'dataBranchgas', 'BranchId', 'Branches', 'dataBranchgasPump', 'dataPumpReading', 'dataPump','dataAccount', 'dataProduct', 'dataGas', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother', 'data_star_cards'));
    }

    public function dipping(){
        
        if(session()->has('dippingId')){

        }
        else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= ucwords($characters[rand(0, $charactersLength - 1)]);
            }
        
        session()->put('dippingId', $randomString);
        }

        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        $dataGastype = Gastype::with('branchpump')->get();
        $Gas = array();
        foreach($dataGastype as $gastype){
            $BranchGasAvail= Branchgases::where('branchid', '=', $BranchId)->where('gasid', '=', $gastype->id)->count();
            if($BranchGasAvail <= 0){
                array_push($Gas, $gastype);
            }
        }
        $dippingDate = Branchdipping::where('status', '=', 'Initial')->with('branchgas.gas')->get();
        
        $dataBranchgas = Branchgases::where('branchid', '=', $BranchId)->with('gas.branchpump', 'branchdipping')->get();
        //dd($dippingDate);
        $getDataBranchgas = Branchgases::where('branchid', '=', $BranchId)->with('gas')->get();

        $BranchGasDipping= Branchdipping::where('branchid', '=', $BranchId)->where('status', '=', 'Final')->with('branchgas.gas')->orderBy('created_at', 'desc')->get();
        //dd($BranchGasDipping);
        return view('incharge.dipping', compact('dataBranch', 'dataGastype', 'BranchId', 'dataBranchgas', 'Gas', 'dippingDate', 'BranchGasDipping', 'Branches'));
    }
    public function accounts(){
        
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $dataAccount = Account::where('branchid', '=', $BranchId)->get();
        $accountBill = Accountbill::where('branchid', '=', $BranchId)->with('account')->latest()->get();  
        //dd($dataAccount);
        return view('incharge.accounts', compact('dataBranch', 'Branches', 'accountBill', 'dataAccount'));
        
    }
    public function searchaccount (Request $request){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        if($request->ajax())
        {
            $dataAccount = Account::where('fname','LIKE','%'.$request->search."%")
                ->orWhere('lname','LIKE','%'.$request->search."%")
                ->orWhere('mname','LIKE','%'.$request->search."%")
                ->orWhere('address','LIKE','%'.$request->search."%")
                ->latest()
                ->get();
            $output="";
            if($dataAccount)
            { 
                foreach ($dataAccount as $key => $Account) {
                    if($Account->branchid == $BranchId){
                        $output.='<tr>
                        <td><a href="/incharge/account/'.$Account->id.'">'.$Account->id.'</a></td>
                        <td>'.ucwords($Account->lname).', '.ucwords($Account->fname).' '.ucwords($Account->mname).'</td>
                        <td>'.ucwords($Account->address).'</td>
                        <td>'.ucwords($Account->contactnum).'</td>
                        <td class="td-actions">
                        <a href="/incharge/account/'.$Account->id.'" class="btn btn-info btn-small"><i class="fa fa-search"></i></a>
                        </td>
                        ';
                        $output .='</tr>';
                    }   
                }
                return Response($output);
            }
        }
    }
    public function pumps(){
        $dataBranch = Branch::get();
        return view('incharge.pumps', compact('dataBranch'));
    }
    public function report(){
        $dataBranch = Branch::get();
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranchUser = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranchUser->branchid;
        $dataBranchReport = Branchreport::where('branchid', '=', $BranchId)->latest()->get();
        return view('incharge.report', compact('dataBranch', 'dataBranchReport'));
    }
    public function submitreport (Request $req) {
        
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        for($i = 0; $i < count($req->pumpid); $i++){
            $data = new Pumplog();
            $data->branchid = $req->branchid;
            $data->userid = $userId; 
            $data->logsession = session()->get('sessionid');
            $data->gasid = $req->gasid[$i]; 
            $data->pumpid = $req->pumpid[$i];
            $data->consumevolume = $req->consumevolume[$i]; 
            $data->openvolume = $req->openvolume[$i];
            $data->closevolume = $req->closevolume[$i]; 
            $data->unitprice = $req->unitprice[$i];
            $data->amount = $req->amount[$i]; 
            $data->batchcode = session()->get('batchcode'); 
            $data->datelog = date('m-d-Y');
            $data->status = 'Initial';
            $data->save();  
            $dataGaslog = new Gaslog();
            $dataGaslog->record_number = $data->logsession;
            $dataGaslog->branch_id = $req->branchid;
            $dataGaslog->gas_id = $req->gasid[$i];
            $dataGaslog->volume = $req->consumevolume[$i];
            $dataGaslog->log_type = 'Pump Log';
            $dataGaslog->status = 'Final';
            $dataGaslog->save();  
            
            // $gas_volume_consume = $req->consumevolume[$i];
            // $data_branch_gases = Branchgases::where('branchid', '=', $req->branchid)->where('gasid', '=', $req->gasid[$i])->first();    
            // $gas_volume = $data_branch_gases->volume;
            // $new_gas_volume = $gas_volume - $gas_volume_consume;
            // $updateBranchgases = Branchgases::where('id', '=', $data_branch_gases->id)
            // ->update(['volume' => $new_gas_volume]); 
        } 
        $dataPumprecord = new Pumprecord();
        $dataPumprecord->branchid = $req->branchid;
        $dataPumprecord->batchcode = session()->get('batchcode'); 
        $dataPumprecord->readingdate =  date('m-d-Y');
        $dataPumprecord->save();
        session()->forget('batchcode');

        
        return redirect('/incharge/dashboard/submit-report/check/'.$req->report_date_hidden.'/'.$req->report_time_hidden);
        
    }
    public function checkreport($date_report, $time_report){
        if (Auth::check()){
            $userId = Auth::user()->id;
        }
        $data_report_time = $time_report;
        $data_report_date = $date_report;
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        
        $dataBranchcredit = Branchcredit::where('creditstatus', '=', 'INITIAL')->where('creditsession', "=", session()->get('sessionid'))->get();
        $dataBranchsale = Branchsale::where('status', '=', 'INITIAL')->where('salesession', "=", session()->get('sessionid'))->get();
        $dataBranchdiscount = Branchdiscount::where('status', '=', 'INITIAL')->where('discountsession', "=", session()->get('sessionid'))->get();
        $dataBranchother = Branchother::where('status', '=', 'INITIAL')->where('descsession', "=", session()->get('sessionid'))->get();
        $dataPumplog = Pumplog::where('logsession', "=", session()->get('sessionid'))->get();
        $data_star_cards = Starcard::where('session', "=", session()->get('sessionid'))->get();
        $logsession = session()->get('sessionid');
        
        $dataGas = Gastype::get();
        $arrayGas = array();
        foreach($dataGas as $Gastypes){
            $dataGasPumplog = Pumplog::where('gasid', '=', $Gastypes->id)->where('logsession', "=", session()->get('sessionid'))->get();
            $volume =0;
            $price = 0;
            foreach($dataGasPumplog as $Pumplog){
                $volume = $volume + $Pumplog->consumevolume;
                $price = $Pumplog->unitprice;
            }
            $gassummary = array($Gastypes->gasname, $volume, $price);
            array_push($arrayGas, $gassummary);
        }
        //dd($arrayGas);
        return view('incharge.checksubmit', compact('dataBranch', 'BranchId', 'Branches', 'dataPumplog', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother', 'logsession', 'arrayGas', 'data_star_cards', 'data_report_time', 'data_report_date'));
    }
    public function reportsave($logsession, $date_report, $time_report){
        if (Auth::check()){
            $userId = Auth::user()->id;
        }
        $data_report_time = $time_report;
        $data_report_date = $date_report;
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        $get_date = Branchcredit::where('creditsession', '=', $logsession)->first();
        $Branchcredit = Branchcredit::where('creditsession', '=', $logsession)->where('creditstatus', 'INITIAL')->get();
        foreach($Branchcredit as $credit) {

            $creditid = explode(",",$credit->account);
            $product = explode(",",$credit->gasname);
            $data = new Accountcredit();
            $data->creditdate  = $credit->creditdate;
            $data->accountid  = trim($creditid[2]);
            $data->invoicenum  = $credit->invoice;
            $data->quantity  = $credit->liters;
            $data->product  = trim($product[1]);
            $data->unitprice  = $credit->unitprice;
            $data->amount  = $credit->amount;
            $data->platenumber  = $credit->creditplatenum;
            $data->credittype  = 'Petrol';
            $data->save();
        }
        $Branchsale = Branchsale::where('salesession', '=', $logsession)->get();
        foreach($Branchsale as $sale) {

            $creditid = explode(",",$sale->account);
            if($creditid[2] != 0) {
                $product = explode(",",$sale->product);
                $data = new Accountcredit();
                $data->creditdate  = $sale->saledate;
                $data->accountid  = $creditid[2];
                $data->invoicenum  = $sale->invoice;
                $data->quantity  = $sale->quantity;
                $data->product  = trim($product[1]);
                $data->unitprice  = $sale->price;
                $data->amount  = $sale->amount;
                $data->platenumber  = 'n/a';
                $data->credittype  = 'Product';
                $data->save();
            }
            
        }
        
        $updateBranchcredit = Branchcredit::where('creditsession', '=', $logsession)
                    ->update(['creditstatus' => 'FINAL']);
        $updateBranchsale = Branchsale::where('salesession', '=', $logsession)
                    ->update(['status' => 'FINAL']);
        $updateBranchdiscount = Branchdiscount::where('discountsession', '=', $logsession)
                    ->update(['status' => 'FINAL']);
        $updateBranchother = Branchother::where('descsession', '=', $logsession)
                    ->update(['status' => 'FINAL']);
        $updatePumplog = Pumplog::where('logsession', '=', $logsession)
                    ->update(['status' => 'FINAL']);
        $update_star_card = Starcard::where('session', '=', $logsession)
                    ->update(['status' => 'FINAL']);
        
        $dataReport = new Branchreport();
        $dataReport->reportdate = $data_report_date;
        $dataReport->report_time = $data_report_time;
        $dataReport->sessionrecord = $logsession;
        $dataReport->branchid = $BranchId;
        $dataReport->userid = $userId;
        $dataReport->report_note = $userId;
        $dataReport->save();
        session()->forget('sessionid');
        return redirect('/incharge/daily-report/'.$logsession);
        //return view('incharge.printreport', compact('dataBranch', 'BranchId', 'Branches', 'dataBranchgasPump', 'dataPumpReading', 'dataPumplog', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother'));
    }
    public function clear_cache(){
        $logsession = session()->get('sessionid');
        $dataBranchcredit = Branchcredit::where('creditstatus', '=', 'INITIAL')->orWhere('creditsession', "=", session()->get('sessionid'))->delete();
        $dataBranchsale = Branchsale::where('status', '=', 'INITIAL')->orWhere('salesession', "=", session()->get('sessionid'))->delete();
        $dataBranchdiscount = Branchdiscount::where('status', '=', 'INITIAL')->orWhere('discountsession', "=", session()->get('sessionid'))->delete();
        $dataBranchother = Branchother::where('status', '=', 'INITIAL')->orWhere('descsession', "=", session()->get('sessionid'))->delete();
        $dataPumplog = Pumplog::where('logsession', "=", session()->get('sessionid'))->delete();
        $dataBranchcredit = Branchcredit::where('creditstatus', '=', 'INITIAL')->delete();
        $dataBranchsale = Branchsale::where('status', '=', 'INITIAL')->delete();
        $dataBranchdiscount = Branchdiscount::where('status', '=', 'INITIAL')->delete();
        $dataBranchother = Branchother::where('status', '=', 'INITIAL')->delete();
        session()->forget('sessionid');
        return redirect()->back()->with('success', 'Cache successfully cleared.');
    }
    public function backdashboard(){
        $delete = Pumplog::where('logsession', '=', session()->get('sessionid'))
                    ->delete();
        return redirect('/incharge/dashboard');           
    }
    public function dailyreport($logsession){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();

        $dataGas = Gastype::get();
        $arrayGas = array();
        foreach($dataGas as $Gastypes){
            $dataGasPumplog = Pumplog::where('gasid', '=', $Gastypes->id)->where('logsession', "=", $logsession)->get();
            $volume =0;
            $price = 0;
            foreach($dataGasPumplog as $Pumplog){
                $volume = $volume + $Pumplog->consumevolume;
                $price = $Pumplog->unitprice;
            }
            $gassummary = array($Gastypes->gasname, $volume, $price);
            array_push($arrayGas, $gassummary);
        }
        $dataBranchcredit = Branchcredit::where('creditsession', "=", $logsession)->get();
        $dataBranchsale = Branchsale::where('salesession', "=", $logsession)->get();
        $dataBranchdiscount = Branchdiscount::where('discountsession', "=", $logsession)->get();
        $dataBranchother = Branchother::where('descsession', "=", $logsession)->get();
        $dataPumplog = Pumplog::where('logsession', "=", $logsession)->get();
        $dataDate = Pumplog::where('logsession', "=", $logsession)->first();
        $data_star_cards = Starcard::where('session', "=", $logsession)->get();
        $branch_report = Branchreport::where('sessionrecord', $logsession)->first();
        //return redirect('/incharge/daily-report/'.$logsession);
        return view('incharge.printreport', compact('dataBranch', 'BranchId', 'Branches', 'dataPumplog', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother', 'arrayGas', 'logsession', 'dataDate', 'data_star_cards', 'branch_report'));
    }
    public function viewrecord($logsession){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranch = Branch::get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        $dataGas = Gastype::get();
        $arrayGas = array();
        foreach($dataGas as $Gastypes){
            $dataGasPumplog = Pumplog::where('gasid', '=', $Gastypes->id)->where('logsession', "=", $logsession)->get();
            $volume =0;
            $price = 0;
            foreach($dataGasPumplog as $Pumplog){
                $volume = $volume + $Pumplog->consumevolume;
                $price = $Pumplog->unitprice;
            }
            $gassummary = array($Gastypes->gasname, $volume, $price);
            array_push($arrayGas, $gassummary);
        }
        $dataBranchcredit = Branchcredit::where('creditsession', "=", $logsession)->get();
        $dataBranchsale = Branchsale::where('salesession', "=", $logsession)->get();
        $dataBranchdiscount = Branchdiscount::where('discountsession', "=", $logsession)->get();
        $dataBranchother = Branchother::where('descsession', "=", $logsession)->get();
        $dataPumplog = Pumplog::where('logsession', "=", $logsession)->get();
        $dataDate = Pumplog::where('logsession', "=", $logsession)->first();
        $data_star_cards = Starcard::where('session', "=", $logsession)->get();
        return view('incharge.viewrecord', compact('dataBranch', 'BranchId', 'Branches', 'dataPumplog', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother', 'arrayGas', 'logsession', 'dataDate', 'data_star_cards'));
    }
    public function payments(){

        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $accountBill = Accountbill::where('branchid', '=', $BranchId)->where('billstatus', '=', 'not paid')->with('account')->latest()->paginate(50);  
        return view('incharge.payments', compact('dataBranch', 'Branches', 'accountBill'));
    }
    public function billing(){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        //$dataAccount = Account::where('branchid', '=', $BranchId)->get();
        $accountBill = Accountbill::where('branchid', '=', $BranchId)->with('account')->latest()->paginate(50);  
        //dd($dataAccount);
        return view('incharge.bills', compact('dataBranch', 'Branches', 'accountBill'));
    }
    public function viewbill($billid){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $accountBill = Accountbill::where('id', '=', $billid)->with('account', 'user')->take(1)->get();
        foreach($accountBill as $dataBill) {
            //dd($dataBill->account);
            $billing_date = Billing_date::where('billingdate', $dataBill->billdate)->first();
            // $daterange = explode('-', $dataBill->billdate); 
            // $fromDate = trim(str_replace('/', '-', $daterange[0]));
            // $toDate = trim(str_replace('/', '-', $daterange[1]));
            $fromDate = $billing_date->fromdate;
            $toDate = $billing_date->todate;

            $dataCreditPetrol = Accountcredit::where('accountid', $dataBill->account->id)->where('creditdate', '>=', $fromDate)->with('gas')
                ->where('creditdate', '<=', $toDate)->where('credittype', '=', 'Petrol')->get();
            $dataCreditProduct = Accountcredit::where('accountid', $dataBill->account->id)->where('creditdate', '>=', $fromDate)->with('productdetails')
                ->where('creditdate', '<=', $toDate)->where('credittype', '=', 'Product')->get();
        }  
        $dataAccount = Account::where('id', '=', $BranchId)->get();

        return view('incharge.bill', compact('dataCreditProduct','dataCreditPetrol','dataBranch', 'Branches', 'accountBill', 'billid'));

    }
    public function account($accountId){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $dataAccount = Account::where('id', '=', $accountId)->get();
        $recentBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '=', 'not paid')->latest()->get(); 
        $historyBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '!=', 'paid')->latest()->get(); 
        $accountBill = Branchpayment::where('accountid', '=', $accountId)->with('bill')->latest()->get();
        //$paymentHistory = Branchpayment::where();
        //dd($dataAccount);
        return view('incharge.account', compact('dataBranch', 'Branches', 'dataAccount', 'recentBill', 'historyBill', 'accountBill'));

    }
    public function paybill($billid){
        if (Auth::check()){
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataAccount = Account::where('branchid', '=', $BranchId)->get(); 
        $accountBill = Accountbill::where('id', '=', $billid)->with('account')->take(1)->get();
        return view('incharge.payment', compact('dataBranch', 'dataAccount', 'accountBill'));  
    }
    public function processpayment(Request $req){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $accountBill = Accountbill::where('id', '=', $req->paymentbillid)->with('account', 'user')->take(1)->get();
        foreach($accountBill as $dataBill) {
          $billamount = $dataBill->balance;

          $billaccountid = $dataBill->accountid;
        }  
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;

        $dataPayment = new Branchpayment();
        $dataPayment->userid = $userId;
        $dataPayment->branchid = $BranchId;
        $dataPayment->billid = $req->paymentbillid;
        $dataPayment->accountid = $billaccountid;
        $dataPayment->payment = $req->paymentadd;
        $billbalance = $billamount - $req->paymentadd;
        $new_billbalance = $billbalance - $req->withholding_tax;
        $dataPayment->balance = $new_billbalance;
        $dataPayment->notes = $req->payment_note;
        $dataPayment->save();
        if($req->paymentadd >= $billamount) {
            $updateAccountbill = Accountbill::where('id', '=', $req->paymentbillid)
                    ->update(['billstatus' => 'paid', 'balance' => $new_billbalance, 'withholding_tax' => $req->withholding_tax]);
        }
        else {
            $updateAccountbill = Accountbill::where('id', '=', $req->paymentbillid)
                    ->update(['balance' => $new_billbalance, 'withholding_tax' => $req->withholding_tax]);
        }
        return redirect()->back()->with('success','Payment successfully added!');
    }
    public function viewpaymenthistory($accountId){
        $this->branchaccount();
        $dataAccount = Account::where('id', '=', $accountId)->first(); 
        $accountBill = Branchpayment::where('accountid', '=', $accountId)->with('bill')->latest()->get();

        return view('incharge.paymenthistory', compact('accountBill', 'dataAccount'));
    }
    public function order(){   
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataPurchase = Purchase::where('branch_id', '=', $BranchId)->take(20)->latest()->get();
        $dataBranch = Branch::with('branchgas.gas')->get();
        //dd($dataBranch);
        
        return view('incharge.order', compact('dataPurchase', 'dataBranch'));
    }
    public function update_account_id_account_credit(){
        $account_credits = Accountcredit::all();
        foreach($account_credits as $account_credit){
            $credit = Accountcredit::find($account_credit->id);
            $credit->accountid = trim($credit->accountid);
            $credit->save();
        }
        echo 'Accounts updated';
    }

    public function update_account_id_branch_credit(){
        $branch_credits = Branchcredit::all();
        foreach($branch_credits as $branch_credit){
            $credit = Branchcredit::find($branch_credit->id);
            $account = preg_split("#,#", $credit->account);
            $credit->accountid = trim($account[2]);
            $credit->save();
            //echo $account[2].'<br>';
        }
        echo 'Accounts updated';
    }
    public function update_branch_report(){
        $dataBranchReport = Branchreport::all();
        foreach($dataBranchReport as $branch_report){
            $dataGas = Gastype::get();
            $arrayGas = array();
            foreach($dataGas as $Gastypes){
                $dataGasPumplog = Pumplog::where('gasid', '=', $Gastypes->id)->where('logsession', "=", $branch_report->sessionrecord)->get();
                $volume =0;
                $price = 0;
                foreach($dataGasPumplog as $Pumplog){
                    $volume = $volume + $Pumplog->consumevolume;
                    $price = $Pumplog->unitprice;
                }
                $gassummary = array($Gastypes->gasname, $volume, $price);
                array_push($arrayGas, $gassummary);
            }
            // Credit
            $dataBranchcredit = Branchcredit::where('creditsession', "=", $branch_report->sessionrecord)->get();
            $totalCredit = 0;
            foreach($dataBranchcredit as $Branchcredit){
                $totalCredit = $totalCredit + $Branchcredit->amount; 
            }            
            //End Credit  
            //Sales                
            $dataBranchsale = Branchsale::where('salesession', "=", $branch_report->sessionrecord)->get();
            $totalSales = 0;
            foreach($dataBranchsale as $Branchsale) {
                $totalSales = $totalSales + $Branchsale->amount;
            }
                                   
            // End Sales
            //Discount
            $dataBranchdiscount = Branchdiscount::where('discountsession', "=", $branch_report->sessionrecord)->get();
            $totalDiscount = 0;
            foreach($dataBranchdiscount as $Branchdiscount){
                    $totalDiscount = $totalDiscount + $Branchdiscount->amount;                     
            }
            //End Discount
            // Others
            $dataBranchother = Branchother::where('descsession', "=", $branch_report->sessionrecord)->get();
            $totalOthers = 0; 
            foreach($dataBranchother as $Branchother) {
                $totalOthers = $totalOthers + $Branchother->amount;
            }
            // End Others
            //Pump
            $dataPumplog = Pumplog::where('logsession', "=", $branch_report->sessionrecord)->get();
            $totalVolume = 0;
            $totalAmount = 0;
            foreach($dataPumplog as $Pump){
                $totalVolume = $totalVolume + $Pump->consumevolume; 
                $totalAmount = $totalAmount + $Pump->amount;
            }   
            //End Pump 
            // Star Cards             
            $data_star_cards = Starcard::where('session', "=", $branch_report->sessionrecord)->get();
            $totalCard = 0; 
            foreach($data_star_cards as $data_star_card) {
                $totalCard = $totalCard + $data_star_card->amount;
            }
            $totalAmountSummary = 0; 
            foreach($arrayGas as $gassummary) {
                $gasAmount = $gassummary[1] * $gassummary[2];
                $totalAmountSummary = $totalAmountSummary + $gasAmount;
            }
            $amountSummary = $totalAmountSummary - $totalCredit;  
            $cash_deduct_credit = $totalAmountSummary - $totalCredit;
            $cash_deduct_discount = $cash_deduct_credit - $totalDiscount;
            $cash_deduct_petty_cash = $cash_deduct_discount -  $totalOthers;
            if(count($data_star_cards)){
                $card_amount = $totalCard;
            }
            else{
                $card_amount = 0;
            }
            $total_sales = $cash_deduct_petty_cash + $totalCredit + $card_amount;           
            // $branch_sales = [];
            // $branch_sales['session'] = $branch_report->sessionrecord;
            // $branch_sales['totalCredit'] = $totalCredit;
            // $branch_sales['totalCash'] = $cash_deduct_petty_cash;
            // $branch_sales['totalSales'] = $totalCredit + $cash_deduct_petty_cash;
            // $branch_sales['totalDiscount'] = $totalDiscount;
            // $branch_sales['totalOthers'] = $totalOthers;
            // $branch_sales['totalAmount'] = $totalAmountSummary;
            // $branch_sales['totalCard'] = $card_amount;

            $session = $branch_report->sessionrecord;
            $update_totalCredit = $totalCredit;
            if($cash_deduct_petty_cash < 0){
                $cash_deduct_petty_cash = 0;
            }
            $update_totalCash = $cash_deduct_petty_cash;
            $update_totalSales = $totalCredit + $cash_deduct_petty_cash;
            $update_totalDiscount = $totalDiscount;
            $update_totalOthers = $totalOthers;
            $update_totalAmount = $totalAmountSummary;
            $update_totalCard = $card_amount;

            $updateBranchcredit = Branchreport::where('sessionrecord', '=', $branch_report->sessionrecord)
                ->update(['cash' =>  $update_totalCash, 'credit' => $update_totalCredit, 
                            'total_sales' => $update_totalSales,'discount' => $update_totalDiscount, 
                            'petty_voucher' => $update_totalOthers,'star_card' => $update_totalCard, 
                            'total_amount' => $update_totalAmount, 'pump_sale' => $update_totalAmount]);
        }   
        
    }
}
