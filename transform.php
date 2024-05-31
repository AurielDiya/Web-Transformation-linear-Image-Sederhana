<?php
// Cek apakah pengguna telah mengunggah gambar
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Simpan gambar unggahan pada direktori server
    $temp_file = $_FILES['image']['tmp_name'];
    $target_file = 'uploads/' . $_FILES['image']['name'];
    move_uploaded_file($temp_file, $target_file);

    // Menampilkan gambar asli
    echo '<h1>Gambar asli:</h1>';
    echo '<img src="' . $target_file . '">'; 

    // Buat gambar negatif
    $image = imagecreatefromjpeg($target_file);
    $width = imagesx($image);
    $height = imagesy($image);
    for ($i = 0; $i < $width; $i++) {
        for ($j = 0; $j < $height; $j++) {
            $rgb = imagecolorat($image, $i, $j);
            $r = 255 - ($rgb >> 16) & 0xFF;
            $g = 255 - ($rgb >> 8) & 0xFF;
            $b = 255 - $rgb & 0xFF;

            $new_rgb = ($r << 16) | ($g << 8) | $b;
            imagesetpixel($image, $i, $j, $new_rgb);
        }
    }

    // Simpan gambar hasil transformasi ke dalam file
    $output_file = 'output/' . uniqid() . '.jpeg';
    imagejpeg($image, $output_file);

    // Tampilkan gambar hasil transformasi pada halaman
    echo '<h1>Gambar hasil transformasi:</h1>';
    echo '<img src="' . $output_file . '">';
} else {
    // Tampilkan pesan kesalahan jika unggahan gagal
    switch ($_FILES['image']['error']) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo 'Ukuran file terlalu besar. Maksimum ukuran file yang diizinkan adalah ' . ini_get('upload_max_filesize') . '.';
            break;
        case UPLOAD_ERR_NO_FILE:
            echo 'File tidak ditemukan. Silakan pilih file untuk diunggah.';
            break;
        case UPLOAD_ERR_PARTIAL:
            echo 'File hanya terunggah sebagian. Silakan coba lagi.';
            break;
        default:
            echo 'Terjadi kesalahan saat unggah file. Silakan coba lagi.';
            break;
    }
}
