@extends('layouts.billing')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="x_panel">
            <div class="x_content">

                <section class="content invoice">
                    <!-- title row -->
                    <!-- info row -->
                    <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        From
                        <address>
                        <img src="{{ asset('img/logo.png') }}" alt="" style="width:50px;">
                            
                            <br><strong>ATP Caltex Station</strong>
                            <br>Purok La Joma,
                            <br>San Jose, Aurora
                            <br>Zamboanga Del Sur
                            
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-lg-4 invoice-col">
                        To
                        <address>
                            <strong>{{ucwords($Bill->account->lname)}}, {{ucwords($Bill->account->fname)}} {{ucwords($Bill->account->mname)}}</strong>
                            <br>{{ucwords($Bill->account->address)}}
                            
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-lg-4 invoice-col">
                        <b>Bill #: {{$Bill->billnum}}</b>
                        <br>
                        <b>Date:</b> {{$Bill->billdate}}
                    </div>
                    <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                    <div class="col-xs-12 table">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Plate #</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                            <th class="no-print"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $total_product_amount = 0;
                            @endphp
                        @forelse($dataCreditPetrol as $CreditPetrol)
                            @php 
                            $total_product_amount += $CreditPetrol->amount;
                            @endphp
                            <tr>
                            <td>{{$CreditPetrol->invoicenum}}</td>
                            <td>{{$CreditPetrol->creditdate}}</td>
                            <td>{{$CreditPetrol->gas->gasname}}</td>
                            <td>{{$CreditPetrol->platenumber}}</td>
                            <td>{{$CreditPetrol->quantity}}</td>
                            <td>{{$CreditPetrol->unitprice}}</td>
                            <td>{{number_format($CreditPetrol->amount,3)}}</td>
                            @if($Bill->billstatus != 'merge')
                                @if(count($Bill->payments) == 0)
                                <td class="no-print"> 
                                    <a href="javascript:;" 
                                        class="btn btn-info btn-sm update_credit"
                                        data-invoicenum="{{$CreditPetrol->invoicenum}}"
                                        data-creditdate="{{$CreditPetrol->creditdate}}"
                                        data-platenumber="{{$CreditPetrol->platenumber}}"
                                        data-quantity="{{$CreditPetrol->quantity}}"
                                        data-petrol="{{$CreditPetrol->product}}"
                                        data-unitprice="{{$CreditPetrol->unitprice}}"
                                        data-amount="{{$CreditPetrol->amount}}"
                                        data-credit_id="{{$CreditPetrol->id}}"
                                        data-credittype = "{{$CreditPetrol->credittype}}"
                                    >
                                            <i class="fa fa-pencil" ></i>
                                    </a>
                                    <a href="javascript:;" 
                                        data-credit_id="{{$CreditPetrol->id}}"
                                        class="btn btn-danger btn-sm remove_credit">
                                        <i class="fa fa-times" ></i>
                                    </a>
                                </td>
                                @endif
                            @endif
                            </tr>
                            @empty
                            <tr>
                            <td colspan="6">No Record</td>
                            </tr>
                            @endforelse
                            @if(count($dataCreditProduct) > 0)
                            @forelse($dataCreditProduct as $CreditProduct)
                                @php 
                                $total_product_amount += $CreditProduct->amount;
                                @endphp
                            <tr>
                            <td>{{$CreditProduct->invoicenum}}</td>
                            <td>{{$CreditProduct->creditdate}}</td>
                            <td>{{$CreditProduct->productdetails->productname}}</td>
                            <td>{{$CreditProduct->platenumber}}</td>
                            <td>{{$CreditProduct->quantity}}</td>
                            <td>{{$CreditProduct->unitprice}}</td>
                            <td>{{$CreditProduct->amount}}</td>
                            @if($Bill->billstatus != 'merge')
                                @if(count($Bill->payments) == 0)
                                <td class="no-print"> 
                                    <a href="javascript:;" 
                                        class="btn btn-info btn-sm update_credit"
                                        data-invoicenum="{{$CreditProduct->invoicenum}}"
                                        data-creditdate="{{$CreditProduct->creditdate}}"
                                        data-platenumber="{{$CreditProduct->platenumber}}"
                                        data-quantity="{{$CreditProduct->quantity}}"
                                        data-unitprice="{{$CreditProduct->unitprice}}"
                                        data-product="{{$CreditProduct->product}}"
                                        data-amount="{{$CreditProduct->amount}}"
                                        data-credit_id="{{$CreditProduct->id}}"
                                        data-credittype = "{{$CreditProduct->credittype}}"
                                    >
                                            <i class="fa fa-pencil" ></i>
                                    </a>
                                    <a href="javascript:;" 
                                        data-credit_id="{{$CreditProduct->id}}"
                                        class="btn btn-danger btn-sm remove_credit">
                                        <i class="fa fa-times" ></i>
                                    </a>
                                </td>
                                @endif
                            @endif
                            </tr>
                            @empty      
                            <tr>
                            <td colspan="6">No Record</td>
                            </tr>
                            @endforelse
                            @endif
                        </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    
                    <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-xs-6">
                    Prepared by: <br>
                    <br>
                    <br>
                    <div style="margin-left:50px;" >
                    <strong>
                        
                        {{$Bill->user->name}}
                       
                        </strong>
                    </div>
                    <br> 
                    Recieved by: <br>
                    <br>
                    <br>
                    
                    -------------------------  
                   
                    <br> 
                    Recieved Date: <br>
                    <br>
                    -------------------------  
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-6">
                        <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th style="width:50%">Total Amount Due:</th>
                                <td>{{number_format($Bill->amount,3)}}</td>
                            </tr>
                            <tr>
                                <th>Less Discount</th>
                                <td>{{number_format($Bill->discount,3)}}</td>
                            </tr>
                            <tr>
                                @php
                                $amount_due = $Bill->amount - $Bill->discount
                                @endphp
                                <th>Amount Due</th>
                                <td>{{number_format($amount_due,3)}}</td>
                            </tr>
                            <tr>
                                <th>Previous Balance :</th>
                                <td>{{number_format($Bill->prevbal,3)}}</td>
                            </tr>
                            <tr>
                                <th>Grand Total :</th>
                                <td>{{number_format($Bill->totalamount,3)}}</td>
                            </tr>
                            @if($Bill->billstatus == 'merge')
                            <tr>
                                <th>Previous Bill Balance :</th>
                                <td>{{number_format($Bill->balance,3)}}</td>
                            </tr>
                            @endif
                            
                            <!-- <tr>
                                <th>Grand Total Amount :</th>
                                <td><?php
                                    $grandTotal = $Bill->totalamount + $Bill->prevbal;
                                    ?>
                                    {{number_format($grandTotal,3)}}
                                </td>
                            </tr> -->
                            </tbody>
                        </table>
                        
                        
                        
                        </div>
                    </div>
                    <!-- /.col -->
                    </div>
                   
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                    <div class="col-xs-12">
                        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        @if($Bill->billstatus != 'merge')
                            @if(count($Bill->payments) == 0)
                                <button class="btn btn-success re_assess" data-bill_id="{{$Bill->id}}" data-account_id="{{$Bill->account->id}}" data-bill_date="{{$Bill->billdate}}"><i class="fa fa-reply"></i> Re-Assess Bill</button>
                                <button class="btn btn-info add_record" data-bill_id="{{$Bill->id}}" data-account_id="{{$Bill->account->id}}" data-bill_date="{{$Bill->billdate}}"><i class="fa fa-plus"></i> Bill Record</button>
                            @endif
                        @endif
                    </div>
                    </div>
                </section>
    
            </div>
        </div>
    </div><!--row-->
