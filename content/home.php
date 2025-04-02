<?php
$sqlGet = mysqli_query($conn, "SELECT * FROM profile ORDER BY id DESC");
$result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-header">
                            <h5 class="card-title text-primary">Profile Showed</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($result as $row) : ?>
                                <div class="row ms-2">
                                    <div class="col-sm-3">
                                        <img src="../assets/uploads/profile/<?= $row['photo'] ?>" alt="<?= $row['name'] ?>" class="img-fluid rounded-circle" width="200px">
                                    </div>
                                    <div class="col-sm-9">
                                        <h2 class="card-title"><?= $row['name'] ?></h2>
                                        <div class="row mt-4">
                                            <ul>
                                                <i class="bi bi-chevron-right"></i> <strong>Birthday:</strong> <span><?php echo date('d/m/Y', strtotime($row['birthday'])); ?></span>
                                            </ul>
                                            <ul>
                                                <i class="bi bi-chevron-right"></i> <strong>Website:</strong> <span><?php echo $row['websites']; ?></span>
                                            </ul>
                                            <ul>
                                                <i class="bi bi-chevron-right"></i> <strong>Phone:</strong> <span><?php echo $row['phone']; ?></span>
                                            </ul>
                                            <ul>
                                                <i class="bi bi-chevron-right"></i> <strong>City:</strong> <span><?php echo $row['city']; ?></span>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Transactions -->
    </div>
</div>