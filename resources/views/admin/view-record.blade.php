
@extends('layouts.admin')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-lg-12" style="width:100% !important;">
            <div class="x_content" style="width:100% !important;" align="center">
            <img src="{{ asset('img/logo.png') }}" alt="" style="width:50px; ">
                        
                    <div class="x_title">
                        <h4>
                            Daily Sales Report: {{$dataDate['datelog']}} (@foreach($Branches as $branch){{$branch->branchname}} @endforeach Branch)
                        </h4>
                    </div>
               
            </div>
        </div>
    </div>
    <div class="row">
        <div class="x_panel tile">
            <div class="col-lg-12" style="width:100% !important;">
               
                    <div class="x_title">
                        <h4>Pumps</h4>
                    </div>
                    <div class="x_content">
                            <table class="table table-striped table-pump" id="table" style="width:100%;">
                                <thead>
                                <tr>
                            
                                    <th> Name</th>
                                    <th> Opening</th>
                                    <th> Closing</th>
                                    <th> Volume </th>
                                    <th class="text-right"> Unit Price </th>
                                    <th class="text-right"> Amount </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $totalVolume = 0;
                                    $totalAmount = 0;
                                ?>
                                @forelse($dataPumplog as $Pump)
                                <tr class="item{{$Pump->id}}">
                                    
                                    <td>{{$Pump->pump->pumpname}}</td>
                                    <td>{{$Pump->openvolume}}</td>
                                    <td>{{$Pump->closevolume}}</td>
                                    <td>{{$Pump->consumevolume}}</td>
                                    <td class="text-right">{{number_format($Pump->unitprice,3)}}</td>
                                    <td class="text-right">{{number_format($Pump->amount,3)}}</td>
                                    <?php 
                                        $totalVolume = $totalVolume + $Pump->consumevolume; 
                                        $totalAmount = $totalAmount + $Pump->amount;
                                    ?>
                                </tr>
                                @empty
                                
                                @endforelse
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total Volume</strong></td>
                                    <td>{{number_format($totalVolume,2)}}</td>
                                    <td class="text-right"><strong>Total Amount</strong></td>
                                    <td class="text-right" >{{number_format($totalAmount, 3) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div><!--x_content-->
                   
                </div>
        </div>
            <div class="x_panel tile">  
                <div class="col-lg-12" style="width:100% !important;">
                     
                                <div class="x_title">
                                    <h4>Discount</h4>
                                </div>
                                <table class="table table-striped" id="discounttable">
                                    <thead>
                                    <tr>
                                    
                                    <th>Account</th>
                                    <th>Product</th>
                                    <th>Plate #</th>
                                    
                                    <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <?php $totalDiscount = 0; ?>
                                    @forelse($dataBranchdiscount as $Branchdiscount)
                                    <tr class='discountitem{{$Branchdiscount->id}}'>
                                    
                                    <?php 
                                        $disaccountname = explode(",",$Branchdiscount->account);
                                        $disgasname = explode(",",$Branchdiscount->gasname);
                                    ?>
                                    <td>{{$disaccountname[0]}}, {{$disaccountname[1]}}</td>
                                    <td>{{$disgasname[0]}}</td>
                                    <td>{{$Branchdiscount->platenum}}</td>
                                    <td class="text-right">{{number_format($Branchdiscount->amount,3)}}</td>
                                    </tr>
                                    <?php $totalDiscount = $totalDiscount + $Branchdiscount->amount;?>
                                    @empty
                                    <tr>
                                    <td colspan="6" id="nosalerecord"><em>No Record</em></td>
                                    </tr>
                                    
                                    @endforelse
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right">{{number_format($totalDiscount,3) }}</td>
                                    </tr>
                                </table>
                            
                                <div class="x_title">
                                    <h4>Credit</h4>
                                </div>
                                <table class="table table-striped" id="credittable">
                                    <thead>
                                    <tr>
                                    <th>Invoice #</th>
                                    <th>Account</th>
                                    <th>Product</th>
                                    <th>Plate #</th>
                                    <th>Liters</th>
                                    <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <?php 
                                        $totalCredit = 0;
                                    ?>
                                    @forelse($dataBranchcredit as $Branchcredit)
                                    <tr class='credititem{{$Branchcredit->id}}'>
                                    <td>{{$Branchcredit->invoice}}</td>
                                    <?php 
                                        $accountname = explode(",",$Branchcredit->account);
                                        $gasname = explode(",",$Branchcredit->gasname);
                                    ?>
                                    <td>{{$accountname[0]}}, {{$accountname[1]}}</td>
                                    <td>{{$gasname[0]}}</td>
                                    <td>{{$Branchcredit->creditplatenum}}</td>
                                    <td class="text-right" >{{$Branchcredit->liters}}</td>
                                    <td class="text-right">{{number_format($Branchcredit->amount,3)}}</td>
                                    </tr>
                                    <?php $totalCredit = $totalCredit + $Branchcredit->amount; ?>
                                    @empty
                                    <tr>
                                    <td colspan="6" id="nocreditrecord"><em>No Record</em></td>
                                    </tr>
                                    
                                    @endforelse
                                    <tr>
                                        <td colspan="5" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right" >{{number_format($totalCredit,3) }}</td>
                                    </tr>
                                </table>
                   
                </div>
            </div>
            <div class="x_panel tile">
                <div class="col-lg-12" style="width:100% !important;">
                    <div class="x_content">
                                <div class="x_title">
                                    <h4>Sales</h4>
                                </div>
                                <table class="table table-striped" id="saletable">
                                    <thead>
                                    <tr>
                                    <th>Invoice</th>
                                    <th>Type</th>
                                    <th>Account</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $totalSales = 0;
                                    ?>
                                    @forelse($dataBranchsale as $Branchsale) 
                                    <?php 
                                        $saleaccountname = explode(",",$Branchsale->account);
                                        $saleproduct = explode(",",$Branchsale->product);
                                    ?>
                                    <tr class='saleitem{{$Branchsale->id}}'>
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
                                        <td class="text-right">{{number_format($Branchsale->price,3)}}</td>
                                        <td class="text-right">{{number_format($Branchsale->amount,3)}}</td>
                                    </tr>
                                    <?php $totalSales = $totalSales + $Branchsale->amount; ?>
                                    @empty
                                    <tr>
                                    <td colspan="6" id="nosalesrecord"><em>No Record</em></td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right">{{number_format($totalSales,3) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            
                                <div class="x_title">
                                    <h4>Others</h4>
                                </div>
                                <table class="table table-striped" id="otherstable" style="wi">
                                    <thead>
                                    <tr>
                                    
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $totalOthers = 0; 
                                    ?>
                                    @forelse($dataBranchother as $Branchother) 
                                        <tr class='otheritem{{$Branchother->id}}'>
                                            <td>{{$Branchother->desc}}</td>
                                            <td class="text-right">{{number_format($Branchother->amount,3)}}</td>
                                        </tr>
                                        <?php $totalOthers = $totalOthers + $Branchother->amount;?>
                                    @empty
                                    <tr>
                                    <td colspan="3" id="nootherrecord"><em>No Record</em></td>
                                    </tr>
                                    @endforelse
                                    <tr >
                                        <td class="text-right"><strong>Total Amount</strong></td>
                                        <td class="text-right">{{number_format($totalOthers, 3)}}</td>
                                    </tr>
                                    </tbody>
                                </table>  
                                @if(count($data_star_cards))
                                    <div class="x_title">
                                        <h4>Starcard</h4>
                                    </div>
                                    <table class="table table-striped" id="otherstable" style="wi">
                                        <thead>
                                        <tr>
                                        
                                        <th>Note</th>
                                        <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $totalCard = 0; 
                                        ?>
                                        @forelse($data_star_cards as $data_star_card) 
                                            <tr class='carditem{{$data_star_card->id}}'>
                                                <td>{{$data_star_card->note}}</td>
                                                <td class="text-right">{{number_format($data_star_card->amount,3)}}</td>
                                            </tr>
                                            <?php $totalCard = $totalCard + $data_star_card->amount;?>
                                        @empty
                                        <tr>
                                        <td colspan="3" id="nootherrecord"><em>No Record</em></td>
                                        </tr>
                                        @endforelse
                                        <tr >
                                            <td class="text-right"><strong>Total Amount</strong></td>
                                            <td class="text-right">{{number_format($totalCard, 3)}}</td>
                                        </tr>
                                        </tbody>
                                    </table>  
                                @endif         
                            </div>
                        </div>
                    </div><!--x_content-->
                    </div>
                    <div class="row">
                        <div class="x_panel tile">
                            <div class="col-lg-12" style="width:100% !important;">
                                    <div class="x_title">
                                        <h4>Summary</h4>
                                    </div>
                                    <table class="table table-striped" id="otherstable">
                                        <thead>
                                        <tr>
                                        <th>Product</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Total Volume</th>
                                        <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $totalAmountSummary = 0; 
                                        ?>
                                        @forelse($arrayGas as $gassummary) 
                                            <tr>
                                                <td>{{$gassummary[0]}}</td>
                                                <td class="text-right" >{{number_format($gassummary[2],3)}}</td>
                                                <td class="text-right" >{{$gassummary[1]}}</td>
                                                <td class="text-right" >{{number_format($gassummary[1] * $gassummary[2], 3)}}</td>
                                            </tr>
                                            <?php 
                                                $gasAmount = $gassummary[1] * $gassummary[2];
                                                $totalAmountSummary = $totalAmountSummary + $gasAmount;?>
                                        @empty
                                        <tr>
                                        <td colspan="4" id=""><em>No Record</em></td>
                                        </tr>
                                        @endforelse
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Total Sales on Pump</strong>  </td>
                                            <td class="text-right" >{{number_format($totalAmountSummary, 3)}}</td>
                                        </tr>
                                        <?php 
                                        $amountSummary = $totalAmountSummary - $totalCredit;
                                        ?>
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Cash</strong>  </td>
                                            <td class="text-right" >
                                                <?php 
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
                                                ?>
                                                {{number_format($cash_deduct_petty_cash, 3)}}</td>
                                        </tr>
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Credit</strong>  </td>
                                            <td class="text-right" >{{number_format($totalCredit, 3)}}</td>
                                        </tr>
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Total Sales</strong>  </td>
                                            <td class="text-right" >{{number_format($totalCredit + $cash_deduct_petty_cash, 3)}}</td>
                                        </tr>
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Discount</strong>  </td>
                                            <td class="text-right" >{{number_format($totalDiscount, 3)}}</td>
                                        </tr>
                                        @if(count($data_star_cards))
                                            <tr >
                                                <td class="text-right" colspan="3" align="right"><strong>Starcard</strong>  </td>
                                                <td class="text-right" >{{number_format($card_amount, 3)}}</td>
                                            </tr>
                                        @endif
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Others</strong>  </td>
                                            <td class="text-right" >{{number_format($totalOthers, 3)}}</td>
                                        </tr>
                                        <!--<tr >-->
                                        <!--    <td class="text-right" colspan="3" align="right"><strong>BIR Form</strong>  </td>-->
                                        <!--    <td class="text-right" >{{number_format($totalOthers, 3)}}</td>-->
                                        <!--</tr>-->
                                        <tr >
                                            <td class="text-right" colspan="3" align="right"><strong>Total Amount</strong>  </td>
                                            <td class="text-right" >{{number_format($totalAmountSummary, 3)}}</td>
                                        </tr>
                                        </tbody>
                                    </table> 
                            </div>
                        </div>
                    </div>
                    <div class="row no-print">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4"><button onclick="window.print();" class="btn btn-info btn-lg"> <i class="fa fa-print"></i> Print</button>
                        <a href="/incharge/dashboard" class="btn btn-success btn-lg btn-back"> <i class="fa fa-reply"></i> Back</a>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                    
                </div><!--col-->
            </div>
        </div><!--col-->
    </div><!--row-->
            </div>
        </div>
    </div><!--row-->
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/dashboardscript.js') }}"></script>

@endsection