</div>
<div id="updateCreditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    {{ csrf_field() }}
                    
                    <input type="hidden" class="form-control" id="credit_id">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-12" for="Price" >Invoice Number:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="invoicenum" name="invoicenum">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-12" for="Price" >Credit Date:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="creditdate" name="creditdate">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-12" for="Volume" >Plate Number:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="platenumber" name="platenumber">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-12" for="Price" >Petrol:</label>
                                <div class="col-12">
                                    <select name="product" id="product" class="form-control petrol_list">
                                        @foreach($dataBranchgas as $branchGas)
                                            <option id="gas{{ $branchGas->gas->id }}" value="{{ $branchGas->gas->id }}">{{ $branchGas->gas->gasname }}</option>
                                        @endforeach
                                        @foreach($dataProduct as $Product)
                                            <option id="product{{ $Product->product->id }}" value="{{ $Product->product->id }}">{{ $Product->product->productname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-12" for="Volume" >Quantity:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="quantity" name="quantity">
                                </div>
                            </div>
                        </div>
                        

                    </div> 
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-12" for="Price" >Unit Price:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="unitprice" name="unitprice">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-12" for="Price" >Amount:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="amount" name="amount">
                                </div>
                            </div>
                        </div>
                        
                    </div> 
                </form>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                    <button type="button" class="btn actionBtn" data-dismiss="modal">
                        <i class="fa fa-save"></i> Save
                     </button>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="deleteCreditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" id="credit_id">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger">
                                <h5>Are you sure you want to delete this credit record?</h5>
                            </div>  
                        </div>
                    </div>
                </form>
               
                <div class="modal-footer">
                    <button type="button" class="btn actionBtn" data-dismiss="modal">
                        <i class="fa fa-check"></i> Remove
                     </button>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/billscript.js') }}"></script>

@endsection