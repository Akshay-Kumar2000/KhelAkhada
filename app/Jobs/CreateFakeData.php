<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Fakechallenge;
use Carbon\Carbon;

class CreateFakeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // echo Carbon::now();
        $fakedata = 50;
        $fakename = [
        "Aarav Sharma",
        "Aditi Verma",
        "Alok Kapoor",
        "Amrita Desai",
        "Aniket Patel",
        "Ananya Singh",
        "Arjun Yadav",
        "Asha Gupta",
        "Ayush Kapoor",
        "Bhavya Sharma",
        "Chetan Verma",
        "Deepika Singh",
        "Dhruv Yadav",
        "Divya Patel",
        "Gaurav Kumar",
        "Geeta Desai",
        "Hari Gupta",
        "Ishaan Kapoor",
        "Jyoti Verma",
        "Kabir Sharma",
        "Kavita Singh",
        "Krishna Yadav",
        "Lavanya Patel",
        "Manish Desai",
        "Meera Kumar",
        "Mohit Verma",
        "Neha Sharma",
        "Nikhil Singh",
        "Nisha Yadav",
        "Pankaj Gupta",
        "Pooja Kapoor",
        "Pranav Patel",
        "Prisha Desai",
        "Rahul Kumar",
        "Riya Verma",
        "Rohan Sharma",
        "Sakshi Singh",
        "Sameer Yadav",
        "Shikha Gupta",
        "Shiv Kapoor",
        "Shreya Patel",
        "Snehal Desai",
        "Sumit Kumar",
        "Tanvi Verma",
        "Utkarsh Sharma",
        "Vaishali Singh",
        "Varun Yadav",
        "Vidya Patel",
        "Vijay Gupta",
        "Yash Kapoor",
        "Zara Desai",
    ];
        $amountstart = 1000;
        $amountend = 10000;
        for($i=0;$i<rand(1,3);$i++){
            $amount = $this->generateRandomMultiples(10, $amountstart, $amountend);
            $name = $fakename[array_rand($fakename)];
            $name2 = $fakename[array_rand($fakename)];
            $exist = Fakechallenge::where('status',1)->where('amount','>',1000)->count();
            if($exist >= 5){
                $amount = $this->generateRandomMultiples(10, 100, 500);
            }
            $d = new Fakechallenge;
            $d->amount = $amount;
            $d->c_id = 11;
            $d->cname = $name;
            $d->o_id = 11;
            $d->oname = $name2;
            $d->ip = 11111111111;
            // $d->save();
        }
        $lastdata = Fakechallenge::where('status',1)->orWhere('status',2)->get();
        foreach($lastdata as $row){
            $startDate = Carbon::parse($row->created_at);
            $endDate = Carbon::now(); // Use the current date and time as an example
            $minutesDifference = $startDate->diffInMinutes($endDate);
            if($row->status > 1){
                if($minutesDifference >= 7){
                    Fakechallenge::where('id',$row->id)->delete();
                }
            }else{
                if($minutesDifference >= 1){
                    Fakechallenge::where('id',$row->id)->update(["status"=>2]);
                }
            }
        }
    }
}
