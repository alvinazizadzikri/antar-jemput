<h2>Tambah Anak</h2>

<form method="POST" action="/kids" enctype="multipart/form-data">
@csrf

<input type="text" name="name" placeholder="Nama"><br><br>
<input type="text" name="school_name" placeholder="Sekolah"><br><br>
<textarea name="address" placeholder="Alamat"></textarea><br><br>

<input type="text" name="pickup_point" placeholder="Titik Jemput"><br><br>
<input type="text" name="dropoff_point" placeholder="Titik Antar"><br><br>

<input type="file" name="photo"><br><br>

<button type="submit">Simpan</button>
</form>