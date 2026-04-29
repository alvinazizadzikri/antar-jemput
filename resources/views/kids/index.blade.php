<h2>Data Anak</h2>

<a href="/kids/create">+ Tambah Anak</a>

@if(session('success'))
<p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10">
<tr>
    <th>Nama</th>
    <th>Sekolah</th>
    <th>Foto</th>
    <th>Aksi</th>
</tr>

@foreach($kids as $k)
<tr>
    <td>{{ $k->name }}</td>
    <td>{{ $k->school_name }}</td>
    <td>
        @if($k->photo)
            <img src="{{ asset('storage/'.$k->photo) }}" width="70">
        @endif
    </td>
    <td>
        <a href="/kids/{{ $k->id }}/edit">Edit</a>

        <form action="/kids/{{ $k->id }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    </td>
</tr>
@endforeach
</table>