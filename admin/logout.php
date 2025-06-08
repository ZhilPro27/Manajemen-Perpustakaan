<?php
// admin/logout.php

session_start();

// Menghapus semua data session
session_unset();

// Menghancurkan session
session_destroy();

// Mengarahkan ke halaman utama publik (index.php di root)
header("Location: ../index.php");
exit;
?>