<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM skills ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);

if (isset($_POST['simpan'])) {
    $skill = $_POST['skill'];
    $persen = $_POST['persen'];

    $sqlInsert = mysqli_query($conn, "INSERT INTO skills (skill, percent) VALUES ('$skill', '$persen')");

    if ($sqlInsert) {
        echo "<script>window.location.href='?page=skill&add=success';</script>";
    } else {
        echo "<script>window.location.href='?page=skill&add=failed';</script>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlGet = mysqli_query($conn, "SELECT * FROM skills WHERE id = '$id'");
    $result = mysqli_fetch_assoc($sqlGet);
    // print_r($result);
    // die;

    if (!$result) {
        die("Data tidak ditemukan!");
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $skill = $_POST['skill'];
    $persen = $_POST['persen'];

    $sql = mysqli_query($conn, "UPDATE skills SET skill = '$skill', percent = '$persen' WHERE id = '$id'");

    if ($sql) {
        echo "<script>window.location.href='?page=skill&update=success';</script>";
    } else {
        echo "<script>alert('DATA GAGAL DI UBAH')</script>" . mysqli_error($conn);
        header("Location: add_edit_blog.php?skill=gagal");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "DELETE FROM skills WHERE id = '$id'");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=skill&notif=success';</script>";
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
                            <h5 class="card-title text-primary">Skill Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['skill'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Skill Has Added!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Skill
                                </button>
                            </div>

                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <!-- FORM ADD CUSTOMER -->
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Skill Add</h5>
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
                                                            <label for="nameWithTitle" class="form-label">Skill</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="skill" id="skill" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col">
                                                            <label for="nameWithTitle" class="form-label">Percentage of Ability</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="number" name="persen" id="persen" class="form-control" />
                                                        </div>
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
                                                <th>Name of Skills</th>
                                                <th>Percentage of Ability</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $no = 1;
                                        foreach ($result as $row) :
                                        ?>
                                            <tbody>
                                                <td><?php echo $no++ . '.' ?></td>
                                                <td><?= $row['skill'] ?></td>
                                                <td><?= $row['percent'] ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->

                                                    <a href="?page=skill&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">EDIT</a>

                                                    <a href="?page=skill&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this customer?')">DELETE</a>
                                                </td>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Skill Edit</h5>
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
                                                                            <label for="nameWithTitle" class="form-label">Skill</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="skill" id="skill" class="form-control" value="<?= $row['skill'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="col">
                                                                            <label for="nameWithTitle" class="form-label">Percentage of Ability</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="number" name="persen" id="persen" class="form-control" value="<?= $row['percent'] ?>" />
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