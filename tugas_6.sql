
DROP database IF EXISTS   tugas_6;
create database IF NOT EXISTS tugas_6;

use tugas_6;
	


-- =====================================================================
-- 1. MEMBUAT TABEL
-- =====================================================================

-- Hapus tabel anak terlebih dahulu jika ada (urutan penting karena FK)
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Products;

CREATE TABLE Products (
    produk_id    INT(11) NOT NULL AUTO_INCREMENT,
    nama_produk  VARCHAR(100),
    harga        INT(11),
    deskripsi    TEXT,
    stok         INT(11),
    PRIMARY KEY (produk_id)
);

CREATE TABLE Users (
    user_id   INT(11) NOT NULL AUTO_INCREMENT,
    nama      VARCHAR(100),
    email     VARCHAR(100),
    password  VARCHAR(100),
    PRIMARY KEY (user_id)
);

-- Tabel orders dengan ON DELETE CASCADE dan ON UPDATE CASCADE
CREATE TABLE orders (
    order_id   INT(11) NOT NULL AUTO_INCREMENT,
    user_id    INT(11) NOT NULL,
    produk_id  INT(11) NOT NULL,
    quantity   INT(11) NOT NULL,
    Total      INT(11) NOT NULL,
    PRIMARY KEY (order_id),
    CONSTRAINT fk_orders_user 
        FOREIGN KEY (user_id) REFERENCES Users(user_id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_orders_produk 
        FOREIGN KEY (produk_id) REFERENCES Products(produk_id) 
        ON DELETE CASCADE ON UPDATE CASCADE
);


-- =====================================================================
-- 2. MEMBUAT DATA DUMMY (DETERMINISTIK) MENGGUNAKAN STORED PROCEDURE
-- =====================================================================

DELIMITER $$

-- SEED PRODUCTS (500 Data)
DROP PROCEDURE IF EXISTS seed_products$$
CREATE PROCEDURE seed_products()
BEGIN
    DECLARE i          INT DEFAULT 1;
    DECLARE v_kategori VARCHAR(50);
    DECLARE v_dasar    VARCHAR(50);
    DECLARE v_merk     VARCHAR(30);
    DECLARE v_seri     INT;
    DECLARE v_harga    INT;
    DECLARE v_stok     INT;

    WHILE i <= 500 DO
        SET v_kategori = ELT(1 + ((i - 1) MOD 10), 'Elektronik','Pakaian','Makanan','Minuman','Olahraga','Rumah Tangga','Kecantikan','Mainan','Otomotif','Buku');
        SET v_dasar = ELT(1 + ((i - 1) MOD 20), 'Kaos Polos','Kemeja Formal','Sepatu Sneakers','Tas Ransel','Blender','Rice Cooker','Headset Bluetooth','Mouse Wireless','Keyboard Mekanik','Smartwatch','Jam Dinding','Lampu LED','Panci Anti Lengket','Botol Minum','Kopi Bubuk','Teh Celup','Masker Wajah','Sabun Cair','Boneka','Mainan Edukasi');
        SET v_merk = ELT(1 + ((i - 1) MOD 25), 'Andalas','Borneo','Citra','Dunia','Elok','Fajar','Gemilang','Harapan','Indah','Jaya','Karya','Lestari','Mulia','Nusa','Omega','Prima','Qiana','Rasa','Sinar','Tirta','Utama','Vega','Wira','Xylo','Yuda');
        SET v_seri  = CEIL(i / 20);
        
        -- Deterministik: (i * 7919) MOD 1490000, dibulatkan ke ribuan, + 10.000
        SET v_harga = 10000 + ROUND(((i * 7919) MOD 1490000) / 1000) * 1000;
        SET v_stok  = (i * 37) MOD 500;

        INSERT INTO Products (nama_produk, harga, deskripsi, stok)
        VALUES (
            CONCAT(v_dasar, ' ', v_merk, ' Seri ', v_seri),
            v_harga,
            CONCAT(v_dasar, ' ', v_merk, ' Seri ', v_seri, ' adalah produk kategori ', v_kategori, ' dengan kualitas terjamin. Cocok untuk kebutuhan sehari-hari, bergaransi resmi, dan siap dikirim ke seluruh Indonesia.'),
            v_stok
        );
        SET i = i + 1;
    END WHILE;
END$$

-- SEED USERS (500 Data)
DROP PROCEDURE IF EXISTS seed_users$$
CREATE PROCEDURE seed_users()
BEGIN
    DECLARE i           INT DEFAULT 1;
    DECLARE v_depan     VARCHAR(50);
    DECLARE v_belakang  VARCHAR(50);

    WHILE i <= 500 DO
        SET v_depan = ELT(1 + ((i - 1) MOD 25), 'Budi','Siti','Agus','Dewi','Rizky','Putri','Andi','Rina','Fajar','Nur','Hendra','Maya','Eko','Intan','Bayu','Ayu','Dimas','Sari','Yoga','Lestari','Taufik','Wulan','Galih','Fitri','Reza');
        SET v_belakang = ELT(1 + (FLOOR((i - 1) / 25) MOD 20), 'Santoso','Wijaya','Pratama','Nugroho','Saputra','Hidayat','Kusuma','Ramadhan','Setiawan','Permata','Anggraini','Maulana','Firmansyah','Halim','Susanti','Purnama','Siregar','Simbolon','Hartono','Gunawan');

        INSERT INTO Users (nama, email, password)
        VALUES (
            CONCAT(v_depan, ' ', v_belakang),
            CONCAT(LOWER(v_depan), '.', LOWER(v_belakang), i, '@mail.com'),
            SHA2(CONCAT('password', i), 256)
        );
        SET i = i + 1;
    END WHILE;
END$$

-- SEED ORDERS (500 Data)
DROP PROCEDURE IF EXISTS seed_orders$$
CREATE PROCEDURE seed_orders()
BEGIN
    DECLARE i        INT DEFAULT 1;
    DECLARE v_user   INT;
    DECLARE v_produk INT;
    DECLARE v_qty    INT;
    DECLARE v_harga  INT;

    WHILE i <= 500 DO
        -- Formula deterministik untuk memilih user & produk secara acak tapi konsisten
        SET v_user   = 1 + ((i * 37) MOD 500);
        SET v_produk = 1 + ((i * 53) MOD 500);
        SET v_qty    = 1 + ((i - 1) MOD 5); 

        SELECT harga INTO v_harga FROM Products WHERE produk_id = v_produk;

        INSERT INTO orders (user_id, produk_id, quantity, Total)
        VALUES (v_user, v_produk, v_qty, v_qty * v_harga);
        
        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

-- =====================================================================
-- 3. EKSEKUSI PENGISIAN DATA & menghapus procedure
-- =====================================================================

-- Panggil procedure untuk mengisi data
CALL seed_products();
CALL seed_users();
CALL seed_orders();

-- Hapus procedure setelah digunakan agar database tetap rapi
DROP PROCEDURE seed_products;
DROP PROCEDURE seed_users;
DROP PROCEDURE seed_orders;



-- ------------------------------------------------------------------

/*
Menampilkan :
order_id,
nama produk yang dibeli,
harga barang
deskripsi
nama user yang membeli ,
jumlah pembelian
total

yang mana harga barang yang di beli diantara harga 31.000 sampai 76.000
dan diurutkan berdasarkan nama user yang membeli secara descending
*/
Select 
    o.order_id,
    p.nama_produk as 'nama produk yang dibeli',
    p.harga as 'harga barang',
    u.nama as 'nama user yang membeli',
    o.quantity as  'jumlah pembelian',
    o.total
FROM
    Products p,
    Users u,
    Orders o 
where 
    u.user_id = o.user_id AND
    p.produk_id = o.produk_id AND
    p.harga BETWEEN 31000 and 76000
ORDER BY u.nama DESC ;


/*
Rubah lah data nama produk menjadi 'nama_produk_berubah' dan quantity menjadi 100
yang mana harganya adalah 76000 atau harga 31.0000
*/
update 
    Products p,  
    Users u,
    Orders o 
Set 
    p.nama_produk = 'nama_produk_berubah',
    o.quantity =  '100'
where 
    u.user_id = o.user_id AND
    p.produk_id = o.produk_id AND
    p.harga in (76000,31000);

/*
Delete semua data dari tiga tabel itu yang mana user yang beli 
bernama 'Yoga Maulana' atau yang quantity kurang dari 5
*/

DELETE o, u, p
FROM orders o, Users u, Products p
where 
    u.user_id = o.user_id AND
    p.produk_id = o.produk_id AND
    (
        u.nama like '%Yoga Maulana%' or 
        o.quantity < 5
    );

