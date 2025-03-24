<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM education ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

if (isset($_POST['simpan'])) {
    $yearIn = $_POST['year_in'];
    $yearOut = $_POST['year_out'];
    $instansi = $_POST['instansi'];
    $degree = $_POST['degree'];
    $major = $_POST['major'];
    $desc = $_POST['desc'];

    $sqlInsert = mysqli_query($conn, "INSERT INTO education (year_in, year_out, name_instansi ,degree, major, description) VALUES ('$yearIn', '$yearOut', '$instansi','$degree', '$major', '$desc')");

    if ($sqlInsert) {
        echo "<script>window.location.href='?page=education&add=success';</script>";
    } else {
        echo "<script>window.location.href='?page=education&add=failed';</script>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlGet = mysqli_query($conn, "SELECT * FROM education WHERE id = '$id'");
    $result = mysqli_fetch_assoc($sqlGet);
    // print_r($result);
    // die;

    if (!$result) {
        die("Data tidak ditemukan!");
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $yearIn = $_POST['year_in'];
    $yearOut = $_POST['year_out'];
    $instansi = $_POST['instansi'];
    $degree = $_POST['degree'];
    $major = $_POST['major'];
    $desc = $_POST['desc'];

    $sql = mysqli_query($conn, "UPDATE education SET year_in = '$yearIn', year_out = '$yearOut', name_instansi = '$instansi', degree = '$degree', major = '$major', description = '$desc' WHERE id = '$id'");

    if ($sql) {
        echo "<script>window.location.href='?page=education&update=success';</script>";
    } else {
        echo "<script>alert('DATA GAGAL DI UBAH')</script>" . mysqli_error($conn);
        header("Location: ?page=education&update=gagal");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "DELETE FROM education WHERE id = '$id'");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=education&notif=success';</script>";
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
                            <h5 class="card-title text-primary">Education Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['education'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Education Has Added!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Education
                                </button>
                            </div>

                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <!-- FORM ADD CUSTOMER -->
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Education Add</h5>
                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        <div class="col">
                                                            <label for="nameWithTitle" class="form-label">Year In</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="year_in" id="year_in" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col">
                                                            <label for="nameWithTitle" class="form-label">Year Out</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="year_out" id="year_out" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Institute</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="instansi" id="instansi" class="form-control" placeholder="Enter Your Institute" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Degree</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="degree" id="degree" class="form-control" placeholder="Enter Your Degree" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="nameWithTitle" class="form-label">Major</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="major" id="major" class="form-control" placeholder="Enter Your Degree" />
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
                                                <th>Year In</th>
                                                <th>Year Out</th>
                                                <th>Institute</th>
                                                <th>Degree</th>
                                                <th>Major</th>
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
                                                <td><?= $row['year_in'] ?></td>
                                                <td><?= $row['year_out'] ?></td>
                                                <td><?= $row['name_instansi'] ?></td>
                                                <td><?= $row['degree'] ?></td>
                                                <td><?= $row['major'] ?></td>
                                                <td><?= $row['description'] ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->

                                                    <a href="?page=education&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">EDIT</a>

                                                    <a href="?page=education&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this customer?')">DELETE</a>
                                                </td>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Education Edit</h5>
                                                                <button
                                                                    type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="row mb-3">
                                                                        <div class="col-sm-6">
                                                                            <div class="col">
                                                                                <label for="nameWithTitle" class="form-label">Year In</label>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <input type="text" name="year_in" id="year_in" class="form-control" value="<?= $row['year_in'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="col">
                                                                                <label for="nameWithTitle" class="form-label">Year Out</label>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <input type="text" name="year_out" id="year_out" class="form-control" value="<?= $row['year_out'] ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col">
                                                                            <label for="nameWithTitle" class="form-label">Institute</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="instansi" id="instansi" class="form-control" value="<?= $row['name_instansi'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col">
                                                                            <label for="nameWithTitle" class="form-label">Degree</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="degree" id="degree" class="form-control" placeholder="Enter Your Degree" value="<?= $row['degree'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col">
                                                                            <label for="nameWithTitle" class="form-label">Major</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="major" id="major" class="form-control" placeholder="Enter Your Degree" value="<?= $row['major'] ?>" />
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