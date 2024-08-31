@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
		<div class="row">
			<div class="col-md-12">
				<p><b>नोट : 1. </b> 01 मार्च 2022 को सभी श्रेणी के विनर खिलाड़ियों को प्राइज की राशि दी जाएगी। </p>
				<p><b>2. </b> चारों फॉर्मेट में जीतने वाले खिलाड़ी को 21000 का  मेगा बोनस दिया जायेगा।</p>
			</div>
		</div>
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Prizes</h1>
            
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
				  <tr><th colspan=4>इस लिस्ट में सबसे ज्यादा राशि के खेल खेलने वाले खिलाड़ी की गिनती की जाएगी। </th></tr>
                <tr>
                  <th>Rank</th>
                  <th>Player Name</th>
                  <th>Total Chips</th>
                  <th>Prize Amount</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($playersmax as $id => $val)
				  <?php if($id == 10) { break; } ?>
					<tr>
					  <td>{{ $id + 1}}</td>
					  <td>
						  {{ @$val['username'] }}
					  </td>
					  <td>
						  {{ @$val['count'] }}
					  </td>
					  <td>@if($id==0) 6000 @elseif($id==1) 2000 @elseif($id==2) 1000 @else 0 @endif</td>
					</tr>
                @endforeach
                <?php if(empty($playersmax)){ ?> <tr><td colspan=4 style="text-align:center">No data found</td></tr> <?php } ?>
              </tbody>
            </table>
            
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
				  <tr><th colspan=4>इस  निम्नलिखित पुरस्कार सूची में सबसे ज्यादा खेल जीतने वाले खिलाड़ी की गणना की जाएगी(जिसने 199 चिप्स से ज्यादा के गेम खेले हो)। </th></tr>
                <tr>
                  <th>Rank</th>
                  <th>Player Name</th>
                  <th>Total Games</th>
                  <th>Prize Amount</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($players100 as $id => $val)
				  <?php if($id == 10) { break; } ?>
					<tr>
					  <td>{{ $id + 1}}</td>
					  <td>
						  {{ @$val['username'] }}
					  </td>
					  <td>
						  {{ @$val['count'] }}
					  </td>
					  <td>@if($id==0) 5000 @elseif($id==1) 2000 @elseif($id==2) 1000 @else 0 @endif</td>
					</tr>
                @endforeach
                <?php if(empty($players100)){ ?> <tr><td colspan=4 style="text-align:center">No data found</td></tr> <?php } ?>
              </tbody>
            </table>
            
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
				  <tr><th colspan=4>इस निम्नलिखित पुरस्कार सूची में सबसे ज्यादा खेल खेलने वाले खिलाड़ी की गणना की जाएगी(जिसने 199 चिप्स से ज्यादा के गेम खेले हो)।</th></tr>
                <tr>
                  <th>Rank</th>
                  <th>Player Name</th>
                  <th>Total Games</th>
                  <th>Prize Amount</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($players200 as $id => $val)
				  <?php if($id == 10) { break; } ?>
                <tr>
                  <td>{{ $id + 1}}</td>
                  <td>
					  {{ @$val['username'] }}
                  </td>
                  <td>
					  {{ @$val['count'] }}
				  </td>
				  <td>@if($id==0) 5000 @elseif($id==1) 2000 @elseif($id==2) 1000 @else 0 @endif</td>
                </tr>
                @endforeach
                <?php if(empty($players200)){ ?> <tr><td colspan=4 style="text-align:center">No data found</td></tr> <?php } ?>
              </tbody>
            </table>

            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
				  <tr><th colspan=4>इस निम्नलिखित पुरस्कार सूची में सबसे ज्यादा खेल खेलने वाले खिलाड़ी की गणना की जाएगी(जिसने 30 चिप्स से 199 चिप्स के बिच के गेम खेले हो)।</th></tr>
                <tr>
                  <th>Rank</th>
                  <th>Player Name</th>
                  <th>Total Games</th>
                  <th>Prize Amount</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($players199 as $id => $val)
				  <?php if($id == 10) { break; } ?>
                <tr>
                  <td>{{ $id + 1}}</td>
                  <td>
					  {{ @$val['username'] }}
                  </td>
                  <td>
					  {{ @$val['count'] }}
				  </td>
				  <td>@if($id==0) 2000  @else 0 @endif</td>
                </tr>
                @endforeach
                <?php if(empty($players199)){ ?> <tr><td colspan=4 style="text-align:center">No data found</td></tr> <?php } ?>
              </tbody>
            </table>
            
          </div>
        </div>
      </div>
    </section>

@endsection

