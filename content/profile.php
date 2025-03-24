<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM profile ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

// Cek apakah folder assets/uploads ada
$uploadDir = "../assets/uploads/profile/";
if (!is_dir($uploadDir)) {
    die("Folder uploads tidak ditemukan. Pastikan 'assets/uploads' sudah ada.");
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $profession = $_POST['profesi'];
    $birthday = $_POST['ultah'];
    $website = $_POST['web'];
    $phone = $_POST['telp'];
    $email = $_POST['email'];
    $city = $_POST['kota'];
    $desc = $_POST['desc'];

    $photo = $_FILES['photos'];
    $background = $_FILES['background'];

    // Cek apakah ada kesalahan dalam upload file
    if ($photo["error"] != 0) {
        die("Error saat upload file: " . $photo["error"]);
    }

    $fillName = uniqid() . "_" . basename($photo["name"]);
    $fillPath = $uploadDir . $fillName;

    // Cek apakah ada kesalahan dalam upload file
    if ($background["error"] != 0) {
        die("Error saat upload file: " . $background["error"]);
    }

    $BgName = uniqid() . "_" . basename($background["name"]);
    $BgPath = $uploadDir . $BgName;

    // Coba pindahkan file ke folder tujuan
    move_uploaded_file($photo['tmp_name'], $fillPath);
    move_uploaded_file($background['tmp_name'], $BgPath);

    // Simpan ke database
    $sqlInsert = mysqli_query($conn, "INSERT INTO profile (name, profession, birthday, websites, phone, email, city, photo, description, background) VALUES ('$nama', '$profession', '$birthday', '$website', '$phone', '$email', '$city', '$fillName', '$desc', '$BgName')");

    if ($sqlInsert) {
        echo "Data berhasil dimasukkan ke database.";
        echo "<script>window.location.href='?page=profile&add=success';</script>";
        exit();
    } else {
        die("Gagal menyimpan ke database: " . mysqli_error($conn));
    }

    // $sqlInsert = mysqli_query($conn, "INSERT INTO profile (name, profession, birthday, websites, phone, email, city) VALUES ('$nama', '$profession', '$birthday', '$website', '$phone', '$email', '$city')");

    // if ($sqlInsert) {
    //     echo "<script>window.location.href='?page=profile&add=success';</script>";
    // } else {
    //     echo "<script>window.location.href='?page=profile&add=failed';</script>";
    // }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlGet = mysqli_query($conn, "SELECT * FROM profile WHERE id = '$id'");
    $result = mysqli_fetch_assoc($sqlGet);
    // print_r($result);
    // die;

    if (!$result) {
        die("Data tidak ditemukan!");
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $profession = $_POST['profesi'];
    $birthday = $_POST['ultah'];
    $websites = $_POST['web'];
    $phone = $_POST['telp'];
    $email = $_POST['email'];
    $city = $_POST['kota'];
    $desc = $_POST['desc'];

    $old_photo = $_POST['old_photo'];
    $old_bg = $_POST['old_bg'];

    // Cek apakah ada file yang diunggah
    // if (isset($_FILES['photos']) && $_FILES['photos']['name'] != '' && isset($_FILES['background']) && $_FILES['background']['name'] != '')
    if ($_FILES['photos']['name'] != '') {
        $photo = $_FILES['photos'];

        $photoName = uniqid() . "_" . basename($photo["name"]);
        $photoPath = $uploadDir  . $photoName;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
            // Hapus foto lama jika ada dan bukan gambar default
            if (!empty($old_photo) && file_exists("../assets/uploads/profile/" . $old_photo)) {
                unlink("../assets/uploads/profile/" . $old_photo);
            }
        }
    } else {
        // Jika tidak ada foto baru, gunakan foto lama
        $photoName = $old_photo;
    }

    //Background
    if ($_FILES['background']['name'] != '') {
        $background = $_FILES['background'];

        $BgName = uniqid() . "_" . basename($background["name"]);
        $BgPath = $uploadDir  . $BgName;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($background['tmp_name'], $BgPath)) {
            // Hapus foto lama jika ada dan bukan gambar default
            if (!empty($old_bg) && file_exists("../assets/uploads/profile/" . $old_bg)) {
                unlink("../assets/uploads/profile/" . $old_bg);
            }
        }
    } else {
        // Jika tidak ada foto baru, gunakan foto lama
        $BgName = $old_bg;
    }

    // print_r($_POST);
    // die;
    $sql = mysqli_query($conn, "UPDATE profile SET name = '$nama', profession = '$profession', birthday = '$birthday', websites = '$websites', phone = '$phone', email = '$email', city = '$city', photo = '$photoName', description = '$desc', background = '$BgName' WHERE id = '$id'");
    if ($sql) {
        echo "<script>window.location.href='?page=profile&update=success';</script>";
    } else {
        echo "<script>alert('DATA GAGAL DI UBAH')</script>" . mysqli_error($conn);
        header("Location: add_edit_blog.php?update=gagal");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "DELETE FROM profile WHERE id = '$id'");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Ambil data profil sebelum dihapus untuk mengakses foto
        $sqlGet = mysqli_query($conn, "SELECT * FROM profile WHERE id = '$id'");
        if ($rows = mysqli_fetch_assoc($sqlGet)) {
            // Hapus file foto dari folder uploads
            unlink($uploadDir . $rows['photo'] & $rows['background']);
        }

        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=profile&notif=success';</script>";
    } else {
        // Jika kueri DELETE gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['idSts'];

    $update_sts0 = mysqli_query($conn, "UPDATE profile SET status = '0'");
    $update_sts1 = mysqli_query($conn, "UPDATE profile SET status = '1' WHERE id = $id");

    if ($update_sts1) {
        echo "<script>alert('Profile di Tampilkan!'); window.location.href='?page=profile&show=success';</script>";
    } else {
        echo "<script>alert('Profile Gagal di Tampilkan'); window.location.href='?page=profile&show=failed';</script>";
    }
}

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-header">
                            <h5 class="card-title text-primary">Profile Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['profile'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Profiles Has Added!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Profile
                                </button>
                            </div>

                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <!-- FORM ADD CUSTOMER -->
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Profiles Add</h5>
                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Photo</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="file" name="photos" id="photos" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Background</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="file" name="background" id="background" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Name</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Enter Name" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Profession</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="profesi" id="profesi" class="form-control" placeholder="Enter Profession" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Birthday</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="date" name="ultah" id="ultah" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Websites</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="web" id="web" class="form-control" placeholder="Enter Website Porto" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Phone Number</label>
                                                    </div>
                                                    <div class="col-sm-12 input-group input-group-merge">
                                                        <span class="input-group-text">+62 </span>
                                                        <input type="number" name="telp" id="telp" class="form-control" placeholder="Enter Phone Number" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Email</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">City</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="kota" id="kota" class="form-control" placeholder="Enter City" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Description</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <textarea name="desc" id="" cols="50" rows="5" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="simpan" class="btn btn-dark">SAVE</button>
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                                    CLOSE
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <caption class="ms-4">
                                            List of Profiles
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Background</th>
                                                <th>Photos</th>
                                                <th>Profile Name</th>
                                                <th>Profession</th>
                                                <th>Birthday</th>
                                                <th>Websites</th>
                                                <th>Phone Number</th>
                                                <th>Email</th>
                                                <th>City</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $no = 1;
                                        foreach ($result as $row) :
                                        ?>
                                            <tbody>
                                                <td><?php echo $no++ . '.' ?></td>
                                                <td><img src="../assets/uploads/profile/<?= $row['background'] ?> " alt="" width="100"></td>
                                                <td><img src="../assets/uploads/profile/<?= $row['photo'] ?> " alt="" width="100"></td>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['profession'] ?></td>
                                                <td><?= $row['birthday'] ?></td>
                                                <td><?= $row['websites'] ?></td>
                                                <td><?= $row['phone'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['city'] ?></td>
                                                <td><?= substr($row['description'], 0, 50) ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->

                                                    <div class="mb-3">
                                                        <a href="?page=profile&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">EDIT</a>

                                                        <a href="?page=profile&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this customer?')">DELETE</a>
                                                    </div>

                                                    <form action="?page=profile&idSts=<?= $row['id'] ?>" method="post">
                                                        <input type="radio" onchange="this.form.submit()" name="status" id="status" class="form-check-input" value="1" <?php echo isset($row['status']) && $row['status'] == 1 ? 'checked' : ''; ?>> SHOW
                                                    </form>
                                                </td>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <input type="hidden" name="old_photo" value="<?= $row['photo']; ?>">
                                                            <input type="hidden" name="old_bg" value="<?= $row['background']; ?>">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Customer Edit</h5>
                                                                <button
                                                                    type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="row mb-3">
                                                                        <div class="col-sm-5">
                                                                        </div>
                                                                        <div class="col-sm-7">
                                                                            <img src="../assets/uploads/profile/<?= $row['photo']; ?>" alt="" width="100">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Photo</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="file" name="photos" id="photos" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-sm-5">
                                                                    </div>
                                                                    <div class="col-sm-7">
                                                                        <img src="../assets/uploads/profile/<?= $row['background']; ?>" alt="" width="100">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Background</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="file" name="background" id="background" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Name</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $row['name'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Profession</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="profesi" id="profesi" class="form-control" value="<?php echo $row['profession'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Birthday</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="date" name="ultah" id="ultah" class="form-control" value="<?php echo $row['birthday'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Websites</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="web" id="web" class="form-control" value="<?php echo $row['websites'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Phone Number</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" name="telp" id="telp" class="form-control" value="<?php echo $row['phone'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Email</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="email" id="email" class="form-control" value="<?php echo $row['email'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">City</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="kota" id="kota" class="form-control" value="<?php echo $row['city'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Description</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea name="desc" id="" cols="50" rows="5" class="form-control"><?= $row['description'] ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="edit" class="btn btn-dark">EDIT</button>
                                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                                                    CLOSE
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>