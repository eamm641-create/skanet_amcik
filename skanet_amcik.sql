-- Database skanet_amcik (import lewat phpMyAdmin)
CREATE DATABASE IF NOT EXISTS skanet_amcik CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE skanet_amcik;

DROP TABLE IF EXISTS nilai;
DROP TABLE IF EXISTS mapel;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS guru;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE guru (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nip VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  no_hp VARCHAR(20) DEFAULT NULL,
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE siswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nis VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  kelas VARCHAR(20) NOT NULL,
  jk ENUM('L','P') NOT NULL,
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE mapel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(20) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  guru_id INT DEFAULT NULL,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE nilai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  siswa_id INT NOT NULL,
  mapel_id INT NOT NULL,
  tugas DECIMAL(5,2) NOT NULL DEFAULT 0,
  uts DECIMAL(5,2) NOT NULL DEFAULT 0,
  uas DECIMAL(5,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
  FOREIGN KEY (mapel_id) REFERENCES mapel(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO guru (nip,nama,no_hp,alamat) VALUES
('198001012005011001','Budi Santoso','081234567890','Nganjuk'),
('198505052010012002','Siti Aminah','081298765432','Kediri');

INSERT INTO siswa (nis,nama,kelas,jk,alamat) VALUES
('2024001','Andi Pratama','XI RPL 1','L','Nganjuk'),
('2024002','Dewi Lestari','XI RPL 1','P','Kertosono');

INSERT INTO mapel (kode,nama,guru_id) VALUES
('PWEB','Pemrograman Web',1),
('BDATA','Basis Data',2);

INSERT INTO nilai (siswa_id,mapel_id,tugas,uts,uas) VALUES
(1,1,85,80,90),(1,2,78,82,80),(2,1,90,88,92);
