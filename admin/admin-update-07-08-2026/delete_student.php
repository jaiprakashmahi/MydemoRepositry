<?php
include 'conn.php';
$id = $_GET['id'];
$conn->query("DELETE FROM onlinestudents WHERE id = $id");
header("Location: RegStudentList.php"); // or wherever your list is
