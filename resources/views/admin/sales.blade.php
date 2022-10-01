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
                        <td>
                        <a href="/admin/view-record/{{$Report->sessionrecord}}" class="btn btn-info"><i class="fa fa-search"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div><!--x_content-->
        </div><!--col-->
        
    </div><!--row-->
</div>
@endsection