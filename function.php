<?php
session_start();

//db connection start
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stockbarang_db";

// buat koneksi ke mysql
$con = new mysqli($servername, $username, $password, $dbname);
//db connection end


//fungsi untuk id barang
function generateRandomId() {
    return mt_rand(10000, 99999);
}
//tambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $kategori = $_POST['kategori'];
    $stock = $_POST['stock'];

    // Generate a random 5-digit ID
    $idb = generateRandomId();

    // Insert the new barang into the database
    $query = "INSERT INTO stock (idbarang, namabarang, kategori, stock) VALUES ('$idb', '$namabarang', '$kategori', '$stock')";
    $result = mysqli_query($con, $query);

    if($result){
        echo "<script>alert('Barang Berhasil Ditambahkan');</script>";
        echo "<script>location='index.php';</script>";
    }else{
        echo "<script>alert('barang Gagal Ditambahkan');</script>";
    }
}


//fungsi barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $distributor = $_POST['distributor'];
    $qty = $_POST['qty'];

    // Check stock sekarang
    $cekstocksekarang = mysqli_query($con, "select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    // menghitung stock baru
    $tambakanstocksekarangdenganquantity = $stocksekarang + $qty;

    // Insert data ke table "masuk" 
    $addtomasuk = mysqli_query($con, "insert into masuk (idbarang,distributor,qty) values ('$barangnya','$distributor','$qty')");

    // Update stock di table "stock" 
    $updatestock = mysqli_query($con, "update stock set stock = '$tambakanstocksekarangdenganquantity' where idbarang = '$barangnya'");

    if ($addtomasuk && $updatestock) {
        header('location: masuk.php');
    } else {
        echo "Error: ";
        header('location: masuk.php');
    }
}
//barang masuk end

//fungsi barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['ppenerima'];
    $qty = $_POST['qty'];

     // Check stock sekarang
    $cekstocksekarang = mysqli_query($con, "select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        // menghitung barang keluar 
        $tambakanstocksekarangdenganquantity = $stocksekarang - $qty;

        // Insert data ke table "keluar" 
        $addtokeluar = mysqli_query($con, "insert into keluar (idbarang,penerima,qty) values ('$barangnya','$penerima','$qty')");

        // Update stock di table "stock" 
        $updatestock = mysqli_query($con, "update stock set stock = '$tambakanstocksekarangdenganquantity' where idbarang = '$barangnya'");

        if ($addtokeluar && $updatestock) {
            header('location: keluar.php');
        } else {
            echo "Error: ";
            header('location: keluar.php');
        }
    } else {
        echo "<script>alert('Stock tidak cukup'); window.location='keluar.php';</script>";
    }
}
//barang keluar end

