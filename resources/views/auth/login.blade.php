@extends('layouts.app')

@section('content')
<div class="container">
     <login-component csrf_token="{{ @csrf_token() }}"></login-component>
</div>
@endsection
