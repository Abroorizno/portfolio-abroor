<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$results = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

// echo "<pre>";
// print_r($results);
// die;
// echo "</pre>";

// if (!$sqlGet) {
//     die("Query gagal: " . mysqli_error($conn));
// }
// $result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

// if (count($result) === 0) {
//     die("Tidak ada data dalam tabel users.");
// }

// Cek apakah folder assets/uploads ada
$uploadDir = "../assets/uploads/users/";
if (!is_dir($uploadDir)) {
    die("Folder uploads tidak ditemukan. Pastikan 'assets/uploads' sudah ada.");
}

if (isset($_POST['simpan'])) {
    $usernama = $_POST['nama'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $photo = $_FILES['photos'];

    // Cek apakah ada kesalahan dalam upload file
    if ($photo["error"] != 0) {
        die("Error saat upload file: " . $photo["error"]);
    }

    $fillName = uniqid() . "_" . basename($photo["name"]);
    $fillPath = $uploadDir . $fillName;

    // Coba pindahkan file ke folder tujuan
    move_uploaded_file($photo['tmp_name'], $fillPath);

    // Simpan ke database
    $sqlInsert = mysqli_query($conn, "INSERT INTO users (username, email, password, photo) VALUES ('$usernama', '$email', '$password', '$fillName')");

    if ($sqlInsert) {
        echo "Data berhasil dimasukkan ke database.";
        echo "<script>window.location.href='?page=users&add=success';</script>";
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

    $sqlGet = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $result = mysqli_fetch_assoc($sqlGet);
    // print_r($result);
    // die;

    if (!$result) {
        die("Data tidak ditemukan!");
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $usernama = $_POST['nama'];
    $email = $_POST['email'];

    // Cek apakah password baru diisi
    if (!empty($_POST['password'])) {
        $password = sha1($_POST['password']);
    } else {
        $password = $_POST['old_password'];
    }

    $old_photo = $_POST['old_photo'];

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

    // print_r($_POST);
    // die;
    $sql = mysqli_query($conn, "UPDATE users SET username = '$usernama', email = '$email', password = '$password', photo = '$photoName' WHERE id = '$id'");
    if ($sql) {
        echo "<script>window.location.href='?page=users&update=success';</script>";
    } else {
        echo "<script>alert('DATA GAGAL DI UBAH')</script>" . mysqli_error($conn);
        header("Location: ?page=users&update=gagal");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Ambil data profil sebelum dihapus untuk mengakses foto
        $sqlGet = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
        if ($rows = mysqli_fetch_assoc($sqlGet)) {
            // Hapus file foto dari folder uploads
            unlink($uploadDir . $rows['photo']);
        }

        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=users&notif=success';</script>";
    } else {
        // Jika kueri DELETE gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
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
                            <h5 class="card-title text-primary">Users Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['users'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Users Has Added!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Users
                                </button>
                            </div>

                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <!-- FORM ADD CUSTOMER -->
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Users Add</h5>
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
                                                        <label for="nameWithTitle" class="form-label">Username</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Enter Username" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Email</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Password</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="password" name="password" id="password" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="simpan" class="btn btn-dark">SAVE</button>
                                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                                        CLOSE
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <caption class="ms-4">
                                            List of Users
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Photos</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $no = 1;
                                        foreach ($results as $row) :
                                        ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $no++ . '.' ?></td>
                                                    <td><img src="../assets/uploads/users/<?= $row['photo'] ?> " alt="" width="100"></td>
                                                    <td><?= $row['username'] ?></td>
                                                    <td><?= $row['email'] ?></td>
                                                    <td>
                                                        <div class="mb-3">
                                                            <a href="?page=users&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">EDIT</a>

                                                            <a href="?page=users&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this user?')">DELETE</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <input type="hidden" name="old_photo" value="<?= $row['photo']; ?>">
                                                            <input type="hidden" name="old_password" value="<?= $row['password'] ?>">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Users Edit</h5>
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
                                                                            <img src="../assets/uploads/users/<?= $row['photo']; ?>" alt="" width="100">
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
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">User Name</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $row['username'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Email</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $row['email'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Password</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="password" name="password" id="password" class="form-control" value="<?php echo $row['password'] ?>" />
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