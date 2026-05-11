<h1>Tambah Driver</h1>

<form action="/admin/drivers" method="POST">

    @csrf

    <input type="text" name="name" placeholder="Nama Driver">
    <br><br>

    <input type="email" name="email" placeholder="Email">
    <br><br>

    <input type="text" name="vehicle_type" placeholder="Jenis Kendaraan">
    <br><br>

    <input type="text" name="plate_number" placeholder="Plat Nomor">
    <br><br>

    <button type="submit">
        Simpan
    </button>

</form>