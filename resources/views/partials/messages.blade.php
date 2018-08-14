@if ($errors->any())
    <div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->exists('gw_message_text'))
    <div class="alert alert-{{session()->get('gw_message_type','warning')}}">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session()->get('gw_message_text', 'Could not retrieve message. Error 8LeDJ87SYpiVBcxn.') }}
    </div>	
@endif