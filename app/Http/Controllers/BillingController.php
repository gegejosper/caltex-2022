<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Branchuser;
//use Carbon\Carbon;
use App\Gastype;
use App\Branchgases;
use App\Branchdipping;
use App\Pump;
use App\Product;
use App\Branchproduct;
use App\Branchcredit;
use App\Branchsale;
use App\Branchdiscount;
use App\Branchother;
use App\Pumplog;
use App\Account;
use App\Accountbill;
use App\Accountcredit;
use App\Pumprecord;
use App\Billing_date;
use App\Branchpayment;
use Illuminate\Support\Facades\Auth;


class BillingController extends Controller
{
    //
    public function index(){
        if (Auth::check())
            {
                $userId = Auth::user()->id;
            }
            $dataBranch = Branchuser::where('userid', '=', $userId)->first();
    
        if(session()->has('sessionid')){

        }
        else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= ucwords($characters[rand(0, $charactersLength - 1)]);
            }
        
        session()->put('sessionid', $randomString);
        }
        if(session()->has('batchcode')){

        }
        else {
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
        $dataBranchcredit = Branchcredit::where('creditstatus', '=', 'INITIAL')->where('creditsession', "=", session()->get('sessionid'))->get();
        $dataBranchsale = Branchsale::where('status', '=', 'INITIAL')->where('salesession', "=", session()->get('sessionid'))->get();
        $dataBranchdiscount = Branchdiscount::where('status', '=', 'INITIAL')->where('discountsession', "=", session()->get('sessionid'))->get();
        $dataBranchother = Branchother::where('status', '=', 'INITIAL')->where('descsession', "=", session()->get('sessionid'))->get();
        // end Pump Code
        //dd($dataBranchcredit);
        return view('billing.dashboard', compact('dataBranch', 'dataBranchgas', 'BranchId', 'Branches', 'dataBranchgasPump', 'dataPumpReading', 'dataPump','dataAccount', 'dataProduct', 'dataGas', 'dataBranchcredit', 'dataBranchdiscount', 'dataBranchsale', 'dataBranchother'));

    }
    public function billing(){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $billing_dates = Billing_date::where('status', 'not set')->get();
        //$dataAccount = Account::where('branchid', '=', $BranchId)->get();
        $accountBill = Accountbill::where('billstatus', 'not paid')->where('branchid', '=', $BranchId)->with('account')->latest()->get();  
        //dd($dataAccount);
        return view('billing.bills', compact('dataBranch', 'Branches', 'accountBill', 'billing_dates'));

    }
    public function viewbill($billid){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataBranchgas = Branchgases::where('branchid', '=', $BranchId)->with('gas.branchpump', 'branchdipping')->get();
        $dataProduct = Branchproduct::where('branchid', '=', $BranchId)->with('product')->get();
        $Branches = Branch::where('id','=', $BranchId)->get();
        $Bill = Accountbill::where('id', '=', $billid)->with('account', 'user', 'payments')->first();
        
            //dd(count($Bill->payments));
            $billing_date = Billing_date::where('billingdate', $Bill->billdate)->first();
            // $daterange = explode('-', $dataBill->billdate); 
            // $fromDate = trim(str_replace('/', '-', $daterange[0]));
            // $toDate = trim(str_replace('/', '-', $daterange[1]));
            $fromDate = $billing_date->fromdate;
            $toDate = $billing_date->todate;

            $dataCreditPetrol = Accountcredit::where('accountid', $Bill->account->id)->where('creditdate', '>=', $fromDate)->with('gas')
                ->where('creditdate', '<=', $toDate)->where('credittype', '=', 'Petrol')->get();
            $dataCreditProduct = Accountcredit::where('accountid', $Bill->account->id)->where('creditdate', '>=', $fromDate)->with('productdetails')
                ->where('creditdate', '<=', $toDate)->where('credittype', '=', 'Product')->get();
       
        $dataAccount = Account::where('id', '=', $BranchId)->get();
         
        //dd($dataCredit);
        return view('billing.bill', compact('dataCreditProduct','dataCreditPetrol','dataBranch', 'Branches', 'Bill', 'billid', 'dataBranchgas', 'dataProduct'));

    }
    public function account($accountId){
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $Branches = Branch::where('id','=', $BranchId)->get();
        $account_credits = Accountcredit::with('gas', 'productdetails')->where('accountid', '=', $accountId)->orderBy('creditdate', 'desc')->paginate(100);
        $dataAccount = Account::where('id', '=', $accountId)->get();
        $recentBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '=', 'not paid')->latest()->get(); 
        $historyBill = Accountbill::where('accountid', '=', $accountId)->where('billstatus', '!=', 'paid')->latest()->get(); 
        $accountBill = Branchpayment::where('accountid', '=', $accountId)->with('bill')->latest()->get();
        //dd($historyBill);
        return view('billing.account', compact('dataBranch', 'Branches', 'dataAccount', 'recentBill', 'historyBill','accountBill', 'account_credits'));

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
        return view('billing.accounts', compact('dataBranch', 'Branches', 'accountBill', 'dataAccount'));
        
    }
    public function generatebill(Request $req){
        $billing_date = Billing_date::find($req->billdate);
        $fromDate = $billing_date->fromdate;
        $toDate = $billing_date->todate;
        if (Auth::check())
        {
            $userId = Auth::user()->id;
        }
        $dataBranch = Branchuser::where('userid', '=', $userId)->first();
        $BranchId = $dataBranch->branchid;
        $dataAccount = Account::where('branchid', '=', $BranchId)->get();
        $accountData = array();
        $prevBillAmount = 0;
        foreach($dataAccount as $Account){
            $accountCredits = Accountcredit::where('accountid', '=', $Account->id)
                ->where('creditdate', '>=', $fromDate)
                ->where('creditdate', '<=', $toDate)
                ->get();
            $totalAmount = 0;
            $totalQuantity = 0;
            foreach($accountCredits as $Credit){
                $totalAmount = $totalAmount + $Credit->amount;
                if($Credit->credittype == 'Petrol' && $Credit->product == 1 ){
                    $totalQuantity = $totalQuantity + $Credit->quantity;
                }
                
            }
            $countPrevBill = Accountbill::where('accountid', '=', $Account->id)->where('billstatus', '=', 'not paid')->get();
            if(count($countPrevBill) > 0){
                foreach($countPrevBill as $PrevBill){
                    $prevBillAmount = $prevBillAmount + $PrevBill->balance;
                    $updateAccountbill = Accountbill::where('id', '=', $PrevBill->id)
                            ->update(['billstatus' => 'merge']);
                    //dd($updateAccountbill);
                }  
            }
            else {
                $prevBillAmount = 0;
            }
             
            $discountedAmount = $totalAmount - $totalQuantity;
            $balance = $discountedAmount + $prevBillAmount;
            $accountDetails = array($Account->id,$Account->fname, $Account->lname,  $totalAmount, $balance, $totalQuantity, $prevBillAmount);
            array_push($accountData,$accountDetails);
        }
        //dd($accountData);
        foreach($accountData as $Data){
            $amount = $Data[3];
            if($amount > 0){
                //$countBill = Accountbill::where('accountid', '=', $Data[0])->count();     
                $countBill = Accountbill::where('accountid', '=', $Data[0])->latest()->first();   
                //dd($countBill);
                if(isset($countBill)){
                    $bill_number = $countBill->billnum + 1;
                }  
                else {
                    $bill_number = 1;
                }
                $data = new Accountbill();
                $data->billnum  = $bill_number;
                $data->billdate  = $billing_date->billingdate;
                $data->balance = $Data[4];
                $data->discount  = $Data[5];
                $data->amount  = $Data[3];
                $data->billstatus  = 'not paid';
                $data->accountid  = $Data[0];
                $data->branchid  = $BranchId;
                $data->userid = $userId;
                $data->totalamount = $Data[4];
                $data->prevbal = $Data[6];
                $data->save();
            }
            
        }
        $update_billing_date = Billing_date::where('id', $req->billdate)
                    ->update(['status' => 'set']);
        return redirect()->back()->with('success','Bill successfully generated for '. $billing_date->billingdate.'.');
    }
    public function update_credit(Request $req){
        $credit = Accountcredit::find($req->credit_id);
        $credit->invoicenum = $req->invoicenum;
        $credit->creditdate = $req->creditdate;
        $credit->platenumber = $req->platenumber;
        $credit->quantity = $req->quantity;
        $credit->unitprice = $req->unitprice;
        $credit->product = $req->product;
        $credit->amount = $req->amount;
        $credit->save();
        return response()->json();
    }
    public function remove_credit(Request $req){
        $credit = Accountcredit::find($req->credit_id);
        $credit->delete();
        return response()->json();
    }
    public function re_assess(Request $req){
        $billing_date = Billing_date::where('billingdate', $req->bill_date)->first();
        $fromDate = $billing_date->fromdate;
        $toDate = $billing_date->todate;
        //dd($fromDate , '-', $toDate);
        $Account = Account::where('id', '=', $req->account_id)->first();
        $accountCredits = Accountcredit::where('accountid', '=', $Account->id)
            ->where('creditdate', '>=', $fromDate)
            ->where('creditdate', '<=', $toDate)
            ->get();
        //dd($accountCredits);
        $totalAmount = 0;
        $totalQuantity = 0;
        foreach($accountCredits as $Credit){
            $totalAmount = $totalAmount + $Credit->amount;
            if($Credit->credittype == 'Petrol' && $Credit->product == 1){
                $totalQuantity = $totalQuantity + $Credit->quantity;
            }
        }
        $discountedAmount = $totalAmount - $totalQuantity;
        //dd($discountedAmount);
        $countPrevBill = Accountbill::where('id', '<>', $req->bill_id)->where('accountid', '=', $req->account_id)->where('billstatus', '=', 'not paid')->get();
        // $prevBillBalance = 0;
        // foreach($countPrevBill as $PrevBill){
        //     //$prevBillAmount = $PrevBill->totalamount;
        //     $prevBillBalance = $prevBillBalance + $PrevBill->balance;
        // }
        
        $bill = Accountbill::find($req->bill_id);
        $previous_balance = $bill->prevbal;
        $bill->balance = $discountedAmount + $previous_balance;
        $bill->discount  = $totalQuantity;
        $bill->amount  = $totalAmount;
        $bill->totalamount = $discountedAmount + $previous_balance;
        $bill->prevbal = $previous_balance;
        $bill->save();
        //dd($bill);
        return response()->json();
    }
}
