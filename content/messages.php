<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM messages ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);
require '../vendor/autoload.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlGet = mysqli_query($conn, "SELECT * FROM messages WHERE id = '$id'");
    $result = mysqli_fetch_assoc($sqlGet);
    // print_r($result);
    // die;

    if (!$result) {
        die("Data tidak ditemukan!");
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['kirim'])) {
    $id = $_POST['id'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
    $subjek = htmlspecialchars($_POST['subjek']); // Sanitize subject
    $pesan = htmlspecialchars($_POST['messages_reply']); // Sanitize message

    // Konfigurasi PHPMailer
    $mail = new PHPMailer(true); // Buat objek PHPMailer
    $mail->SMTPDebug = 2; // Aktifkan debug

    try {
        // Setel pengaturan server SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'abroorprod@gmail.com';
        $mail->Password   = 'zolp pinb dubd xwet';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Setel pengaturan pengirim dan penerima
        $mail->setFrom('abroorprod@gmail.com', 'Abroor Rizky');
        $mail->addAddress($email);

        // Setel subjek dan isi email
        $mail->isHTML(true);
        $mail->Subject = $subjek;
        $mail->Body    = $pesan;
        $mail->AltBody = strip_tags($pesan);

        // Kirim email
        if ($mail->send()) {
            // Jika email berhasil dikirim, hapus data dari database
            $sqlDel = mysqli_query($conn, "UPDATE messages SET status = '1' WHERE id = $id");
            echo "<script>window.location.href='?page=messages&reply=success';</script>";
        } else {
            echo "<script>alert('GAGAL KIRIM PESAN!'); window.location.href='?page=messages&reply=failed';</script>";
        }
    } catch (Exception $e) {
        // Menangani error jika pengiriman email gagal
        echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
        echo "<br>Error Tambahan: " . $e->getMessage();
    }
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Menjalankan kueri DELETE
    $sqlDel = mysqli_query($conn, "UPDATE messages SET status = '2' WHERE id = $id");

    // Memeriksa apakah kueri DELETE berhasil
    if ($sqlDel) {
        // Redirect setelah penghapusan berhasil
        echo "<script>window.location.href='?page=messages&notif=success';</script>";
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
                            <h5 class="card-title text-primary">Messages Data</h5>
                            <!-- ALERT ERROR -->
                            <?php if (isset($_GET['messages'])) : ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-bell me-2"></i>
                                    <strong>Messages Has Sended!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
                            <!-- <div class="text-end">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                    New Messages
                                </button>
                            </div> -->

                            <!-- <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Messages Add</h5>
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
                            </div> -->


                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <caption class="ms-4">
                                            List of Messages
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Contact Name</th>
                                                <th>Email</th>
                                                <th>Subject</th>
                                                <th>Messages</th>
                                                <th>Time Message In</th>
                                                <th>Time Message Replied/Dismised</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $no = 1;
                                        foreach ($result as $row) :
                                        ?>
                                            <tbody>
                                                <td><?php echo $no++ . '.' ?></td>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['subject'] ?></td>
                                                <td><?= substr($row['message'], 0, 50) ?></td>
                                                <td><?= date('d/m/Y', strtotime($row['message_in'])) ?></td>
                                                <td><?= isset($row['message_update']) ? date('d/m/Y', strtotime($row['message_update'])) : "Message Hasn't Replayed!" ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->

                                                    <?php if ($row['status'] == 0) { ?>
                                                        <a href="?page=messages&id<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">REPLY</a>

                                                        <a href="?page=messages&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to dismissed this messages?')">DISMISSED</a>
                                                    <?php } elseif ($row['status'] == 1) { ?>
                                                        <button class="btn btn-light" disabled>HAS REPLYED</button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-light" disabled>HAS DISMISSED</button>
                                                    <?php } ?>
                                                </td>
                                            </tbody>

                                            <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <!-- FORM EDIT CUSTOMER -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <input type="hidden" name="email" value="<?php echo $row['email'] ?>">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Messages Reply</h5>
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
                                                                            <label for="nameWithTitle" class="form-label">Contact Name</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="nama_pengirim" id="name_pengirim" class="form-control" value="<?= $row['name'] ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="col">
                                                                            <label for="nameWithTitle" class="form-label">Email</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" name="email_pengirim" id="email_pengirim" class="form-control" value="<?= $row['email'] ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Subject</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="subjek_pengirim" id="subject_pengirim" class="form-control" value="<?= $row['subject'] ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Messages</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea name="messages_pengirim" id="messages_pengirim" cols="50" rows="10" class="form-control" readonly><?= $row['message'] ?></textarea>
                                                                    </div>
                                                                </div>

                                                                <!-- BALASAN -->
                                                                <hr class="mb-3">
                                                                <div class="col-sm-6 mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Email</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="email" id="email" class="form-control" value="<?= $row['email'] ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Subject</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="subjek" id="" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col">
                                                                        <label for="nameWithTitle" class="form-label">Messages Reply</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea name="messages_reply" id="" cols="50" rows="4" class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="kirim" class="btn btn-dark">SEND</button>
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