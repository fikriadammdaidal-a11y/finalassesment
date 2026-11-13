
<?php
include "db.php";

// GET RESULT ID
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid Result ID!");
}

// DELETE STUDENT RESULT
$sql = $conn->prepare(
    "DELETE FROM results WHERE result_id = ?"
);

$sql->bind_param("i", $id);

if ($sql->execute()) {
    header("Location: index.php");
    exit();
} else {
    die("Error deleting result!");
}
?>
