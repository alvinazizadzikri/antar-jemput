<h1>Daftar Driver</h1>

<a href="/admin/drivers/create">
    Tambah Driver
</a>

<table border="1" cellpadding="10">

    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Kendaraan</th>
        <th>Plat</th>
        <th>Status</th>
    </tr>

    @foreach($drivers as $driver)

    <tr>
        <td>{{ $driver->user->name }}</td>
        <td>{{ $driver->user->email }}</td>
        <td>{{ $driver->vehicle_type }}</td>
        <td>{{ $driver->plate_number }}</td>
        <td>{{ $driver->status }}</td>
    </tr>

    @endforeach

</table>