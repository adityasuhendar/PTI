<?php 
@ob_start();
session_start();
if (!empty($_SESSION['admin'])) { } else {
    echo '<script>window.location="login.php";</script>';
    exit;
}
require 'config.php';
include $view;
$lihat = new view($config);
$toko = $lihat->toko();
$hsl = $lihat->penjualan();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Belanja</title>
    <style>
        body {
            width: 80mm;
            margin: 0 auto;
            font-family: monospace;
            font-size: 12px;
        }
        .center {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header, .footer {
            margin-bottom: 10px;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        td {
            vertical-align: top;
        }
        .right {
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center header">
        <div><strong><?= $toko['nama_toko']; ?></strong></div>
        <div><?= $toko['alamat_toko']; ?></div>
        <div><?= date("j F Y, G:i"); ?></div>
        <div>Kasir: <?= htmlentities($_GET['nm_member']); ?></div>
    </div>

    <div class="line"></div>
    <table>
        <?php $no = 1; foreach ($hsl as $isi): ?>
        <tr>
            <td colspan="2"><?= $isi['nama_barang']; ?></td>
        </tr>
        <tr>
            <td><?= $isi['jumlah']; ?> x <?= number_format($isi['harga']); ?></td>
            <td class="right">Rp<?= number_format($isi['total']); ?></td>
        </tr>
        <?php $no++; endforeach; ?>
    </table>
    <div class="line"></div>

    <?php $hasil = $lihat->jumlah(); ?>
    <table>
        <tr>
            <td>Total</td>
            <td class="right">Rp<?= number_format($hasil['bayar']); ?></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">Rp<?= number_format(htmlentities($_GET['bayar'])); ?></td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right">Rp<?= number_format(htmlentities($_GET['kembali'])); ?></td>
        </tr>
    </table>

    <div class="line"></div>
    <div class="center footer">
        Terima Kasih<br>
        Telah berbelanja di toko kami
    </div>
</body>
</html>
