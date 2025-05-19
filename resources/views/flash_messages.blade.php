@if(Session::has('flash_message_error'))
<div class="alert alert-danger">

    <strong> {!! session('flash_message_error') !!} </strong>
</div>

@endif
@if(Session::has('flash_message_success'))
<div class="alert alert-success">

    <strong> {!! session('flash_message_success') !!} </strong>
</div>
@endif

@if(Session::has('flash_message_warning'))
<div class="alert alert-warning">

    <strong> {!! session('flash_message_warning') !!} </strong>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
