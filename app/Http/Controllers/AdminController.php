<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Product;
use App\Gastype;
use App\Purchase;
use App\Account;
use App\Accountcredit;
use App\Branchgases;
use App\Branchreport;
use App\Branchcredit;
use App\Branchsale;
use App\Branchdiscount;
use App\Branchother;
use App\Pumplog;
use App\Starcard;
use App\Accountbill;
use App\Branchpayment;

class AdminController extends Controller
{
    //
    public function index()
    {
        $dataBranch = Branch::get();
        $data_branch_gas = Branch::with('branchgas')->get();
        $dataBranchgas = Branchgases::with('branchpump', 'branch')->get();
        $dataPurchase = Purchase::take(5)->latest()->get();
        return view('admin.dashboard', compact('dataBranch', 'dataPurchase', 'dataBranchgas', 'data_branch_gas'));
    }
    public function sales()
    {
        $dataBranchReport = Branchreport::with('branch')->latest()->get();
        $dataBranch = Branch::get();
        return view('admin.sales', compact('dataBranch', 'dataBranchReport'));
    }

    public function view_record($logsession){
        
        $dataBranch = Branch::get();
        $Branches = Branch::get();
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
        return view('admin.view-record', compact('dataBranch', 'Branches', 'dataPumplog', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother', 'arrayGas', 'logsession', 'dataDate', 'data_star_cards'));
    }
    public function products()
    {
        $dataBranch = Branch::get();
        $dataProduct = Product::get();
        return view('admin.products', compact('dataBranch', 'dataProduct'));
    }
    public function reports(Request $req)
    {
        if(isset($req->branch)){
            if($req->branch == 'all'){
                $condition = '!=';
            }else {
                $condition = '=';
            }
            if($req->month == '00'){
                $from_month = '01';
                $to_month = '12';
            }
            else {
                $from_month = $req->month;
                $to_month = $req->month;
            }
            $fromDate = $from_month.'-01-'.$req->year;
            $toDate = $to_month.'-31-'.$req->year;
            $dataBranchReport = Branchreport::where('branchid', $condition ,$req->branch)
                ->where('reportdate', '>=', $fromDate)
                ->where('reportdate', '<=', $toDate)
                ->with('branch')->latest()->get();
                //dd($toDate);
        }
        
        else {
            $dataBranchReport = Branchreport::with('branch')->latest()->get();
        }
        
        $dataBranch = Branch::get();
        return view('admin.reports', compact('dataBranch', 'dataBranchReport'));
    }
    public function gastypes()
    {
        $dataBranch = Branch::get();
        $dataGastype = Gastype::get();
        return view('admin.gastypes', compact('dataGastype', 'dataBranch'));
    }
    public function petrol()
    {   
        $dataBranch = Branch::with('branchgas.gas')->get();
        //dd($dataBranch);
        
        return view('admin.petrol', compact('dataBranch'));
    }

    public function order()
    {   
        $dataPurchase = Purchase::take(10)->latest()->get();
        $dataPurchaseLast = Purchase::take(1)->latest()->get();
        //dd($dataPurchaseLast);
        $dataBranch = Branch::with('branchgas.gas')->get();
        //dd($dataBranch);
        
        return view('admin.order', compact('dataPurchase', 'dataBranch', 'dataPurchaseLast'));
    }

    public function pumps()
    {   
        $dataBranch = Branch::get();
        return view('admin.pumps', compact('dataBranch'));
    }
    public function branches()
    {   
        
        $dataBranch = Branch::get();
        return view('admin.branches', compact('dataBranch'));
    }
    public function users()
    {
        $dataBranch = Branch::get();
        return view('admin.users', compact('dataBranch'));
    }
    public function settings()
    {
        $dataBranch = Branch::get();
        return view('admin.settings', compact('dataBranch'));
    }
    public function accounts()
    {  
        $dataBranch = Branch::get();
        $dataAccounts = Account::paginate();
        return view('admin.accounts', compact('dataBranch','dataAccounts'));
        
    }

    public function account($accountId){

        $Branches = Branch::get();
        $dataBranch = Branch::get();
        $dataAccount = Account::where('id', '=', $accountId)->get();
        $account_credits = Accountcredit::with('gas', 'productdetails')->where('accountid', '=', $accountId)->orderBy('creditdate', 'desc')->paginate(100);
        $recentBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '=', 'not paid')->latest()->get(); 
        $historyBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '!=', 'paid')->latest()->get(); 
        $accountBill = Branchpayment::where('accountid', '=', $accountId)->with('bill')->latest()->get();

        return view('admin.account', compact('dataBranch', 'Branches', 'dataAccount', 'recentBill', 'historyBill', 'accountBill', 'account_credits'));

    }

    public function view_payment_history($accountId){
       
        $dataAccount = Account::where('id', '=', $accountId)->first(); 
        $accountBill = Branchpayment::where('accountid', '=', $accountId)->with('bill')->latest()->get();
        $dataBranch = Branch::get();
        return view('admin.view-payment-history', compact('accountBill', 'dataAccount', 'dataBranch'));
    }
    
}
