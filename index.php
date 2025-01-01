<?php
require 'function.php';
require 'cek.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>StocKu</title>
        <link href="css/styles.css" rel="stylesheet"/>
        <link
            href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
            rel="stylesheet"
            crossorigin="anonymous"/>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"
            crossorigin="anonymous"></script>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">
                Stoc<span style="color: #007bff;">Ku</span>
            </a>
            <button
                class="btn btn-link btn-sm order-1 order-lg-0"
                id="sidebarToggle"
                href="#">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Navbar-->

        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon">
                                    <i class="bi bi-cart-fill"></i>
                                </div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon">
                                    <i class="bi bi-cart-dash"></i>
                                </div>
                                barang Keluar
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-user text-center py-3">
                        <div class="small">Logged in as:</div>
                        <strong><?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Stock Barang</h1>

                        <div class="card mb-4">
                            <div class="card-header">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#myModal">
                                    Tambah Barang
                                </button>
                                <a href="export.php" class="btn btn-info">Export Data</a>
                            </div>
                            <div class="card-body">

                                <?php
                            $ambilsemuadatastock = mysqli_query($con,"select * from stock where stock < 1");

                            while($fetch=mysqli_fetch_array($ambilsemuadatastock)){
                                $barang = $fetch['namabarang'];
                                
                            ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Perhatian!</strong>
                                    Stock barang
                                    <?=$barang;?>
                                    habis
                                </div>

                                <?php
                            }
                            ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Id Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Stock</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            
                                            $i=0;
                                            $ambilsemuadatastock = mysqli_query($con,"select * from stock");
                                            while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                $namabarang = $data['namabarang'];
                                                $kategori = $data['kategori'];
                                                $stock = $data['stock'];
                                                $idb = $data['idbarang'];
                                            ?>
                                            <tr>
                                                <td><?=$idb?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$kategori;?></td>
                                                <td><?=$stock;?></td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        class="btn btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#edit<?=$idb;?>">
                                                        Edit
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger"
                                                        data-toggle="modal"
                                                        data-target="#delete<?=$idb;?>">
                                                        delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?=$idb;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <label for="namabarang">Nama Barang</label>
                                                                <input
                                                                    type="text"
                                                                    id="namabarang"
                                                                    name="namabarang"
                                                                    value="<?=$namabarang?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <label for="kategori">Kategori</label>
                                                                <br>
                                                                <input
                                                                    type="text"
                                                                    id="kategori"
                                                                    name="kategori"
                                                                    value="<?=$kategori?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <label for="stock">Stock</label>
                                                                <input
                                                                    type="number"
                                                                    id="stock"
                                                                    name="stock"
                                                                    value="<?=$stock?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <input type="hidden" id="idb" name="idb" value="<?=$idb?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-warning" name="confirmupdate">Confirm Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delet Modal -->
                                            <div class="modal fade" id="delete<?=$idb;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Apakah Anda Yakin Ingin Menghapus Barang
                                                                <?=$namabarang;?>?<br>
                                                                <input type="hidden" id="idb" name="idb" value="<?=$idb?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="deletebarang">Hapus</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            };
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; stocKu.com 2024</div>
                            <div class="text-muted">Kelompok 2 (UAS) STT WASTUKANCANA TEKNIK INFORMATIKA</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script
            src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            crossorigin="anonymous"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
            crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"
            crossorigin="anonymous"></script>
        <script
            src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"
            crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <label for="namabarang">Nama Barang</label>
                        <input
                            type="text"
                            id="namabarang"
                            name="namabarang"
                            placeholder="Masukan Nama Barang"
                            class="form-control"
                            required="required"><br>

                        <label for="deskripsi">Kategori</label>
                        <input
                            type="text"
                            id="kategori"
                            name="kategori"
                            placeholder="Kategori Barang"
                            class="form-control"
                            required="required"><br>

                        <label for="stock">Stock</label>
                        <input
                            type="number"
                            id="stock"
                            name="stock"
                            placeholder="Masukan Stock Barang"
                            class="form-control"
                            required="required"><br>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</html>