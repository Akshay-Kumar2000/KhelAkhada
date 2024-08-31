@extends('layouts.front.front')
@section('content')
	<section>
      <div class="row">
        <div class="col-md-12 how-to-play-content">
          <h1>HOW TO PLAY</h1>
          <div class="about-paragraph">
            <p>Ludo is a strategy board game for two to four players, in which the players race their four tokens from
              start to finish
              according to the rolls of a single die. Like other cross and circle games, Ludo is derived from the Indian
              game Pachisi,
              but simpler.</p>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row text-center">
          <div class="col-md-4 how-to-play-icon">
            <img src="{{ asset('front/images/htp-1.png')}}">
          </div>
          <div class="col-md-4 how-to-play-icon">
            <img src="{{ asset('front/images/htp-2.png')}}">
          </div>
          <div class="col-md-4 how-to-play-icon">
            <img src="{{ asset('front/images/htp-3.png')}}">
          </div>
        </div>
      </div>
    </section>
@endsection

