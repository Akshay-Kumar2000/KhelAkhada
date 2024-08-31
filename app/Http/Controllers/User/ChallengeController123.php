<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Challenge;
use App\User;
use App\PlayerUsername;
use App\ChallengeJoin;
use App\Transaction;
use App\Setting;
use App\RoomCode;
use Auth;
use DB;
use Http;
use URL;

use GuzzleHttp\Client;

class ChallengeController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//         $client = new Client();

        $user_id            =   Auth::user()->id;
        //echo $user_id; die;
        $myChallenges       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $challenges         =   DB::select("SELECT * FROM challenges WHERE NOT (c_id = ".$user_id." and o_id = ".$user_id.") AND STATUS=1 and deleted_at IS NULL ORDER BY id ASC");
        //$challenges       =   DB::select("select * from challenges where status = 1 and c_id != ".$user_id." and o_id != ".$user_id." and deleted_at is null ORDER BY id ASC");
        $myPlayChallenges   =   DB::select("select * from challenges where ((status = 3 or status = 4 or status = 5) and c_id = ".$user_id." ) OR ((status = 4 or status = 5) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $playChallenges     =   DB::select("SELECT * from challenges WHERE NOT (c_id = ".$user_id." or o_id = ".$user_id.") and (status = 2 or status = 3 or status = 4 or status =5 ) and deleted_at is null ORDER BY id ASC");

        //echo "<pre>";print_r($myPlayChallenges); die;
        //$playChallenges =   DB::select("select * from challenges where status != 0 and status != 1 and c_id != ".$user_id." and o_id != ".$user_id." and deleted_at is null ORDER BY amount ASC");
        // $myChallenges   =   Challenge::where('status','!=',0)->where(function($query){
        //     return $query
        //     ->where('c_id',Auth::user()->id)
        //     ->orWhere('o_id',Auth::user()->id);
        // })->orderBy('amount','asc')->get();
        // $challenges     =   Challenge::where('status',1)->where(function($query){
        //     return $query
        //     ->where('c_id','!=',Auth::user()->id)
        //     ->orWhere('o_id','!=',Auth::user()->id);
        // })->orderBy('amount','asc')->get();
        //echo "<pre>";print_r($myPlayChallenges);die;
        // $playChallenges =   Challenge::where('status','!=',0)->where('status','!=',1)
        // ->where(function($query){
        //     return $query
        //     ->where('c_id','!=',Auth::user()->id)
        //     ->orWhere('o_id','!=',Auth::user()->id);
        // })->orderBy('amount','asc')->get();
        return view('user.challenges',compact('challenges','playChallenges','myChallenges','myPlayChallenges'));
    }
    public function ajax_chalange(){


					$url = URL::to('../public/').'/';
					$wurl = URL::to('/').'/';


        $user_id            =   Auth::user()->id;
        $myChallenges       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");

         $output= '';
        foreach($myChallenges as $key => $mval) {

                    if($mval->amount == 50){
                        $a_amount   =   5;
                    }elseif($mval->amount > 50 && $mval->amount <=250){
                        $a_amount   =   10/100*($mval->amount);
                    }elseif($mval->amount > 250 && $mval->amount <=500){
                        $a_amount   =   25;
                    }elseif($mval->amount > 500){
                        $a_amount   =   5/100*($mval->amount);
                    }
                    $prize  =   (2 * $mval->amount) - $a_amount;

                if($mval->status == 1 && $mval->c_id == Auth::user()->id){


           $output='<div id="p1">
                    <div class="betCard mt-1" id="chdiv-'.$mval->id.'">
                        <div class="d-flex">
                          <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR
                          <img class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$mval->amount.'</span>
                            <div class="betCard-title d-flex align-items-center text-uppercase">
                                <span class="ml-auto" id='.$mval->id.'-buttons">
                                    <button class="btn btn-danger px-3 btn-sm" onclick="cancelChallengeCre('.$mval->id.')">DELETE</button>
                                </span>
                            </div>
                        </div>
                        <div class="py-1 row">
                            <div class="pr-3 text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mval->cname.'</span></div>
                            </div>
                            <div class="pr-3 text-center col-2 cxy">
                            <div>
                            <img src="'.$url.'front/images/vs.png" width="15px" alt=""></div>
                            </div>
                            <div class="text-center col-5">
                            <div class="pl-2">
                            <img class="border-50" id="'.$mval->id.'-loading" src="'.$url.'front/images/small-loading.gif" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName" id="'.$mval->id.'-finding">Finding Player</span></div>
                            </div>
                        </div>
                    </div>
            </div>';

                }elseif($mval->status == 1 && $mval->o_id == Auth::user()->id){
                $output = ' <div id="p2">
                          <div id="chdiv-'.$mval->id.'" class="betCard mt-1">
                              <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM '.$mval->cname.' </span>
                              <div class="d-flex pl-3">
                                  <div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
                                  <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$mval->amount.'</span>
                                  </div>
                                  </div>
                                  <div><span class="betCard-subTitle">Prize</span>
                                  <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$prize.'</span>
                                  </div>
                                  </div><button id='.$mval->id.'-play" class="bg-secondary playButton cxy" onclick="playChallenge('.$mval->id.');">Play</button>
                              </div>
                          </div>
                </div>';

                  }elseif($mval->status ==2 && $mval->c_id == Auth::user()->id){

                     $output = '<div id="p3">
                    <div class="betCard mt-1" id="chdiv-'.$mval->id.'">
                        <div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
                                class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$prize.'</span>
                            <div class="betCard-title d-flex align-items-center text-uppercase">
                                <span class="ml-auto" id="'.$mval->id.'-buttons">

                                    <button id="'.$mval->id.'-accept" class="btn btn-success px-3 btn-sm" style="cursor: pointer;width: 65px;float:left;height: 31px; position:relative;"
                                     onclick="acceptChallenge('.$mval->id.')"><a href="'.$wurl.'challenge-detail/'.$mval->id.'"  class="btn btn-info px-3 btn-sm" style="position:absolute; top:0; left:-3px; width:100%; background:none; border:none;">START</a></button>

                                    <button id="'.$mval->id.'-deny" class="btn btn-danger px-3 btn-sm" style="cursor: pointer;float: right;width: 72px;height: 31px;"
                                     onclick="denyChallenge('.$mval->id.')">REJECT</button>
                                </span>
                            </div>
                        </div>
                        <div class="py-1 row">
                            <div class="pr-3 text-center col-5">
                            <div class="pl-2"><img class="border-50" src=""'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mval->cname.'</span></div>
                            </div>
                            <div class="pr-3 text-center col-2 cxy">
                            <div><img src="'.$url.'front/images/vs.png" width="15px" alt=""></div>
                            </div>
                            <div class="text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mval->oname.'</span></div>
                            </div>
                        </div>
                    </div>
          </div>';

                }elseif($mval->status ==2 && $mval->o_id == Auth::user()->id){

          $output= '<div id="p4">
                    <div id="chdiv-'.$mval->id.'" class="betCard mt-1">
                        <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">You </span></span>
                        <div class="d-flex pl-3">
                            <div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$mval->amount.'</span>
                            </div>
                            </div>
                            <div><span class="betCard-subTitle">Prize</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$prize.'</span>
                            </div>
                            </div>
                            <button id="'.$mval->id.'-requested" class="bg-warning playButton cxy" onclick="cancelChallengeReq('.$mval->id.')">Requested</button>
                        </div>
                    </div>
          </div>';
                }elseif($mval->status == 3 && $mval->o_id == Auth::user()->id){

         $output= '  <div id="p5">
                    <div id="chdiv-'.$mval->id.'" class="betCard mt-1">
                        <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">'.$mval->cname.' </span></span>
                        <div class="d-flex pl-3">
                            <div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$mval->amount.'</span>
                            </div>
                            </div>
                            <div><span class="betCard-subTitle">Prize</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$prize.'</span>
                            </div>
                            </div>
                            <a href="'.$wurl.'challenge-detail/'.$mval->id.'"  class="btn btn-info px-3 btn-sm" style="position:absolute; right:10px; background-color:green;">START</a>

                        </div>
                    </div>
          </div>';
                }
            }
        $challenges         =   DB::select("SELECT * FROM challenges WHERE NOT (c_id = ".$user_id." and o_id = ".$user_id.") AND STATUS=1 and deleted_at IS NULL ORDER BY id ASC");

        $output2='';
        foreach($challenges as $key => $val)    {
                    if($val->amount == 50){
                        $a_amount   =   5;
                    }elseif($val->amount > 50 && $val->amount <=250){
                        $a_amount   =   10/100*($val->amount);
                    }elseif($val->amount > 250 && $val->amount <=500){
                        $a_amount   =   25;
                    }elseif($val->amount > 500){
                        $a_amount   =   5/100*($val->amount);
                    }
                    $prize  =   (2 * $val->amount) - $a_amount;

        if($val->c_id == Auth::user()->id){

       $output2 ='<div id="p6">
                    <div id="chdiv-'.$val->id.'" class="betCard mt-1">
                        <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">You </span></span>
                        <div class="d-flex pl-3">
                            <div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$val->amount.'</span>
                            </div>
                            </div>
                            <div><span class="betCard-subTitle">Prize</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$prize.'</span>
                            </div>
                            </div>
                            <span class="ml-auto" id="'.$val->id.'-buttons">
                                <button class="bg-danger playButton cxy" onclick="cancelChallengeCre('.$val->id.')">Cancel</button>
                            </span>
                        </div>
                    </div>
        </div>';
        }elseif($val->c_id != Auth::user()->id){

        $output2 ='<div id="p7">
                    <div id="chdiv-'.$val->id.'" class="betCard mt-1">
                        <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">'.$val->cname.' </span></span>
                        <div class="d-flex pl-3">
                            <div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$val->amount.'</span>
                            </div>
                            </div>
                            <div><span class="betCard-subTitle">Prize</span>
                            <div><img src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt=""><span class="betCard-amount">'.$prize.'</span>
                            </div>
                            </div><button id="'.$val->id.'-play" class="bg-secondary playButton cxy" onclick="playChallenge('.$val->id.');">Play</button>
                        </div>
                    </div>
        </div>
            </div>';
        }else   {}
            }
        $myPlayChallenges   =   DB::select("select * from challenges where ((status = 3 or status = 4) and c_id = ".$user_id." ) OR ((status = 4) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
                // $myPlayChallenges   =   DB::select("select * from challenges where ((status = 3 or status = 4 or status = 5) and c_id = ".$user_id." ) OR ((status = 4 or status = 5) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");

      $output3 =' ';
      foreach($myPlayChallenges as $mpid    =>  $mpval) {



                    if($mpval->amount == 50){
                        $a_amount   =   5;
                    }elseif($mpval->amount > 50 && $mpval->amount <=250){
                        $a_amount   =   10/100*($mpval->amount);
                    }elseif($mpval->amount > 250 && $mpval->amount <=500){
                        $a_amount   =   25;
                    }elseif($mpval->amount > 500){
                        $a_amount   =   5/100*($mpval->amount);
                    }
                    $prize  =   (2 * $mpval->amount) - $a_amount;

             $output3 ='
                    <div class="betCard mt-1" id="myplaying-chdiv-'.$mpval->id.'" >
                        <div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
                                class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$prize.'</span>
                            <div class="betCard-title d-flex align-items-center text-uppercase"><span class="ml-auto">
                                <a href="'.$wurl.'challenge-detail/'.$mpval->id.'"  class="btn btn-info px-3 btn-sm" >View</a>
                            </span></div>
                        </div>
                        <div class="py-1 row">
                            <div class="pr-3 text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->cname.'</span></div>
                            </div>
                            <div class="pr-3 text-center col-2 cxy">
                            <div><img src="'.$url.'front/images/vs.png" width="15px" alt=""></div>
                            </div>
                            <div class="text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->oname.'</span></div>
                            </div>
                        </div>
                    </div>
          ';
      }

      $playChallenges     =   DB::select("SELECT * from challenges WHERE NOT (c_id = ".$user_id." or o_id = ".$user_id.") and (status = 2 or status = 3 or status = 4 or status =5 ) and deleted_at is null ORDER BY id ASC");
        $output4 = '';
      foreach($playChallenges as $pid   =>  $pval){


                if($pval->amount == 50){
                    $a_amount   =   5;
                }elseif($pval->amount > 50 && $pval->amount <=250){
                    $a_amount   =   10/100*($pval->amount);
                }elseif($pval->amount > 250 && $pval->amount <=500){
                    $a_amount   =   25;
                }elseif($pval->amount > 500){
                    $a_amount   =   5/100*($pval->amount);
                }
                $prize  =   (2 * $pval->amount) - $a_amount;
    $output4 = '
            <div class="betCard mt-1" id="playing-chdiv-'.$pval->id.'">
            <div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
                    class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$pval->amount.'</span>
                <div class="betCard-title d-flex align-items-center text-uppercase"><span class="ml-auto mr-3">PRIZE<img
                    class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$prize.'</span></div>
            </div>
            <div class="py-1 row">
                <div class="pr-3 text-center col-5">
                <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                    alt=""></div>
                <div style="line-height: 1;"><span class="betCard-playerName">'.$pval->cname.'</span></div>
                </div>
                <div class="pr-3 text-center col-2 cxy">
                <div><img src="'.$url.'front/images/vs.png)}}" width="15px" alt=""></div>
                </div>
                <div class="text-center col-5">
                <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                    alt=""></div>
                <div style="line-height: 1;"><span class="betCard-playerName">'.$pval->oname.'</span></div>
                </div>
            </div>
            </div>
           ';

            }

        $disPlayChallenges   =   DB::select("select * from challenges where (status=5 and c_id = ".$user_id." ) OR (status = 5 and  o_id = ".$user_id.") and deleted_at is null ORDER BY id DESC");
        $output5='';

        foreach($disPlayChallenges as $mpid    =>  $mpval) {



                    if($mpval->amount == 50){
                        $a_amount   =   5;
                    }elseif($mpval->amount > 50 && $mpval->amount <=250){
                        $a_amount   =   10/100*($mpval->amount);
                    }elseif($mpval->amount > 250 && $mpval->amount <=500){
                        $a_amount   =   25;
                    }elseif($mpval->amount > 500){
                        $a_amount   =   5/100*($mpval->amount);
                    }
                    $prize  =   (2 * $mpval->amount) - $a_amount;

             $output5 ='
                    <div class="betCard mt-1" id="myplaying-chdiv-'.$mpval->id.'" >
                        <div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
                                class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$prize.'</span>
                            <div class="betCard-title d-flex align-items-center text-uppercase"><span class="ml-auto">
                                <a href="'.$wurl.'challenge-detail/'.$mpval->id.'"  class="btn btn-info px-3 btn-sm" >View</a>
                            </span></div>
                        </div>
                        <div class="py-1 row">
                            <div class="pr-3 text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->cname.'</span></div>
                            </div>
                            <div class="pr-3 text-center col-2 cxy">
                            <div><img src="'.$url.'front/images/vs.png" width="15px" alt=""></div>
                            </div>
                            <div class="text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->oname.'</span></div>
                            </div>
                        </div>
                    </div>
          ';
      }

      $completeChallenges   =   DB::select("select * from challenges where (status=0 and c_id = ".$user_id." ) OR (status = 0 and  o_id = ".$user_id.") and deleted_at is null ORDER BY id DESC");
        $output6='';

        foreach($disPlayChallenges as $mpid    =>  $mpval) {



                    if($mpval->amount == 50){
                        $a_amount   =   5;
                    }elseif($mpval->amount > 50 && $mpval->amount <=250){
                        $a_amount   =   10/100*($mpval->amount);
                    }elseif($mpval->amount > 250 && $mpval->amount <=500){
                        $a_amount   =   25;
                    }elseif($mpval->amount > 500){
                        $a_amount   =   5/100*($mpval->amount);
                    }
                    $prize  =   (2 * $mpval->amount) - $a_amount;

             $output6 ='
                    <div class="betCard mt-1" id="myplaying-chdiv-'.$mpval->id.'" >
                        <div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
                                class="mx-1" src="'.$url.'front/images/global-rupeeIcon.png" width="21px" alt="">'.$prize.'</span>
                            <div class="betCard-title d-flex align-items-center text-uppercase"><span class="ml-auto">
                                <!--<a href="'.$wurl.'challenge-detail/'.$mpval->id.'"  class="btn btn-info px-3 btn-sm" >View</a> -->
                            </span></div>
                        </div>
                        <div class="py-1 row">
                            <div class="pr-3 text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->cname.'</span></div>
                            </div>
                            <div class="pr-3 text-center col-2 cxy">
                            <div><img src="'.$url.'front/images/vs.png" width="15px" alt=""></div>
                            </div>
                            <div class="text-center col-5">
                            <div class="pl-2"><img class="border-50" src="'.$url.'front/images/author.svg" width="21px" height="21px"
                                alt=""></div>
                            <div style="line-height: 1;"><span class="betCard-playerName">'.$mpval->oname.'</span></div>
                            </div>
                        </div>
                    </div>
          ';
      }

      return response()->json(array('myChallenges' =>  $output,'challenges' =>  $output2,'myPlayChallenges' =>$output3 ,'playChallenges' =>$output4, 'disPlayChallenges' =>$output5,'completeChallenges' => $output6 ));


    }
    public function ajax_open_battle(){

         $user_id            =   Auth::user()->id;
        //echo $user_id; die;
        $myChallenges       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $challenges         =   DB::select("SELECT * FROM challenges WHERE NOT (c_id = ".$user_id." and o_id = ".$user_id.") AND STATUS=1 and deleted_at IS NULL ORDER BY id ASC");
        //$challenges       =   DB::select("select * from challenges where status = 1 and c_id != ".$user_id." and o_id != ".$user_id." and deleted_at is null ORDER BY id ASC");
        $myPlayChallenges   =   DB::select("select * from challenges where ((status = 3 or status = 4 or status = 5) and c_id = ".$user_id." ) OR ((status = 4 or status = 5) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $playChallenges     =   DB::select("SELECT * from challenges WHERE NOT (c_id = ".$user_id." or o_id = ".$user_id.") and (status = 2 or status = 3 or status = 4 or status =5 ) and deleted_at is null ORDER BY id ASC");


        return view('user.ajax_challenges',compact('challenges','playChallenges','myChallenges','myPlayChallenges'));
    }

    public function ajax_running_battle(){

           $user_id            =   Auth::user()->id;
        //echo $user_id; die;
        $myChallenges       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $challenges         =   DB::select("SELECT * FROM challenges WHERE NOT (c_id = ".$user_id." and o_id = ".$user_id.") AND STATUS=1 and deleted_at IS NULL ORDER BY id ASC");
        //$challenges       =   DB::select("select * from challenges where status = 1 and c_id != ".$user_id." and o_id != ".$user_id." and deleted_at is null ORDER BY id ASC");
        $myPlayChallenges   =   DB::select("select * from challenges where ((status = 3 or status = 4 or status = 5) and c_id = ".$user_id." ) OR ((status = 4 or status = 5) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $playChallenges     =   DB::select("SELECT * from challenges WHERE NOT (c_id = ".$user_id." or o_id = ".$user_id.") and (status = 2 or status = 3 or status = 4 or status =5 ) and deleted_at is null ORDER BY id ASC");


        return view('user.ajax_running_battle',compact('challenges','playChallenges','myChallenges','myPlayChallenges'));
    }
    public function challengeListing(Request $request)
    {
        $user_id            =   Auth::user()->id;
        //echo $user_id; die;
        $data['myChallenges']       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $data['challenges']         =   DB::select("SELECT * FROM challenges WHERE NOT (c_id = ".$user_id." and o_id = ".$user_id.") AND STATUS=1 and deleted_at IS NULL ORDER BY id ASC");

        //$data['myPlayChallenges']   =   DB::select("select * from challenges where (status = 3 or status = 4) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
        $data['myPlayChallenges']   =   DB::select("select * from challenges where ((status = 3 or status = 4 or status = 5) and c_id = ".$user_id." ) OR ((status = 4 or status = 5) and  o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");

        return response([
            'data'        => $data
        ],200);
    }

    public function create(Request $request)
    {
	   $user_id            =   Auth::user()->id;

        $request->validate([
            'amount' => 'required|numeric|gt:29'
        ]);

        $chk    =   $this->checkBalance($request->amount);
        if(!$chk){
            return response([
                'message'        => "Insufficient balance, Please recharge your wallet!"
            ],400);
        }
		$myChallenges       =   DB::select("select * from challenges where (status = 1 or status = 2 or status = 3) and (c_id = ".$user_id." OR o_id = ".$user_id.") and deleted_at is null ORDER BY id ASC");
		if(count($myChallenges)>0){
			      return response([
                'message'        => "Already Running Game"
            ],400);


		}

        try
        {
            //$cname                          =   $this->randomPlayer();
            $challenge  =   Challenge::create([
                        'c_id'              => Auth::user()->id,
                        'cname'             => Auth::user()->username,
                        'amount'            => $request->amount,
                        'ip'                => $request->ip(),
                    ]);

            if($challenge){

              $walletData =   User::find(Auth::user()->id);
            //   $walletData->decrement('wallet',$request->amount);

                return response()->json(['data'=>$challenge]);
            }
            return response([
                'message'        => "Unable to create challenge!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }
    }

    public function cancelChallenge(Request $request)
    {
        $request->validate([
            'ch_id' => 'required|numeric'
        ]);
        try
        {
             $user_id    =   Auth::user()->id;
            $chData     =   Challenge::find($request->ch_id);
            if($chData->status  ==  1){
                $walletData =   User::find($user_id);
                // $walletData->wallet = $walletData->wallet + $chData->amount;
                $walletData->save();
                $chData->delete();
                return response()->json(['data'=>$request->ch_id]);
            }
            return response([
                'message'        => "Unable to cancel the game!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    public function playChallenge(Request $request)
    {
        $request->validate([
            'ch_id' => 'required|numeric'
        ]);

        try
        {
            $user_id    =   Auth::user()->id;
            $chData     =   Challenge::find($request->ch_id);


            $chk        =   $this->checkBalance($chData->amount);
            if(!$chk){
                return response([
                    'message'        => "Insufficient balance, Please recharge your wallet!"
                ],400);
            }


            if($user_id == $chData->c_id){
                return response([
                    'message'        => "You cannot accept the game which is created by you!"
                ],400);
            }

            if($chData->status  ==  1){
                ChallengeJoin::create([
                    'user_id'       =>   $user_id,
                    'ch_id'         =>   $request->ch_id,
                    'ip'            =>   $request->ip(),
                ]);

                $chData->status =   2;
                $chData->o_id   =   $user_id;
                $chData->oname  =   Auth::user()->username;
                $chData->save();

                return response()->json(['data'=>$chData]);
            }
            return response([
                'message'        => "Unable to play the game!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    public function cancelChallengeReq(Request $request)
    {
        $request->validate([
            'ch_id' => 'required|numeric'
        ]);
        try
        {
            $chData     =   Challenge::find($request->ch_id);
            $user_id    =   Auth::user()->id;
            if($chData->status  ==  2){

                $chData->status =   1;
                $chData->o_id   =   NULL;
                $chData->oname  =   NULL;
                $chData->save();


                return response()->json(['data'=>$chData]);
            }
            return response([
                'message'        => "Unable to cancel request!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    public function acceptChallenge(Request $request)
    {
        $request->validate([
            'ch_id' => 'required|numeric'
        ]);

        $chData     =   Challenge::find($request->ch_id);
        // $chk        =   $this->checkBalance($chData->amount);
        // if(!$chk){
        //     return response([
        //         'message'        => "Insufficient balance, Please recharge your wallet!"
        //     ],400);
        // }

        $roomcode   =   0;

        try
        {
      $resp = file_get_contents('https://ludokingroomcode.shop/api.php?user=Sofitgrow');
      $obj = json_decode($resp);
	  $token = $obj->roomcode;
            $settingData    =   Setting::find(1);
            if($settingData->field_value == 1 ){

                $roomcode   =  $token;
            }

            $user_id    =   Auth::user()->id;
            if($chData->status  ==  2){
                    $user_data = User::where('id', Auth::user()->id)->first();
                    $wallet = $user_data->wallet;

                $txn    =   Transaction::create([
                    'user_id'       =>  $user_id,
                    'source_id'     =>  $request->ch_id,
                    'amount'        =>  $chData->amount,
                    'status'        =>  'Create',
                    'remark'        =>  'Creator accept opp. request',
                    'ip'            =>  $request->ip(),
                    'closing_balance' =>  $wallet,
                ]);

                if($txn){
                    $chData->status =   3;
                    $chData->rcode  =   $roomcode;
                    $chData->save();

                    $walletData =   User::find($user_id);
                    $walletData->decrement('wallet',$chData->amount);
                    return response()->json(['data'=>$chData]);
                }
                return response([
                    'message'        => "Unable to accept the game!"
                ],400);
            }
            return response([
                'message'        => "Unable to accept the game!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    public function startChallenge(Request $request)
    {
        $request->validate([
            'ch_id' => 'required|numeric'
        ]);

        $chData     =   Challenge::find($request->ch_id);
        $chk        =   $this->checkBalance($chData->amount);
        if(!$chk){
            return response([
                'message'        => "Insufficient balance, Please recharge your wallet!"
            ],400);
        }

        try
        {
            $user_id    =   Auth::user()->id;
            if($chData->status  ==  3){
                    $user_data = User::where('id', Auth::user()->id)->first();
                    $wallet = $user_data->wallet;

                $txn    =   Transaction::create([
                    'user_id'       =>  $user_id,
                    'source_id'     =>  $request->ch_id,
                    'amount'        =>  $chData->amount,
                    'status'        =>  'Play',
                    'remark'        =>  'Opponent start the game',
                    'ip'            =>  $request->ip(),
                    'closing_balance' =>  $wallet-$chData->amount,

                ]);

                if($txn){
                    $chData->status =   4;
                    $chData->save();

                    $walletData =   User::find($user_id);
                    $walletData->decrement('wallet',$chData->amount);
                    return response()->json(['data'=>$chData]);
                }
                return response([
                    'message'        => "Unable to start the game!"
                ],400);
            }
            return response([
                'message'        => "Unable to start the game!"
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    private function randomPlayer(){
        $pStrId =   rand(1,740);
        $pData  =   PlayerUsername::find($pStrId);
        //echo "<pre>";print_r($pData->username);die;
        return $pData->username;
    }

    private function checkBalance($amount){
        $user_id    =   Auth::user()->id;
        $usrData    =   User::find($user_id);
        $balance    =   $usrData->wallet;
        $creGames   =   Challenge::where('c_id',$user_id)->where(function($query){
            return $query
            ->where('status',1)
            ->orWhere('status',2);
        })->sum('amount');

        $oppGames   =   Challenge::where('o_id',$user_id)->where(function($query){
            return $query
            ->where('status',1)
            ->orWhere('status',2);
        })->sum('amount');

        $userData   =   User::find($user_id);

        if($userData->wallet >= ($creGames + $oppGames + $amount)){
            return true;
        }

        return false;
    }

}
