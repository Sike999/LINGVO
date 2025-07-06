@extends ('layout')
@section('content')
@if ($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    <form action="{{ route('update.confirm', $res->id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @error('Image')
        <p class="text-danger">{{ $message }}</p>
    @enderror

    ФИО: <input type="text" name="FIO" value="{{ old('FIO') }}">
    Профессия: 
    <select name="Staff">
        @foreach($staffs as $s)
            <option value="{{ $s->id }}" {{ old('Staff') == $s->id ? 'selected' : ''}}>{{ $s->staff }}</option>
        @endforeach
    </select>
    Телефон: <input type="text" name="Phone" value="{{ old('Phone') }}">
    Стаж: <input type="text" name="Stage" value="{{ old('Stage') }}">
    Картинка: <input type="file" name="Image">

<button class='button'>Подтвердить</button>
</form>

@else
<form action="{{ route('update.confirm', $res->id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @error('Image')
        <p class="text-danger">{{ $message }}</p>
    @enderror

    ФИО: <input type="text" name="FIO" value="{{ $res->FIO }}">
    Профессия: 
    <select name="Staff">
        @foreach($staffs as $s)
            <option value="{{ $s->id }}" {{ $res->Staff == $s->id ? 'selected' : ''}}>{{ $s->staff }}</option>
        @endforeach
    </select>
    Телефон: <input type="text" name="Phone" value="{{ $res->Phone }}">
    Стаж: <input type="text" name="Stage" value="{{ $res->Stage }}">
    Картинка: <input type="file" name="Image">
    <button class='button'>Подтвердить</button>
</form>
@endif
@endsection