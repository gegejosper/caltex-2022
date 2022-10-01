@extends('layouts.admin')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="x_panel tile">
                <div class="x_title">
                    <h3>Daily Sales Report</h3> 
                </div>
                <div class="clearfix"></div>
                <form action="{{ route('admin.reports')}}" >
                    <div class="form-group col-lg-2">
                        <select name="branch" id="branch" class="form-control">
                            <option value="all">ALL</option>
                            @foreach($dataBranch as $Branch)
                            <option value="{{$Branch->id}}">
                                {{$Branch->branchname}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <select name="month" id="month" class="form-control">
                            <option value="00">ALL</option>
                            <option value="01">January</option>
                            <option value="02">Febuary</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <select name="year" id="year" class="form-control">
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-1">
                    <button class="btn btn-primary" type="submit"> Show</button>
                    </div>
                    
                </form>
                <table class="table table-striped">
                    <tr>
                    <td>Date</td>
                    <td>Branch</td>
                    <td>Record Code</td>
                    <td>Processor</td>
                    <td>Pump Sales</td>
                    <td>Cash</td>
                    <td>Credit</td>
                    <td>Total Sales</td>
                    <td>Discount</td>
                    <td>Petty Voucher</td>
                    <td>Star Card</td>
                    <td>Total Amount</td>
                    </tr>
                    @php 
                    $total_pump_sale = 0;
                    $total_cash = 0;
                    $total_credit = 0;
                    $total_discount = 0;
                    $total_sales = 0;
                    $total_petty_voucher = 0;
                    $total_star_card = 0;
                    $total_amount = 0; 
                    @endphp
                    @foreach($dataBranchReport as $Report)
                    <tr>
                        <td><a href="/admin/view-record/{{$Report->sessionrecord}}">{{$Report->reportdate}}</a></td>
                        <td><a href="/admin/branches/{{$Report->branch->id}}">{{$Report->branch->branchname}}</a></td>
                        <td><a href="/admin/view-record/{{$Report->sessionrecord}}">{{$Report->sessionrecord}}</a></td>
                        <td>{{$Report->user->name}}</td>
                        <td>{{number_format($Report->pump_sale,3)}}</td>
                        <td>{{number_format($Report->cash,3)}}</td>
                        <td>{{number_format($Report->credit,3)}}</td>
                        <td>{{number_format($Report->total_sales,3)}}</td>
                        <td>{{number_format($Report->discount,3)}}</td>
                        <td>{{number_format($Report->petty_voucher,3)}}</td>
                        <td>{{number_format($Report->star_card,3)}}</td>
                        <td>{{number_format($Report->total_amount,3)}}</td>
                        
                    </tr>
                    @php 
                    $total_pump_sale = $total_pump_sale + $Report->pump_sale;
                    $total_cash = $total_cash + $Report->cash;
                    $total_credit = $total_credit + $Report->credit;
                    $total_discount = $total_discount + $Report->discount;
                    $total_sales = $total_sales + $Report->total_sales;
                    $total_petty_voucher = $total_petty_voucher + $Report->petty_voucher;
                    $total_star_card = $total_star_card + $Report->star_card;
                    $total_amount = $total_amount + $Report->total_amount; 
                    @endphp
                    @endforeach
                    <tr>
                    <td colspan="4" class="text-right"> <strong>Total</strong> </td>
                    <td><strong>{{number_format($total_pump_sale,3)}}</strong></td>
                    <td><strong>{{number_format($total_cash,3)}}</strong></td>
                    <td><strong>{{number_format($total_credit,3)}}</strong></td>
                    <td><strong>{{number_format($total_discount,3)}}</strong></td>
                    <td><strong>{{number_format($total_sales,3)}}</strong></td>
                    <td><strong>{{number_format($total_petty_voucher,3)}}</strong></td>
                    <td><strong>{{number_format($total_star_card,3)}}</strong></td>
                    <td><strong>{{number_format($total_amount,3)}}</strong></td>
                    </tr>
                </table>
            </div><!--x_content-->
        </div><!--col-->
        
    </div><!--row-->
</div>
@endsection