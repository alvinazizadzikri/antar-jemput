<h1>Daftar Perjalanan</h1>

<a href="/admin/trips/create">
    Assign Driver
</a>

@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

<table border="1" cellpadding="10">
    <tr>
        <th>Driver</th>
        <th>Kid</th>
        <th>Status</th>
    </tr>

    @foreach($trips as $trip)
    <tr>
        <td>{{ $trip->driver->user->name }}</td>
        <td>{{ $trip->kid->name }}</td>
        <td>{{ $trip->status }}</td>
    </tr>
    @endforeach
</table>