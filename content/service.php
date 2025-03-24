<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

// Cek apakah folder assets/uploads ada
$uploadDir = "../assets/uploads/service/";
if (!is_dir($uploadDir)) {
    die("Folder uploads tidak ditemukan. Pastikan 'assets/uploads' sudah ada.");
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $desc = $_POST['desc'];

    $photo = $_FILES['photos'];

    // Cek apakah ada kesalahan dalam upload file
    if ($photo["error"] != 0) {
        die("Error saat upload file: " . $photo["error"]);
    }

    $fillName = uniqid() . "_" . basename($photo["name"]);
    $fillPath = $uploadDir . $fillName;

    // Coba pindahkan file ke folder tujuan
    if (move_uploaded_file($photo['tmp_name'], $fillPath)) {
        echo "File berhasil diunggah ke: " . $fillPath . "<br>";

        // Simpan ke database
        $sqlInsert = mysqli_query($conn, "INSERT INTO services (service_name, pict, description) VALUES ('$nama', '$fillName', '$desc')");

        if ($sqlInsert) {
            echo "Data berhasil dimasukkan ke database.";
            echo "<script>window.location.href='?page=service&add=success';</script>";
            exit();
        } else {
            die("Gagal menyimpan ke database: " . mysqli_error($conn));
        }
    } else {
        die("Gagal memindahkan file ke folder uploads.");
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlGet = mysqli_query($conn, "SELECT * FROM services WHERE id = '$id'");
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
    $desc = $_POST['desc'];

    $old_photo = $_POST['old_photo'];

    // Cek apakah ada file yang diunggah
    if ($_FILES['photos']['name'] != '') {
        $photo = $_FILES['photos'];
        $photoName = uniqid() . "_" . basename($photo["name"]);
        $photoPath = $uploadDir . $photoName;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
            // Hapus foto lama jika ada dan bukan gambar default
            if (!empty($old_photo) && file_exists($uploadDir . $old_photo)) {
                unlink($uploadDir . $old_photo);
            }
        }
    } else {
        // Jika tidak ada foto baru, gunakan foto lama
        $photoName = $old_photo;
    }

    $sql = mysqli_query($conn, "UPDATE services SET service_name = '$nama', pict = '$photoName', description = '$desc' WHERE id = '$id'");

    if ($sql) {
        echo "<script>window.location.href='?page=service&update=success';</script>";
    } else {
        echo "<script>alert('DATA GAGAL DI UBAH')</script>" . mysqli_error($conn);
        header("Location: add_edit_blog.php?service=gagal");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "DELETE FROM services WHERE id = '$id'");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Ambil data profil sebelum dihapus untuk mengakses foto
        $sqlGet = mysqli_query($conn, "SELECT * FROM services WHERE id = '$id'");
        if ($rows = mysqli_fetch_assoc($sqlGet)) {
            // Hapus file foto dari folder uploads
            unlink($uploadDir . $rows['photo']);
        }

        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=service&notif=success';</script>";
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
                            <h5 class="card-title text-primary">Services Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['service'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Services Has Added!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Services
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
                                                        <label for="nameWithTitle" class="form-label">Name of Service</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Enter Name" />
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
                                                <th>Photos</th>
                                                <th>Profile Name</th>
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
                                                <td><img src="../assets/uploads/service/<?= $row['pict'] ?> " alt="" width="50"></td>
                                                <td><?= $row['service_name'] ?></td>
                                                <td><?= $row['description'] ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->

                                                    <a href="?page=service&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">EDIT</a>

                                                    <a href="?page=service&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this customer?')">DELETE</a>
                                                </td>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <input type="hidden" name="old_photo" value="<?= $row['pict']; ?>">

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
                                                                    <div class="col-sm-5">
                                                                    </div>
                                                                    <div class="col-sm-7">
                                                                        <img src="../assets//uploads/service/<?= $row['pict']; ?>" alt="" width="100">
                                                                    </div>
                                                                </div>
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
                                                                        <label for="nameWithTitle" class="form-label">Name</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $row['service_name'] ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Description</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea name="desc" id="" cols="50" rows="5" class="form-control"><?php echo $row['description'] ?></textarea>
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