<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
</head>

<body>

    <h2>Form Tambah Kelas</h2>

    <form action="../../../backend/simpan_kelas.php" method="post">
        <label>Nama Kelas</label><br>
        <input type="text" name="nama_kelas" required><br><br>

        <label>Kuota</label><br>
        <input type="number" name="kuota" min="1" required><br><br>

        <button type="submit">Simpan</button>
    </form>

</body>

</html>