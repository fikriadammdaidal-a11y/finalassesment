<?php
include "db.php";

// GET STUDENT ID
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid Result ID!");
}

// GET EXISTING STUDENT DATA
$sql = $conn->prepare(
    "SELECT * FROM results WHERE result_id = ?"
);
$sql->bind_param("i", $id);
$sql->execute();

$row = $sql->get_result()->fetch_assoc();

if (!$row) {
    die("Record not found!");
}

// UPDATE STUDENT RESULT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_name = $_POST['student_name'];
    $matric_no = $_POST['matric_no'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $test1 = (float) $_POST['test1'];
    $test2 = (float) $_POST['test2'];
    $final = (float) $_POST['final'];

    // AUTO CALCULATE TOTAL
    $total = ($test1 * 0.20) +
        ($test2 * 0.30) +
        ($final * 0.50);

    // AUTO CALCULATE GRADE
    if ($total >= 80) {
        $grade = "A";
    } elseif ($total >= 70) {
        $grade = "B";
    } elseif ($total >= 60) {
        $grade = "C";
    } elseif ($total >= 50) {
        $grade = "D";
    } else {
        $grade = "F";
    }

    // UPDATE DATABASE
    $update = $conn->prepare(
        "UPDATE results SET
        student_name = ?,
        matric_no = ?,
        subject_name = ?,
        subject_code = ?,
        test1 = ?,
        test2 = ?,
        final_exam = ?,
        total_marks = ?,
        grade = ?
        WHERE result_id = ?"
    );

    $update->bind_param(
        "ssssddddsi",
        $student_name,
        $matric_no,
        $subject_name,
        $subject_code,
        $test1,
        $test2,
        $final,
        $total,
        $grade,
        $id
    );

    if ($update->execute()) {
        header("Location: index.php");
        exit();
    } else {
        die("Update failed!");
    }
}

include "header.php";
?>

<h2>Edit Result</h2>

<form method="post">

    <label>Student Name</label>
    <input type="text" name="student_name" value="<?php echo htmlspecialchars($row['student_name']); ?>" required>
    <br><br>

    <label>Matric Number</label>
    <input type="text" name="matric_no" value="<?php echo htmlspecialchars($row['matric_no']); ?>" required>
    <br><br>

    <label>Subject Name</label>
    <input type="text" name="subject_name" value="<?php echo htmlspecialchars($row['subject_name']); ?>" required>
    <br><br>

    <label>Subject Code</label>
    <input type="text" name="subject_code" value="<?php echo htmlspecialchars($row['subject_code']); ?>" required>
    <br><br>

    <label>Test 1 (20%)</label>
    <input type="number" name="test1" min="0" max="100" step="0.01"
        value="<?php echo htmlspecialchars($row['test1']); ?>" required>
    <br><br>

    <label>Test 2 (30%)</label>
    <input type="number" name="test2" min="0" max="100" step="0.01"
        value="<?php echo htmlspecialchars($row['test2']); ?>" required>
    <br><br>

    <label>Final Exam (50%)</label>
    <input type="number" name="final" min="0" max="100" step="0.01"
        value="<?php echo htmlspecialchars($row['final_exam']); ?>" required>
    <br><br>

    <input type="submit" name="submit" value="Update Result">

</form>

<?php include "footer.php"; ?>