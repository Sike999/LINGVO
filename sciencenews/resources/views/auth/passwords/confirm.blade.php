@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="FU" style="padding:200px;">
        <div class="col-md-8">
            <fieldset>
                <legend style="color:#2BA6CB; background-color:#FAFAFA; font-size:10pt;">Пожалуйста подтвердите свой пароль перед тем как продолжить</legend>
            <div class="card">
                <div class="card-body">
                    
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Пароль</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type ="submit" style="border-radius:8px; margin-left:160px; font-family: Open Sans Condensed; font-size:14pt; width:200px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Подтвердить</button>
                                <br>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Забыли пароль?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