//fungsi edit barang
if (isset($_POST['confirmupdate'])) {
    // Ambil data POST
    $idb = mysqli_real_escape_string($con, $_POST['idb']);
    $namabarang = mysqli_real_escape_string($con, $_POST['namabarang']);
    $kategori = mysqli_real_escape_string($con, $_POST['kategori']);
    $stock = mysqli_real_escape_string($con, $_POST['stock']);

    // Jalankan query
    $update = mysqli_query($con, "update stock set namabarang = '$namabarang', kategori = '$kategori', stock = '$stock' where idbarang = '$idb'");

    if ($update) {
        echo "<script>alert('Barang berhasil diupdate'); window.location='index.php';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}

// fungsi delete barang
if (isset($_POST['deletebarang'])) {
    $idb = $_POST['idb'];
    
    $hapus = mysqli_query($con, "delete from stock where idbarang = '$idb'");

    if ($hapus) {
        echo "<script>alert('Barang berhasil dihapus'); window.location='index.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
}

//fungsi edit barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = intval($_POST['idb']); 
    $idm = intval($_POST['idm']); 
    $distributor = mysqli_real_escape_string($con, $_POST['distributor']); 
    $qty = intval($_POST['qty']);

    // Ambil stok saat ini
    $stmt = $con->prepare("SELECT stock FROM stock WHERE idbarang = ?");
    $stmt->bind_param("i", $idb);
    $stmt->execute();
    $stmt->bind_result($stocksekarang);
    $stmt->fetch();
    $stmt->close();

    // Ambil stock saat ini
    $stmt = $con->prepare("SELECT qty FROM masuk WHERE idmasuk = ?");
    $stmt->bind_param("i", $idm);
    $stmt->execute();
    $stmt->bind_result($qtysekarang);
    $stmt->fetch();
    $stmt->close();

    if ($qty > $qtysekarang) {
        // Penambahan stok
        $selisih = $qty - $qtysekarang;
        if ($selisih <= $stocksekarang) {
            $tambahkan = $stocksekarang + $selisih; 
    
            // Perbarui stok
            $stmt = $con->prepare("UPDATE stock SET stock = ? WHERE idbarang = ?");
            $stmt->bind_param("ii", $tambahkan, $idb);
            $updateStock = $stmt->execute();
            $stmt->close();
    
            // Perbarui barang masuk
            $stmt = $con->prepare("UPDATE masuk SET qty = ?, distributor = ? WHERE idmasuk = ?");
            $stmt->bind_param("isi", $qty, $distributor, $idm);
            $updateMasuk = $stmt->execute();
            $stmt->close();
    
            if ($updateStock && $updateMasuk) {
                header('Location: masuk.php?status=success');
                exit;
            }
        } else {
            header('Location: masuk.php?status=error&message=Insufficient+stock');
            exit;
        }
    } else {
        // Pengurangan stok
        $selisih = $qtysekarang - $qty;
        $kurangi = $stocksekarang - $selisih; 
    
        // Perbarui stok
        $stmt = $con->prepare("UPDATE stock SET stock = ? WHERE idbarang = ?");
        $stmt->bind_param("ii", $kurangi, $idb);
        $updateStock = $stmt->execute();
        $stmt->close();
    
        // Perbarui barang masuk
        $stmt = $con->prepare("UPDATE masuk SET qty = ?, distributor = ? WHERE idmasuk = ?");
        $stmt->bind_param("isi", $qty, $distributor, $idm);
        $updateMasuk = $stmt->execute();
        $stmt->close();
    
        if ($updateStock && $updateMasuk) {
            header('Location: masuk.php?status=success');
            exit;
        }
    }
    
    // Jika terjadi kegagalan
    header('Location: masuk.php?status=error&message=Update+failed');
    exit;
}


//fungsi delete barang masuk
if (isset($_POST['deletebarangmasuk'])) {
    $idm = $_POST['idm'];  
    $qty = $_POST['qty'];  
    $idb = $_POST['idb'];  

    // Ambil data stok saat ini dari tabel stock
    $getdatastoke = mysqli_query($con, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastoke);
    $stoke = $data['stock'];  // Ambil stok saat ini

    // Kurangi stok berdasarkan kuantitas yang dihapus
    $new_stock = $stoke - $qty;  // Kurangi stok dengan kuantitas yang dihapus

    // Update stok di tabel stock
    $updateStok = mysqli_query($con, "UPDATE stock SET stock = '$new_stock' WHERE idbarang = '$idb'");

    // Hapus data dari tabel masuk
    $hapusdata = mysqli_query($con, "DELETE FROM masuk WHERE idmasuk = '$idm'");

    // Cek apakah update stok dan penghapusan data berhasil
    if ($updateStok && $hapusdata) {
        echo "<script>alert('Barang berhasil dihapus dan stok diperbarui'); window.location='masuk.php';</script>";
    } else {
        echo "<script>alert('Barang gagal dihapus'); window.location='masuk.php';</script>";
    }
}


//fungsi edit barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = intval($_POST['idb']); 
    $idk = intval($_POST['idk']);
    $penerima = mysqli_real_escape_string($con, $_POST['penerima']); 

    // Ambil stok saat ini
    $stmt = $con->prepare("SELECT stock FROM stock WHERE idbarang = ?");
    $stmt->bind_param("i", $idb);
    $stmt->execute();
    $stmt->bind_result($stocksekarang);
    $stmt->fetch();
    $stmt->close();

    // Ambil kuantitas keluar saat ini
    $stmt = $con->prepare("SELECT qty FROM keluar WHERE idkeluar = ?");
    $stmt->bind_param("i", $idk);
    $stmt->execute();
    $stmt->bind_result($qtysekarang);
    $stmt->fetch();
    $stmt->close();

    $qty = intval($_POST['qty']);

if ($qty > $qtysekarang) {
    // Penambahan barang keluar
    $selisih = $qty - $qtysekarang;
    if ($selisih <= $stocksekarang) {
        $kurangi = $stocksekarang - $selisih;

        // Perbarui stok
        $stmt = $con->prepare("UPDATE stock SET stock = ? WHERE idbarang = ?");
        $stmt->bind_param("ii", $kurangi, $idb);
        $updateStock = $stmt->execute();
            $stmt->close();

            // Perbarui barang keluar
            $stmt = $con->prepare("UPDATE keluar SET qty = ?, penerima = ? WHERE idkeluar = ?");
            $stmt->bind_param("isi", $qty, $penerima, $idk);
            $updateKeluar = $stmt->execute();
            $stmt->close();

            if ($updateStock && $updateKeluar) {
                header('Location: keluar.php?status=success');
                exit;
            }
        } else {
            header('Location: keluar.php?status=error&message=Insufficient+stock');
            exit;
        }
    } else {
        // Pengurangan barang keluar
        $selisih = $qtysekarang - $qty;
        $tambahkan = $stocksekarang + $selisih;

        // Perbarui stok
        $stmt = $con->prepare("UPDATE stock SET stock = ? WHERE idbarang = ?");
        $stmt->bind_param("ii", $tambahkan, $idb);
        $updateStock = $stmt->execute();
        $stmt->close();

        // Perbarui barang keluar
        $stmt = $con->prepare("UPDATE keluar SET qty = ?, penerima = ? WHERE idkeluar = ?");
        $stmt->bind_param("isi", $qty, $penerima, $idk);
        $updateKeluar = $stmt->execute();
        $stmt->close();

        if ($updateStock && $updateKeluar) {
            header('Location: keluar.php?status=success');
            exit;
        }
    }

    // Jika terjadi kegagalan
    header('Location: keluar.php?status=error&message=Update+failed');
    exit;
}

//fungsi delete barang keluar
if (isset($_POST['deletebarangkeluar'])) {
    $idk = $_POST['idk']; 
    $qty = $_POST['qty']; 
    $idb = $_POST['idb'];  

    // Ambil data stok saat ini dari tabel stock
    $getdatastoke = mysqli_query($con, "SELECT * FROM stock WHERE idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastoke);
    $stoke = $data['stock'];  // Ambil stok saat ini

    // Tambahkan stok berdasarkan kuantitas yang dihapus
    $new_stock = $stoke + $qty;  // Tambahkan stok dengan kuantitas yang dihapus

    // Update stok di tabel stock
    $updateStok = mysqli_query($con, "UPDATE stock SET stock = '$new_stock' WHERE idbarang = '$idb'");

    // Hapus data dari tabel keluar
    $hapusdata = mysqli_query($con, "DELETE FROM keluar WHERE idkeluar = '$idk'");

    // Cek apakah update stok dan penghapusan data berhasil
    if ($updateStok && $hapusdata) {
        echo "<script>alert('Barang keluar berhasil dihapus dan stok diperbarui'); window.location='keluar.php';</script>";
    } else {
        echo "<script>alert('Barang keluar gagal dihapus'); window.location='keluar.php';</script>";
    }
}
//menampilkan siapa yang login
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];

    // Query untuk mengambil data dari database
    $query = "SELECT email FROM login WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userEmail = $user['email'];  // Ambil email dari database
    } else {
        $userEmail = 'Guest'; // Jika tidak ditemukan
    }

    $stmt->close();
} else {
    $userEmail = 'Guest'; // Jika tidak ada sesi email
}


?>  