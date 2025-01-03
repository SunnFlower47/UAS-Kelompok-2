# KELOMPOK 2 UAS STT WATUKANCANA TEKNIK INFORMATIKA 2025(semester 1)
* `RIDWAN ANDRIAN(241351144)`
* `SAVRIDA YAHROSANI(241351111)`
* `FARIS ZUFAR ARKANANTA(241351127)`


# Struktur Database

Database projec ini terdiri dari 4 tabel, yaitu `stock`, `masuk`, `keluar`, dan `login`.

**Tabel `stock`**

Tabel ini berisi informasi tentang stock yang ada di gudang. Kolom-kolom yang terdapat pada tabel ini adalah:

* `idbarang`: id unik untuk setiap barang
* `namabarang`: nama barang
* `kategori`: kategori barang
* `stock`: jumlah stock yang tersedia

**Tabel `masuk`**

Tabel ini berisi informasi tentang barang yang masuk ke gudang. Kolom-kolom yang terdapat pada tabel ini adalah:

* `idmasuk`: id unik untuk setiap transaksi
* `idbarang`: id barang yang diterima
* `qty`: jumlah barang yang diterima
* `distributor`: nama distributor yang mengirimkan barang
* `tanggal`: tanggal transaksi

**Tabel `keluar`**

Tabel ini berisi informasi tentang barang yang keluar dari gudang. Kolom-kolom yang terdapat pada tabel ini adalah:

* `idkeluar`: id unik untuk setiap transaksi
* `idbarang`: id barang yang dikeluarkan
* `qty`: jumlah barang yang dikeluarkan
* `penerima`: nama penerima yang menerima barang
* `tanggal`: tanggal transaksi

**Tabel `login`**

Tabel ini berisi informasi tentang user yang terdaftar. Kolom-kolom yang terdapat pada tabel ini adalah:

* `iduser`: id unik untuk setiap user
* `email`: email user
* `password`: password user
* `create_at`: tanggal saat user terdaftar


# ini untuk detail pembuatan database'nya
* -- Create Database
* CREATE DATABASE stockbarang_db;

* -- Create Table `stock`
* CREATE TABLE stock (
*    idbarang INT NOT NULL AUTO_INCREMENT,
*    namabarang VARCHAR(255) NOT NULL,
*    kategori VARCHAR(50) NOT NULL,
*    stock INT NOT NULL,
*    PRIMARY KEY (idbarang)
* );

* -- Create Table `masuk`
* CREATE TABLE masuk (
*    idmasuk INT NOT NULL AUTO_INCREMENT,
*    idbarang INT NOT NULL,
*    qty INT NOT NULL,
*    distributor VARCHAR(50) NOT NULL,
*    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
*    PRIMARY KEY (idmasuk)
* );

* -- Create Table `keluar`
* CREATE TABLE keluar (
*    idkeluar INT NOT NULL AUTO_INCREMENT,
*    idbarang INT NOT NULL,
*    qty INT NOT NULL,
*    penerima VARCHAR(50) NOT NULL,
*    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
*    PRIMARY KEY (idkeluar)
* );

* -- Create Table `login`
* CREATE TABLE login (
*    iduser INT NOT NULL AUTO_INCREMENT,
*    email VARCHAR(150) NOT NULL,
*    password VARCHAR(50) NOT NULL,
*    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
*    PRIMARY KEY (iduser)
* );

# FITUR WEBSITE
- register > daftar > login (harus login dulu)
- setelah daftar lalu coba login
- tidak bisa masuk index tanpa login
- setelah login tidak bisa kembali ke login harus logout dulu

- ada daftar barang dan stok 
- ada barang masuk untuk menginput barang masuk
- ada barang keluar juga untuk melacak barang yang keluar dari stock barang

- filter barang (sesuai tanggal)
- export data (pake internet)
- buat export data tabel bisa ke exel, pdf, dan print langsung
