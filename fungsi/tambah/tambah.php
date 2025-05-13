<?php
require '../../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
// use Picqer\Barcode\BarcodeGeneratorJPG;

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    if (!empty($_GET['kategori'])) {
        $nama = htmlentities($_POST['kategori']);
        $tgl = date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&success=tambah-data"</script>';
    }

    if (!empty($_GET['barang'])) {
        $barcode_option  = htmlentities($_POST['barcode_option']);
        // require_once '../../assets/barcode/BarcodeGenerator.php';
        // require_once '../../assets/barcode/BarcodeGeneratorJPG.php';        

        $id = htmlentities($_POST['id']);
        $barcode = htmlentities($_POST['barcode']);
        $kategori = htmlentities($_POST['kategori']);
        $nama = htmlentities($_POST['nama']);
        $merk = htmlentities($_POST['merk']);
        $beli = htmlentities($_POST['beli']);
        $jual = htmlentities($_POST['jual']);
        $satuan = htmlentities($_POST['satuan']);
        $stok = htmlentities($_POST['stok']);
        $tgl = htmlentities($_POST['tgl']);
        $barcodeImagePath = ''; // Variabel untuk menyimpan path gambar barcode
        // Membuat generator barcode JPG
        // $generator = new BarcodeGeneratorJPG();

        // // Menggunakan ID barang sebagai barcode
        // $barcode = $id;  // Kamu bisa ganti dengan ID atau string lain untuk barcode

        // // Tentukan tipe barcode (misalnya TYPE_CODE_128)
        // $type = $generator::TYPE_CODABAR;

        // // Membuat barcode JPG berdasarkan tipe dan ID barang
        // $barcodeImage = $generator->getBarcode($barcode, $type);

        // // Tentukan path untuk menyimpan gambar barcode
        
        // // Menyimpan gambar barcode ke server
        // file_put_contents($barcodeImagePath, $barcodeImage);
        if ($kategori == '') {
            echo "<script>alert('Silakan pilih kategori terlebih dahulu.'); window.history.back();</script>";
            exit;
        }

        $cek_kategori = $config->prepare("SELECT COUNT(*) FROM kategori WHERE id_kategori = ?");
        $cek_kategori->execute([$kategori]);
        if ($cek_kategori->fetchColumn() == 0) {
            echo "<script>alert('Kategori tidak ditemukan di database.'); window.history.back();</script>";
            exit;
}

    if ($barcode_option == 'generate') {
        // Misal $nama_barang sudah tersedia dari input form atau query
        $nama_file = str_replace(' ', '_', strtolower($nama)) . '_' . $barcode . '.jpg';
        
        // $generator = (new Picqer\Barcode\Types\TypeCodabar())->getBarcode($barcode);
        $generator = (new Picqer\Barcode\Types\TypeCode128())->getBarcode($barcode);
        $renderer = new Picqer\Barcode\Renderers\JpgRenderer();
        
        $barcodeImagePath = '../../assets/barcode_images/' . $nama_file;
        file_put_contents($barcodeImagePath, $renderer->render($generator, $generator->getWidth() * 1));
    }

        // Menyimpan data barang ke database, hanya menyimpan path gambar barcode
        $data[] = $id;
        $data[] = $barcode;
        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $data[] = $barcodeImagePath; // Menyimpan path gambar barcode di database

        $sql = 'INSERT INTO barang (id_barang,barcode,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input,barcode_image_path) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        $row = $config -> prepare($sql);
        $row -> execute($data);

        echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
    }

    if (!empty($_GET['jual'])) {
        $id = $_GET['id'];

        // get tabel barang id_barang
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $row = $config->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch();

        if ($hsl['stok'] > 0) {
            $kasir =  $_GET['id_kasir'];
            $jumlah = 1;
            $total = $hsl['harga_jual'];
            $tgl = date("j F Y, G:i");

            $data1[] = $id;
            $data1[] = $kasir;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $tgl;

            $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input) VALUES (?,?,?,?,?)';
            $row1 = $config -> prepare($sql1);
            $row1 -> execute($data1);

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        } else {
            echo '<script>alert("Stok Barang Anda Telah Habis !"); 
                    window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }
}
?>
