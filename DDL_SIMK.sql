CREATE DATABASE IF NOT EXISTS login_db;
USE login_db;

CREATE TABLE divisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE jabatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    divisi_id INT,
    jabatan_id INT,
    nama VARCHAR(150),
    nik VARCHAR(20),
    email VARCHAR(100),
    alamat TEXT,
    telepon VARCHAR(20),
    foto VARCHAR(255) DEFAULT 'uploads/default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_user_karyawan FOREIGN KEY (username) 
        REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
        
    CONSTRAINT fk_divisi FOREIGN KEY (divisi_id) 
        REFERENCES divisi(id) ON DELETE SET NULL,
        
    CONSTRAINT fk_jabatan FOREIGN KEY (jabatan_id) 
        REFERENCES jabatan(id) ON DELETE SET NULL
) ENGINE=InnoDB;