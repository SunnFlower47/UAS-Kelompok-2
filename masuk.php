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
        <title>Barang Masuk</title>
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
                        <h1 class="mt-4">Barang Masuk</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#myModal">
                                    Barang Masuk
                                </button>
                                <a href="exportMasuk.php" class="btn btn-info">Export data</a>
                            </div>
                            <div calas="row">
                                <div class="col mt-3">
                                    <form method="post" class="form-inline">
                                        <input type="date" name="tanggal_mulai" class="form-control mr-3">
                                        <input type="date" name="tanggal_selesai" class="form-control mr-3">
                                        <button type="submit" class="btn btn-primary mr-3" name="filter-tanggal">Filter</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Barang</th>
                                                <th>Distributor</th>
                                                <th>Quantity</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            
                                            if(isset($_POST['filter-tanggal'])){
                                                $tanggal_mulai = $_POST['tanggal_mulai'];
                                                $tanggal_selesai = $_POST['tanggal_selesai'];

                                                if($tanggal_mulai!=null || $tanggal_selesai=null){
                                                    $ambilsemuadatastock = mysqli_query($con,"select * from masuk m, stock s where s.idbarang = m.idbarang and m.tanggal BETWEEN '$tanggal_mulai' AND DATE_ADD('$tanggal_selesai', INTERVAL 1 DAY) ORDER BY m.tanggal DESC");
                                                } else {
                                                    $ambilsemuadatastock = mysqli_query($con,"select * from masuk m, stock s where s.idbarang = m.idbarang");
                                                }
                                                
                                                
                                            } else {
                                                $ambilsemuadatastock = mysqli_query($con,"select * from masuk m, stock s where s.idbarang = m.idbarang");
                                            }
                                            

                                            while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                $tanggal = $data['tanggal'];
                                                $namabarang = $data['namabarang'];
                                                $distributor = $data['distributor'];
                                                $qty = $data['qty'];
                                                $idb = $data['idbarang'];
                                                $idm = $data['idmasuk'];
                                            ?>
                                            <tr>
                                                <td><?=$tanggal?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$distributor;?></td>
                                                <td><?=$qty;?></td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        class="btn btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#edit<?=$idm;?>">
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
                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="edit<?=$idm;?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Barang</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label for="namabarang">Nama Barang</label>
                                                                <input
                                                                    type="text"
                                                                    name="namabarang"
                                                                    value="<?=$namabarang;?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <label for="distributor">Distibutor</label>
                                                                <input
                                                                    type="text"
                                                                    name="distributor"
                                                                    value="<?=$distributor;?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <label for="qty">Stok Masuk</label>
                                                                <input
                                                                    type="number"
                                                                    name="qty"
                                                                    value="<?=$qty;?>"
                                                                    class="form-control"
                                                                    required="required"><br>
                                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                <input type="hidden" name="idm" value="<?=$idm;?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-warning" name="updatebarangmasuk">Confirm Update</button>
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
                                                                <!-- Hidden inputs to pass idb (item ID) and qty (quantity) -->
                                                                <input type="hidden" name="idb" value="<?=$idb?>">
                                                                <input type="hidden" name="qty" value="<?=$qty?>">
                                                                <input type="hidden" name="idm" value="<?=$idm?>">
                                                                <!-- Assuming you also need the ID for 'masuk' -->
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="deletebarangmasuk">Hapus</button>
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
                        <label for="barangnya">Nama Barang</label>
                        <select name="barangnya" id="" class="form-control">
                            <?php
                        $ambildata = mysqli_query($con, "select * from stock");
                        while($fetcharray = mysqli_fetch_array($ambildata)) {
                            $namabarangnya = $fetcharray['namabarang'];
                            $idbarangnya = $fetcharray['idbarang'];
                        ?>
                            <option value="<?=$idbarangnya?>"><?=$namabarangnya?></option>
                            <?php
                        }
                        ?>
                        </select><br>

                        <label for="stock">Quantity</label>
                        <input
                            type="number"
                            id="qty"
                            name="qty"
                            placeholder="Jumlah Quantity"
                            class="form-control"
                            required="required"><br>
                        <label for="distributor">Distributor</label>
                        <input
                            type="text"
                            id="distributor"
                            name="distributor"
                            placeholder="Distributor"
                            class="form-control"
                            required="required"><br>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="barangmasuk">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</html>