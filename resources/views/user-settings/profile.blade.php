@extends('layouts.dashboard')

@section('content')
    <div class="card md:w-1/2">
        <div class="card-header">
            <p class="card-header-title">{{__('Update your profile')}}</p>
        </div>
        <div class="card-content">
            <form action="{{route('settings.profile')}}" method="POST">
                @csrf
                <div class="field">
                    <label for="name" class="label">{{__('Your name')}}</label>
                    <div class="control">
                        <input type="text" name="name" id="name" class="input @error('name') is-danger @enderror"
                               placeholder="{{__('Your name')}}" value="{{old('name', auth()->user()->name)}}">

                        @error('name')
                        <p class="help is-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="email" class="label">{{__('Your email')}}</label>
                    <div class="control">
                        <input type="email" name="email" id="email" class="input @error('email') is-danger @enderror"
                               placeholder="{{__('Your email')}}" value="{{old('email', auth()->user()->email)}}">
                        @error('email')
                        <p class="help is-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="field">
                    <button class="button is-primary">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
