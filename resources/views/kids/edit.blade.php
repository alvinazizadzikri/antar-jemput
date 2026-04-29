<h2>Edit Anak</h2>

<form method="POST" action="/kids/{{ $kid->id }}" enctype="multipart/form-data">
@csrf
@method('PUT')

<input type="text" name="name" value="{{ $kid->name }}"><br><br>
<input type="text" name="school_name" value="{{ $kid->school_name }}"><br><br>
<textarea name="address">{{ $kid->address }}</textarea><br><br>

<input type="text" name="pickup_point" value="{{ $kid->pickup_point }}"><br><br>
<input type="text" name="dropoff_point" value="{{ $kid->dropoff_point }}"><br><br>

<input type="file" name="photo"><br><br>

<button type="submit">Update</button>
</form>