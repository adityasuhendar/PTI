<?php
include '../../../config.php';

// PROSES TAMBAH
if (isset($_POST['tambah'])) {
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);
    $role = 'kasir';

    // Tambah ke tabel member
    $stmt = $config->prepare("INSERT INTO member (nm_member, alamat_member, telepon, email, gambar, NIK) 
        VALUES (?, ?, ?, ?, 'unnamed.jpg', ?)");
    $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['telepon'], $_POST['email'], $_POST['nik']]);

    $id_member = $config->lastInsertId();

    // Tambah ke tabel login
    $stmt = $config->prepare("INSERT INTO login (user, pass, id_member, role) 
        VALUES (?, ?, ?, ?)");
    $stmt->execute([$user, $pass, $id_member, $role]);

    header("Location: index.php?page=kasir&success=true");
    exit;
}

// PROSES EDIT
if (isset($_POST['edit'])) {
    $id_login = $_POST['id_login'];
    $id_member = $_POST['id_member'];

    // Update tabel login
    $stmt = $config->prepare("UPDATE login SET user = ? WHERE id_login = ?");
    $stmt->execute([$_POST['user'], $id_login]);

    // Update tabel member
    $stmt = $config->prepare("UPDATE member SET nm_member = ?, alamat_member = ?, telepon = ?, email = ?, NIK = ? 
        WHERE id_member = ?");
    $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['telepon'], $_POST['email'], $_POST['nik'], $id_member]);

    header("Location: index.php?page=kasir&edit=true");
    exit;
}

// PROSES HAPUS
if (isset($_GET['hapus'])) {
    $id_login = $_GET['hapus'];

    // Ambil id_member
    $stmt = $config->prepare("SELECT id_member FROM login WHERE id_login = ?");
    $stmt->execute([$id_login]);
    $id_member = $stmt->fetch()['id_member'];

    // Hapus login
    $config->prepare("DELETE FROM login WHERE id_login = ?")->execute([$id_login]);

    // Hapus member
    $config->prepare("DELETE FROM member WHERE id_member = ?")->execute([$id_member]);

    header("Location: index.php?page=kasir&hapus=true");
    exit;
}
?>

<h4>Manajemen User Kasir</h4>
<br />
<?php if (isset($_GET['success'])) { ?>
    <div class="alert alert-success"><p>Data berhasil ditambahkan!</p></div>
<?php } ?>
<?php if (isset($_GET['edit'])) { ?>
    <div class="alert alert-success"><p>Data berhasil diubah!</p></div>
<?php } ?>
<?php if (isset($_GET['hapus'])) { ?>
    <div class="alert alert-danger"><p>Data berhasil dihapus!</p></div>
<?php } ?>

<!-- Tombol Tambah -->
<button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalTambah">
    <i class="fa fa-plus"></i> Tambah User Kasir
</button>
<br><br>

<!-- Tabel Data -->
<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm" id="example1">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th>No.</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>NIK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT l.id_login, l.user, l.role, l.id_member, m.nm_member, m.alamat_member, m.telepon, m.email, m.NIK 
                            FROM login l 
                            JOIN member m ON l.id_member = m.id_member 
                            WHERE l.role = 'kasir'";
                    $row = $config->prepare($sql);
                    $row->execute();
                    $no = 1;
                    foreach($row as $data) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['user']; ?></td>
                    <td><?= $data['role']; ?></td>
                    <td><?= $data['nm_member']; ?></td>
                    <td><?= $data['email']; ?></td>
                    <td><?= $data['telepon']; ?></td>
                    <td><?= $data['alamat_member']; ?></td>
                    <td><?= $data['NIK']; ?></td>
                    <td>
                        <!-- Button untuk trigger modal edit -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $data['id_login']; ?>">Edit</button>
                        <a href="index.php?page=kasir&hapus=<?= $data['id_login']; ?>" onclick="return confirm('Yakin ingin hapus data ini?')">
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit<?= $data['id_login']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST">
                            <input type="hidden" name="id_login" value="<?= $data['id_login']; ?>">
                            <input type="hidden" name="id_member" value="<?= $data['id_member']; ?>">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title">Edit Kasir</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"><label>Username</label><input type="text" name="user" value="<?= $data['user']; ?>" class="form-control" required></div>
                                    <div class="form-group"><label>Nama</label><input type="text" name="nama" value="<?= $data['nm_member']; ?>" class="form-control" required></div>
                                    <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= $data['email']; ?>" class="form-control" required></div>
                                    <div class="form-group"><label>Telepon</label><input type="text" name="telepon" value="<?= $data['telepon']; ?>" class="form-control" required></div>
                                    <div class="form-group"><label>Alamat</label><input type="text" name="alamat" value="<?= $data['alamat_member']; ?>" class="form-control" required></div>
                                    <div class="form-group"><label>NIK</label><input type="text" name="nik" value="<?= $data['NIK']; ?>" class="form-control" required></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Kasir</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Username</label><input type="text" name="user" class="form-control" required></div>
                    <div class="form-group"><label>Password</label><input type="password" name="pass" class="form-control" required></div>
                    <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label>Telepon</label><input type="text" name="telepon" class="form-control" required></div>
                    <div class="form-group"><label>Alamat</label><input type="text" name="alamat" class="form-control" required></div>
                    <div class="form-group"><label>NIK</label><input type="text" name="nik" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
