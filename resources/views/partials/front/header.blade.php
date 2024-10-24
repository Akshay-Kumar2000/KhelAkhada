<div class="headerContainer">
    @if (isset(Auth::user()->id))
        <a id="menu-toggle" class="cxy h-100">
            <div class="sideNavIcon ml-0">
                <img src="{{ asset('front/images/sidebar.png') }}" alt="">
            </div>
        </a>
    @endif
    <a href="{{ url('/') }}">
        <div class="ml-0 navLogo d-flex" style=".ml-1 {
  margin-left: ($spacer * .5) !important;
}">
            <img src="{{ asset('front/images/khelmoj123.jpeg') }}" alt="" style="width: 65px;">
        </div>
    </a>
    <script type="text/javascript">
        $(document).ready(function() {
            //     setInterval(function () {
            //     //   $("#wallet").load(window.location.href + " #wallet" );
            //   }, 1000);
        });
    </script>
    @if (isset(Auth::user()->id))
        <div>
            <div class="menu-items">

                <a class="box" href="{{ url('add-money') }}">
                    <picture class="moneyIcon-container"><img src="{{ asset('front/images/global-rupeeIcon.png') }}"
                            alt=""></picture>
                    <div class="mt-1 ml-1">
                        <div class="moneyBox-header">Cash</div>
                        <div id="wallet">
                            <div class="moneyBox-text">{{ number_format(Auth::user()->wallet, 2) }}</div>
                        </div>
                    </div>
                    <picture class="moneyBox-add"><img src="{{ asset('front/images/global-addSign.png') }}"
                            alt=""></picture>
                </a>
            </div><span class="mx-5"></span>
        </div>
    @else
        <div class="menu-items">
            <a type="button" class="login-btn" href="{{ url('login') }}">SIGNUP</a>
            <a type="button" class="login-btn" href="{{ url('login') }}">LOGIN</a>
        </div>
        <span class="mx-5"></span>
    @endif

</div>
