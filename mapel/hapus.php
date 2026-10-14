<?php
require '../koneksi.php'; cek_login('../');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $s = $conn->prepare("DELETE FROM mapel WHERE id=?"); $s->bind_param('i',$id); $s->execute();
}
header('Location: index.php?msg=' . urlencode('Data mapel dihapus.'));
