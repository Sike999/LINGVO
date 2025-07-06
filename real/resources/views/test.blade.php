<title>TEST</title>
<h1>test {{$id / 10}}</h1>
<p> {{$cas}} </p>
@foreach ($data as $one)
    <div>
        <h2>{{ $one->Model }}</h2>
        <p>{{ $one->Price }}</p>
    </div>
@endforeach