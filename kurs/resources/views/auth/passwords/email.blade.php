@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="FU" style="padding:200px;">
        <div class="col-md-8">
            <fieldset>
                <legend style="color:#2BA6CB; background-color:#FAFAFA; font-size:16pt;">Восстановление пароля</legend>
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type ="submit" style="border-radius:8px; margin-left:110px; font-family: Open Sans Condensed; font-size:14pt; width:300px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Отправить писмьо для восстановления</button>
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
