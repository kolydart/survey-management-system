@extends('frontend.master')

@section('menu')
	{{-- @include('frontend.nav') --}}
@endsection

@section('content')
<div class="container">
		{!! $content !!}
    <div class="footer mr-2 mb-2 text-right" id="gw-footer">
        <div>
            {!! \gateweb\common\presenter\Laraview::signature() !!}
        </div>
        <div>
            <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/gr/"><img alt="Άδεια Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/3.0/gr/88x31.png" /></a><br />Το λογισμικό παρέχεται με άδεια <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/gr/">Creative Commons <br>Αναφορά Δημιουργού - Μη Εμπορική Χρήση - Παρόμοια Διανομή 3.0</a>
        </div>        
    </div>
		
</div>

@endsection