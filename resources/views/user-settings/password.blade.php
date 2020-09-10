@extends('layouts.dashboard')

@section('content')
    <div class="card md:w-1/2">
        <div class="card-header">
            <p class="card-header-title">{{__('Change your password')}}</p>
        </div>
        <div class="card-content">
            <form action="{{route('settings.password')}}" method="POST">
                @csrf
                <div class="field">
                    <label for="oldPassword" class="label">{{__('Old password')}}</label>
                    <div class="control">
                        <input type="text" name="oldPassword" id="oldPassword"
                               class="input @error('oldPassword') is-danger @enderror"
                               placeholder="{{__('Your old password')}}">
                        @error('oldPassword')
                        <p class="help is-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label for="newPassword" class="label">{{__('New password')}}</label>
                    <div class="control">
                        <input type="text" name="newPassword" id="newPassword"
                               class="input @error('newPassword') is-danger @enderror"
                               placeholder="{{__('your new password')}}">
                        @error('newPassword')
                        <p class="help is-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label for="newPassword_confirmation" class="label">{{__('New password again')}}</label>
                    <div class="control">
                        <input type="text" name="newPassword_confirmation" id="newPassword_confirmation"
                               class="input @error('newPassword_confirmation') is-danger @enderror"
                               placeholder="{{__('Confirm your new password')}}">
                        @error('newPassword_confirmation')
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
