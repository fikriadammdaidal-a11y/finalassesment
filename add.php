
<?php
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = trim($_POST['student_name'] ?? '');
    $matric_no = trim($_POST['matric_no'] ?? '');
    $subject_name = trim($_POST['subject_name'] ?? '');
    $subject_code = trim($_POST['subject_code'] ?? '');

    $test1 = $_POST['test1'] ?? '';
    $test2 = $_POST['test2'] ?? '';
    $final = $_POST['final'] ?? '';

    if (
        $student_name == '' ||
        $matric_no == '' ||
        $subject_name == '' ||
        $subject_code == '' ||
        !is_numeric($test1) ||
        !is_numeric($test2) ||
        !is_numeric($final)
    ) {
        $error = "Please fill in all fields correctly!";
    } else {

        $test1 = (float)$test1;
        $test2 = (float)$test2;
        $final = (float)$final;

        // Calculate total automatically
        $total = $test1 + $test2 + $final;

        // Calculate grade automatically
        if ($total >= 80) {
            $grade = 'A';
        } elseif ($total >= 65) {
            $grade = 'B';
        } elseif ($total >= 50) {
            $grade = 'C';
        } elseif ($total >= 40) {
            $grade = 'D';
        } else {
            $grade = 'E';
        }

        // Insert student result
        $sql = "INSERT INTO results
            (student_name, matric_no, subject_name,
             subject_code, test1, test2,
             final_exam, total_marks, grade)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssdddds",
                $student_name,
                $matric_no,
                $subject_name,
                $subject_code,
                $test1,
                $test2,
                $final,
                $total,
                $grade
            );

            $stmt->execute();

            header("Location: index.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                $error = "This student already has a result for this subject!";
            } else {
                error_log($e->getMessage());
                $error = "Unable to save result. Please check the database.";
            }
        }
    }
}
?>

<?php include "header.php"; ?>

<style>
label {
    display: inline-block;
    width: 240px;
}
</style>

<h2>Add Result</h2>

<?php
if (!empty($error)) {
    echo "<p style='color:red;'>"
        . htmlspecialchars($error) .
        "</p>";
}

include "form.php";
include "footer.php";
?>
