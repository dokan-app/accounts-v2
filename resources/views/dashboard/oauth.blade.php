@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-4">
            <passport-clients></passport-clients>
        </div>
        <div class="mb-4">
            <passport-authorized-clients></passport-authorized-clients>
        </div>

        <passport-personal-access-tokens></passport-personal-access-tokens>
    </div>
@endsection
