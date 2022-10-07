@extends('layouts.incharge')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="page-title">
            <div class="title_left">
            <h3>
                @foreach($Branches as $Branch)
                    {{$Branch->branchname}}
                @endforeach 
            </h3>
           
            </div>
            <div class="text-right">
                {{session()->get('batchcode')}} 
                <a href="javascript:;" class="btn btn-danger btn-success text-left clear-cache">Clear Cache</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
            @endif
            <div class="x_panel tile">
                
                    <div class="clearfix"></div>
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-credit" id="credit-tab" role="tab" data-toggle="tab" aria-expanded="true">Credit</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab-discount" role="tab" id="discount-tab" data-toggle="tab" aria-expanded="false">Discount</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab-sales" role="tab" id="sales-tab" data-toggle="tab" aria-expanded="false">Sales</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab-others" role="tab" id="others-tab" data-toggle="tab" aria-expanded="false">Others</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab-card" role="tab" id="card-tab" data-toggle="tab" aria-expanded="false">Starcard</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab-pump" role="tab" id="pump-tab" data-toggle="tab" aria-expanded="false">Pump</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-lg-3">
                                <div class="row mb-5">
                                <div class="col-lg-6"> 
                                    <label for="Date">Date</label>    
                                    <input type="text" class="form-control"  aria-describedby="basic-addon2" name="report_date" id="report_date" value="{{ date('m-d-Y')}}"> 
                                </div>
                                <div class="col-lg-6">  
                                    <label for="Date">Time</label>    
                                    <input type="text" class="form-control"  aria-describedby="basic-addon2" name="report_time" id="report_time" value="" placeholder="5:00AM-12:30PM">  
                                </div>
                            </div>
                                </div>
                            </div>
                            
                            <div role="tabpanel" class="tab-pane fade active in" id="tab-credit" aria-labelledby="credit-tab">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3">
                                        
                                        <div class="input-group col-lg-12">
                                        <label for="Account">Account</label>
                                        <select name="accountcredit" id="accountcredit" class="js-example-basic-single form-control select2" type="text">
                                            <option value=""></option>
                                            @foreach($dataAccount as $Account)
                                                <option value="{{$Account->lname}}, {{$Account->fname}},  {{ $Account->id }}">{{$Account->lname}}, {{$Account->fname}} </option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Invoice">Invoice No.</label>
                                            <input type="text" class="form-control"  aria-describedby="basic-addon2" name="creditinvoicenum" id="creditinvoicenum" value="">  
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Volume">Product</label>
                                            <!-- <select name="branchgas" id="branchgas" class="form-control">
                                                @foreach($dataGas as $Gas)
                                                    <option value="{{ $Gas->gasname }}, {{ $Gas->id }}">{{ $Gas->gasname }}</option>
                                                @endforeach
                                            </select> -->
                                            <select name="branchgas" id="branchgas" class="form-control branchgascredit">
                                                <option value="0"></option>
                                                @foreach($dataBranchgas as $branchGas)
                                                    <option value="{{ $branchGas->gas->gasname }}, {{ $branchGas->gas->id }}, {{ $branchGas->price }}">{{ $branchGas->gas->gasname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Liters">Liters</label>
                                            <input type="text" class="form-control" placeholder="Liters"  aria-describedby="basic-addon2" name="creditliters" id="creditliters">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Unit Price">Unit Price</label>
                                            <input type="text" class="form-control" placeholder="Unit Price"  aria-describedby="basic-addon2" name="creditunitprice" id="creditunitprice">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Volume">Amount</label>
                                            <input type="text" class="form-control" placeholder="Amount"  aria-describedby="basic-addon2" name="creditamount" id="creditamount">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Plate">Plate #</label>
                                            <input type="text" class="form-control" placeholder="Plate #"  aria-describedby="basic-addon2" name="creditplatenum" id="creditplatenum" value="N/A">
                                        </div>
                                        
                                        <div class="input-group col-lg-12">
                                            <button class="btn btn-primary" type="submit" id="creditadd">Add</button> 
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="x_panel tile">        
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                        </li>
                                                    </ul> 
                                                    <div class="x_title">
                                                        <h4>Credit</h4>
                                                    </div>
                                                <div class="x_content">
                                                <table class="table table-striped" id="credittable">
                                                    <thead>
                                                    <tr>
                                                    <td>Date</td>
                                                    <td>Invoice #</td>
                                                    <td>Account</td>
                                                    <td>Product</td>
                                                    <td>Plate #</td>
                                                    <td>Liters</td>
                                                    <td>Unit Price</td>
                                                    <td>Amount</td>
                                                    </tr>
                                                    </thead>
                                                    @php 
                                                        $total_credit_amount = 0;
                                                    @endphp
                                                    @forelse($dataBranchcredit as $Branchcredit)
                                                    @php 
                                                    $total_credit_amount = $total_credit_amount + $Branchcredit->amount;
                                                    @endphp
                                                    <tr class='credititem{{$Branchcredit->id}}'>
                                                    <td>{{$Branchcredit->creditdate}}</td>
                                                    <td>{{$Branchcredit->invoice}}</td>
                                                    <?php 
                                                        $accountname = explode(",",$Branchcredit->account);
                                                        $gasname = explode(",",$Branchcredit->gasname);
                                                    ?>
                                                    <td>{{$accountname[0]}}, {{$accountname[1]}}</td>
                                                    <td>{{$gasname[0]}}</td>
                                                    <td>{{$Branchcredit->creditplatenum}}</td>
                                                    <td>{{$Branchcredit->liters}}</td>
                                                    <td>{{$Branchcredit->unitprice}}</td>
                                                    <td>{{$Branchcredit->amount}}</td>
                                                    <td><a class='delete-credit btn btn-danger btn-xs' data-id='{{$Branchcredit->id}}' data-amount="{{$Branchcredit->amount}}"><i class='fa fa-times'></i></a></td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                    <td colspan="7" id="nocreditrecord"><em>No Record</em></td>
                                                    </tr>
                                                    
                                                    @endforelse
                                                </table>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="80%" class="text-right">
                                                            <strong>Total: </strong> 
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <strong><span class="text-danger" id="total_credit_amount">{{number_format($total_credit_amount,2)}}</span></strong> 
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div><!--x_content-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-discount" aria-labelledby="discount-tab">
                                <div class="row">
                                    <div class="col-lg-3">
                                       
                                        <div class="input-group col-lg-12">
                                            <select name="accountdis" id="accountdis" class="js-example-basic-single form-control" type="text">
                                                @foreach($dataAccount as $Account)
                                                    <option value="{{$Account->lname}}, {{$Account->fname}},  {{ $Account->id }}">{{$Account->lname}}, {{$Account->fname}} </option>
                                                @endforeach
                                                <option value="0">Save Plus</option>  
                                            </select>
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Product">Product</label>
                                            <select name="branchgasdis" id="branchgasdis" class="form-control">
                                                @foreach($dataGas as $Gas)
                                                    <option value="{{ $Gas->gasname }}, {{ $Gas->id }}">{{ $Gas->gasname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Plate">Plate #</label>
                                            <input type="text" class="form-control" placeholder="Plate #"  aria-describedby="basic-addon2" name="displatenum" id="displatenum">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Amount">Amount</label>
                                            <input type="text" class="form-control" placeholder="Amount"  aria-describedby="basic-addon2" name="disamount" id="disamount">
                                        </div>
                                        
                                        <div class="input-group col-lg-12">
                                            <input type="hidden" name="branchid" class="form-control" id="branchid" value="{{$BranchId}}">
                                            <button class="btn btn-primary" type="submit" id="discountadd">Add</button> 
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="x_panel tile">
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul> 
                                                <div class="x_title">
                                                    <h4>Discount</h4>
                                                </div>
                                                <div class="x_content">
                                                    <table class="table table-striped" id="discounttable">
                                                        <thead>
                                                        <tr>
                                                        <td>Date</td>
                                                        <td>Account</td>
                                                        <td>Product</td>
                                                        <td>Plate #</td>
                                                        
                                                        <td>Amount</td>
                                                        </tr>
                                                        </thead>
                                                        @php 
                                                        $total_discount_amount = 0;
                                                        @endphp
                                                        @forelse($dataBranchdiscount as $Branchdiscount)
                                                        @php 
                                                        $total_discount_amount = $total_discount_amount + $Branchdiscount->amount;
                                                        @endphp
                                                        <tr class='discountitem{{$Branchdiscount->id}}'>
                                                        
                                                        <?php 
                                                            $disaccountname = explode(",",$Branchdiscount->account);
                                                            $disgasname = explode(",",$Branchdiscount->gasname);
                                                        ?>
                                                        <td>{{$Branchdiscount->discountdate}}</td>
                                                        <td>{{$disaccountname[0]}}, {{$disaccountname[1]}}</td>
                                                        <td>{{$disgasname[0]}}</td>
                                                        <td>{{$Branchdiscount->platenum}}</td>
                                                        <td>{{$Branchdiscount->amount}}</td>
                                                        <td><a class='delete-discount btn btn-danger btn-xs' data-id='{{$Branchdiscount->id}}' data-amount="{{$Branchdiscount->amount}}"><i class='fa fa-times'></i></a></td>
                                                        </tr>
                                                        
                                                        @empty
                                                        <tr>
                                                        <td colspan="6" id="nodiscountrecord"><em>No Record</em></td>
                                                        </tr>
                                                        
                                                        @endforelse
                                                        
                                                    </table>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <td width="80%" class="text-right">
                                                                <strong>Total: </strong> 
                                                            </td>
                                                            <td colspan="2" class="text-left">
                                                                <strong><span class="text-danger" id="total_discount_amount">{{number_format($total_discount_amount,2)}}</span></strong> 
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!--x_content-->
                                            </div><!--col-->
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-sales" aria-labelledby="sales-tab">
                                <div class="row">
                                    <div class="col-lg-3">
                                       
                                        <div class="input-group col-lg-12">
                                        <label for="Invoice">Invoice No.</label>
                                        <input type="text" class="form-control"  aria-describedby="basic-addon2" name="saleinvoicenum" id="saleinvoicenum" value="">  
                                        </div>
                                        
                                        <div class="input-group col-lg-12">
                                            <label for="Product">Product</label>
                                            <select name="salebranchproduct" id="salebranchproduct" class="form-control salebranchproduct">
                                                <option>-Select Product-</option>
                                                @foreach($dataProduct as $Product)
                                                    <option value="{{ $Product->product->productname }}, {{ $Product->product->id }}, {{ $Product->price }}">{{ $Product->product->productname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Price">Price</label>
                                            <input type="text" class="form-control"  aria-describedby="basic-addon2" name="saleprice" id="saleprice" value="">  
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Quantity">Quantity</label>
                                            <input type="text" class="form-control"  aria-describedby="basic-addon2" name="salequantity" id="salequantity" value="">  
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Amount">Amount</label>
                                            <input type="text" class="form-control"  aria-describedby="basic-addon2" name="saleamount" id="saleamount" value="">  
                                        </div>
                                        <div class="input-group col-lg-12" id="paymentoption">
                                            <label for="Payment">Payment</label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" checked name="payment" class="paymenttype"  value="CASH">
                                                <label class="form-check-label" for="inlineRadio1">Cash</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio"  class="paymenttype" name="payment" value="CREDIT">  
                                                <label class="form-check-label" for="inlineRadio2">Credit</label>
                                            </div>
                                            
                                            <select name="accountsale" id="accountsale" class="js-example-basic-single form-control" type="text">
                                                @foreach($dataAccount as $Account)
                                                    <option value="{{$Account->lname}}, {{$Account->fname}},  {{ $Account->id }}">{{$Account->lname}}, {{$Account->fname}} </option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                        
                                        <div class="input-group col-lg-12">
                                            <button class="btn btn-primary" type="submit" id="salesadd">Add</button> 
                                        </div>
                                    
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="x_panel tile">
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul> 
                                                <div class="x_title">
                                                    <h4>Sales</h4>
                                                </div>
                                                <div class="x_content">
                                                <table class="table table-striped" id="saletable">
                                                    <thead>
                                                    <tr>
                                                    <td>Date</td>
                                                    <td>Invoice</td>
                                                    <td>Type</td>
                                                    <td>Account</td>
                                                    <td>Product</td>
                                                    <td>Quantity</td>
                                                    <td>Price</td>
                                                    <td>Amount</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php 
                                                    $total_sale_amount = 0;
                                                    @endphp
                                                    @forelse($dataBranchsale as $Branchsale) 
                                                    @php 
                                                    $total_sale_amount = $total_sale_amount + $Branchsale->amount;
                                                    @endphp
                                                    <?php 
                                                        $saleaccountname = explode(",",$Branchsale->account);
                                                        $saleproduct = explode(",",$Branchsale->product);
                                                    ?>
                                                    <tr class='saleitem{{$Branchsale->id}}'>
                                                        <td>{{$Branchsale->saledate}}</td>
                                                        <td>{{$Branchsale->invoice}}</td>
                                                        <td>{{$Branchsale->paymenttype}}</td>
                                                        <?php 
                                                            if($saleaccountname[2] == 0){       
                                                        ?>
                                                        <td></td>
                                                        <?php  
                                                        } else {
                                                        ?>
                                                        <td>{{$saleaccountname[0]}}, {{$saleaccountname[1]}}</td>
                                                        <?php }
                                                        ?>
                                                        <td>{{$saleproduct[0]}}</td>
                                                        <td>{{$Branchsale->quantity}}</td>
                                                        <td>{{$Branchsale->price}}</td>
                                                        <td>{{$Branchsale->amount}}</td>
                                                        <td><a class='delete-sale btn btn-danger btn-xs' data-id='{{$Branchsale->id}}' data-amount="{{$Branchsale->amount}}"><i class='fa fa-times'></i></a></td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                    <td colspan="6" id="nosalesrecord"><em>No Record</em></td>
                                                    </tr>
                                                    @endforelse
                                                    
                                                    </tbody>
                                                </table>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="80%" class="text-right">
                                                            <strong>Total: </strong> 
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <strong><span class="text-danger" id="total_sale_amount">{{number_format($total_sale_amount,2)}}</span></strong> 
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div><!--x_content-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-others" aria-labelledby="others-tab">
                                <div class="row">
                                    <div class="col-lg-3">
                                        
                                        <div class="input-group col-lg-12">
                                            <label for="Description">Description</label>
                                            <input type="text" class="form-control" placeholder="Description"  aria-describedby="basic-addon2" name="description" id="description">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Amount">Amount</label>
                                            <input type="text" class="form-control" placeholder="Amount"  aria-describedby="basic-addon2" name="descamount" id="descamount">
                                        </div>
                                        
                                        <div class="input-group col-lg-12">
                                            <button class="btn btn-primary" type="submit" id="othersadd">Add</button> 
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="x_panel tile">
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul> 
                                                <div class="x_title">
                                                    <h4>Others</h4>
                                                </div>
                                                <div class="x_content">
                                                <table class="table table-striped" id="otherstable">
                                                    <thead>
                                                    <tr>
                                                    <td>Date</td>
                                                    <td>Description</td>
                                                    <td>Amount</td>
                                                    </tr>
                                                    </thead>
                                                    @php 
                                                    $total_others_amount = 0;
                                                    @endphp
                                                    <tbody>
                                                    @forelse($dataBranchother as $Branchother) 
                                                    @php 
                                                    $total_others_amount = $total_others_amount + $Branchother->amount;
                                                    @endphp

                                                        <tr class='otheritem{{$Branchother->id}}'>
                                                            <td>{{$Branchother->othersdate}}</td>
                                                            <td>{{$Branchother->desc}}</td>
                                                            <td>{{$Branchother->amount}}</td>
                                                            <td><a class='delete-other btn btn-danger btn-xs' data-id='{{$Branchother->id}}' data-amount="{{$Branchother->amount}}"><i class='fa fa-times'></i></a></td>
                                                        </tr>
                                                    @empty
                                                    <tr>
                                                    <td colspan="3" id="nootherrecord"><em>No Record</em></td>
                                                    </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="80%" class="text-right">
                                                            <strong>Total: </strong> 
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <strong><span class="text-danger" id="total_others_amount">{{number_format($total_others_amount,2)}}</span></strong> 
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div><!--x_content-->
                                            </div><!--col-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-card" aria-labelledby="card-tab">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="input-group col-lg-12">
                                            <label for="Amount">Amount</label>
                                            <input type="text" class="form-control" placeholder="Amount"  aria-describedby="basic-addon2" name="cardamount" id="cardamount">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Description">Note</label>
                                            <input type="text" class="form-control" placeholder="Note"  aria-describedby="basic-addon2" name="note" id="note">
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <label for="Date">Date</label>
                                            <input type="text" class="form-control"  aria-describedby="basic-addon2" name="carddate" id="carddate" value="{{ date('m-d-Y')}}">  
                                        </div>
                                        <div class="input-group col-lg-12">
                                            <button class="btn btn-primary" type="submit" id="starcard">Add</button> 
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="x_panel tile">
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul> 
                                                <div class="x_title">
                                                    <h4>Star Card</h4>
                                                </div>
                                                <div class="x_content">
                                                <table class="table table-striped" id="cardtable">
                                                    <thead>
                                                    <tr>
                                                    <td>Amount</td>
                                                    <td>Note</td>
                                                    </tr>
                                                    </thead>
                                                    @php 
                                                    $total_starcard_amount = 0;
                                                    @endphp
                                                    <tbody>
                                                    @forelse($data_star_cards as $data_star_card) 
                                                    @php 
                                                    $total_starcard_amount = $total_starcard_amount + $data_star_card->amount;
                                                    @endphp
                                                        <tr class='carditem{{$data_star_card->id}}'>
                                                            <td>{{$data_star_card->amount}}</td>
                                                            <td>{{$data_star_card->note}}</td>
                                                            <td><a class='delete-card btn btn-danger btn-xs' data-id='{{$data_star_card->id}}' data-amount="{{$data_star_card->amount}}"><i class='fa fa-times'></i></a></td>
                                                        </tr>
                                                    @empty
                                                    <td colspan="3" id="nocardrecord"><em>No Record</em></td>
                                                    </tr>
                                                    @endforelse
                                                
                                                    </tbody>
                                                </table>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="80%" class="text-right">
                                                            <strong>Total: </strong> 
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <strong><span class="text-danger" id="total_starcard_amount">{{number_format($total_starcard_amount,2)}}</span></strong> 
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div><!--x_content-->
                                            </div><!--col-->
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="tab-pump" aria-labelledby="pump-tab">
                                <div class="row">
                                    <form action="{{ route('submitreport') }}" method="post">
                                    <input type="hidden" class="form-control"  aria-describedby="basic-addon2" name="report_date_hidden" id="report_date_hidden"> 
                                    <input type="hidden" class="form-control"  aria-describedby="basic-addon2" name="report_time_hidden" id="report_time_hidden"> 
                                        <div class="col-lg-12">
                                            <div class="x_panel tile">
                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <ul class="nav navbar-right panel_toolbox dashboard-panel">
                                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                        </li>
                                                    </ul> 
                                                    <div class="x_title">
                                                        <h4>Pumps</h4>
                                                    </div>
                                                    <div class="x_content">
                                                        <input type="hidden" name="branchid" class="form-control" id="branchid" value="{{$BranchId}}">
                                                        {{ csrf_field() }}
                                                        <table class="table table-striped table-bordered" id="table">
                                                            <thead>
                                                            <tr>
                                                                <th> Name</th>
                                                                <th> Opening</th>
                                                                <th> Closing</th>
                                                                <th> Volume </th>
                                                                <th> Unit Price </th>
                                                                <th> Amount </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $counter = 1; @endphp
                                                            @forelse($dataPump as $Pump)
                                                            <tr class="item{{$Pump->id}}">
                                                                
                                                                <td>{{$Pump->pumpname}}</td>
                                                                <td>
                                                                <input type="hidden" name="pumpid[]" class="form-control" id="pump{{$Pump->id}}" value="{{$Pump->id}}">
                                                                <input type="hidden" name="gasid[]" class="form-control" id="gasid{{$Pump->gasid}}" value="{{$Pump->gasid}}">
                                                                
                                                                <?php if($Pump->pumplog != null) {?>
                                                                    
                                                                        <input type="text" name="openvolume[]" class="form-control" id="openvolume{{$Pump->id}}" value="{{$Pump->pumplog['closevolume']}}" readonly></td>
                                                                    
                                                                <?php }
                                                                else{
                                                                ?>
                                                                    <input type="text" name="openvolume[]" class="form-control" id="openvolume{{$Pump->id}}" value="0" required readonly></td>
                                                                
                                                                <?php }?>
                                                                <td><input type="text" name="closevolume[]" class="form-control" id="closevolume{{$Pump->id}}" tabindex="{{$counter}}" required></td>
                                                                <td><input type="text" name="consumevolume[]" class="form-control" id="consumevolume{{$Pump->id}}" required></td>
                                                                <td>
                                                                @foreach($dataBranchgasPump as $branchDetails)
                                                                    @if($branchDetails->gasid == $Pump->gasid && $branchDetails->branchid == $Pump->branchid)
                                                                    <input type="text" name="unitprice[]" class="form-control" id="unitprice{{$Pump->id}}" value="{{$branchDetails->price}}" readonly></td>
                                                                    @endif
                                                                @endforeach
                                                                <td><input type="text" name="amount[]" class="form-control" id="amount{{$Pump->id}}" required></td>
                                                            </tr>
                                                            @php $counter += 1; @endphp
                                                            @empty
                                                            
                                                            @endforelse
                                                            
                                                            </tbody>
                                                        </table>
                                                        <table class="table table-striped">
                                                        <tr>
                                                            <td width="80%" class="text-right">
                                                                <strong>Total: </strong> 
                                                            </td>
                                                            <td colspan="2" class="text-left">
                                                                <strong><span class="text-danger" id="total_pump_amount">0</span></strong> 
                                                            </td>
                                                        </tr>
                                                        <tr><td colspan="6" class="text-center"> <button type="submit" id="save_daily_sales" class="btn btn-info btn-lg"> <i class="fa fa-save"></i> Save</button></td></tr>
                                                    </table>
                                                    </div><!--x_content-->
                                                </div><!--col-->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                
            </div>
        </div><!--col-->
    </div><!--row-->
    </div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
                <div class="deleteContent">
                    Are you sure you want to delete <span class="dname"></span> ? <span
                        class="hidden did"></span>
                        <input type="hidden" id="deleteamount" name="deleteamount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn actionBtn" data-dismiss="modal">
                        <span id="footer_action_button" class='glyphicon'> </span>
                    </button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="clearModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Clear cache?</h4>
            </div>
            <div class="modal-body">
                
                <div class="deleteContent">
                    <p> Are you sure you want to clear cache? This will delete all the details inputed in the form.</p>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn actionBtn" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-danger clear" data-dismiss="modal">
                        <i class="fa fa-check"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $("#accountdis").select2({
        placeholder: "Select account"
    });
    $("#accountcredit").select2({
        placeholder: "Select account"
    });
    // $("#accountsale").select2({
    //     placeholder: "Select account"
    // });
</script>
<script src="{{ asset('js/app.js') }}"></script>
@foreach($dataPump as $Pump)
<script>
$(function() {
    $('#closevolume{{$Pump->id}}').keyup(function() {  
        updateTotal();
    });
    const updateTotal = function () {
      let closevalue = parseFloat($('#closevolume{{$Pump->id}}').val()).toFixed(3);
      let openvalue = parseFloat($('#openvolume{{$Pump->id}}').val()).toFixed(3);
      
      let consumevolume = closevalue - openvalue;
      $('#consumevolume{{$Pump->id}}').val(parseFloat(consumevolume).toFixed(3));
      let unitprice = parseFloat($('#unitprice{{$Pump->id}}').val()).toFixed(3);
      let amount = consumevolume * unitprice;
      //let pump_amount = amount + total_pump_amount;
      //$('#total_pump_amount').text(pump_amount.toFixed(3));
      $('#amount{{$Pump->id}}').val(parseFloat(amount).toFixed(3));

        var total_pump_amount = 0;
        var pump_amount = document.getElementsByName('amount[]');
        for (var i = 0; i <pump_amount.length; i++) {
            let amount_pump = pump_amount[i].value;
           //console.log(amount_pump);
            if(amount_pump != null && amount_pump != 0 && amount_pump != NaN){
                total_pump_amount = parseFloat(total_pump_amount) + parseFloat(amount_pump);
                //console.log(total_pump_amount);
                $('#total_pump_amount').text(total_pump_amount.toFixed(3));
            }  
            
        }
        
        
        //console.log(total_pump_amount);
    
    //console.log(total_pump_amount);
    };
 });
</script>
@endforeach



<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/incharge-dashboard.js') }}"></script>

@endsection