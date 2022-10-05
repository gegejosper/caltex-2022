<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Branch;
use App\User;
use App\Account;
use App\Accountbill;
use App\Customer;
use App\Customeraccount;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
class AccountsController extends Controller
{
    //
    public function addAccount(Request $request)
    {
        $rules = array(
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tax' => 'required',
            'discount' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array(
                     'errors' => $validator->getMessageBag()->toArray(),
            ));

        } else {
            $data = new Account();
            $data->lname = strtoupper($request->lname);
            $data->mname = strtoupper($request->mname);
            $data->fname = strtoupper($request->fname);
            $data->address = strtoupper($request->address);
            $data->tax = $request->tax;
            $data->discount = $request->discount;
            $data->branchid = $request->branchid;
            $data->save();

            return response()->json($data);
        }
    }

    public function viewAccount($branchId, $accountid){
        $dataAccount = Account::where('id', '=', $accountid)->first();
        $BranchId = $branchId;
        $dataBranch = Branch::where('id', '=', $branchId)->get();
        $accountDetails = Customeraccount::where('accountid', '=', $accountid)->get();
        
        $recentBill = Accountbill::where('accountid', '=', $accountid)->where('billstatus', '=', 'not paid')->latest()->get(); 
        $historyBill = Accountbill::where('accountid', '=', $accountid)->where('billstatus', '=', 'paid')->latest()->get(); 
        return view('admin.branch-account', compact('dataBranch', 'dataAccount', 'BranchId', 'accountDetails', 'recentBill', 'historyBill'));
    }
    public function editAccount(Request $req)
    {
       
        $data = Account::find($req->id);
        $data->lname = strtoupper($req->lname);
        $data->mname = strtoupper($req->mname);
        $data->fname = strtoupper($req->fname);
        $data->address = strtoupper($req->address);
        $data->tax = $req->tax;
        $data->discount = $req->discount;
        
        $data->save();
        
        return response()->json($data);
    }
    public function deleteAccount(Request $req)
    {
        Account::find($req->id)->delete();
        
        return response()->json();
    }
}
