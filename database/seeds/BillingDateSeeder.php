<?php

use Illuminate\Database\Seeder;
use App\Billing_date;

class BillingDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Billing_date::truncate();
        $y=1;
        $num_term = 120;
        //start date
        $month_sched = date("2020-06-01");
        while($y <= $num_term) {
            //15th
            $month_line_15 = strtotime($month_sched." +14 day");
            $month_line_15_start = strtotime($month_sched);
            $month_line_15_end = strtotime($month_sched." +14 day");

            $month_line_last = strtotime($month_sched." next month - 1 hour");
            $month_line_last_start = strtotime($month_sched." +15 day");
            //$month_line_last_end = strtotime($month_line_last);

            $billing_date = new Billing_date();
            $billing_date->billingdate = date("M-d-Y", $month_line_15);
            $billing_date->fromdate = date("m-d-Y",$month_line_15_start);
            $billing_date->todate = date("m-d-Y",$month_line_15_end);
            $billing_date->status = 'not set';
            $billing_date->save();

            $billing_date_last = new Billing_date();
            $billing_date_last->billingdate = date("M-d-Y", $month_line_last);
            $billing_date_last->fromdate = date("m-d-Y",$month_line_last_start);
            $billing_date_last->todate = date("m-d-Y",$month_line_last);
            $billing_date_last->status = 'not set';
            $billing_date_last->save();
            //last day of month
    
            $month_sched = date("Y-m-d",strtotime($month_sched." +1month"));
            $y++;
        }
    }
}
