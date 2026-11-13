
<?php
include_once "db.php";

$search = trim($_GET['search'] ?? '');

if ($search != '') {

    $sql = "SELECT * FROM results
            WHERE student_name LIKE ?
            OR matric_no LIKE ?
            OR subject_name LIKE ?
            OR subject_code LIKE ?
            ORDER BY result_id DESC";

    $stmt = $conn->prepare($sql);

    $keyword = "%$search%";

    $stmt->bind_param(
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );

} else {

    $sql = "SELECT * FROM results ORDER BY result_id DESC";

    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

include "header.php";
?>

<h2>Student Records</h2>

<p><a href="add.php">+ Add Result</a></p>

<form method="GET" action="index.php">

    <input type="text"
           name="search"
           placeholder="Search student..."
           value="<?php echo htmlspecialchars($search); ?>">

    <button type="submit">Search</button>

    <a href="index.php">Show all</a>

</form>

<br>

<table border="1" cellpadding="5">

<tr>
    <th>No.</th>
    <th>Student Name</th>
    <th>Matric No.</th>
    <th>Subject</th>
    <th>Test 1 (20%)</th>
    <th>Test 2 (30%)</th>
    <th>Final (50%)</th>
    <th>Total</th>
    <th>Grade</th>
    <th>Actions</th>
</tr>

<?php
$no = 1;

while ($row = $result->fetch_assoc()) {
?>

<tr>
    <td><?php echo $no++; ?></td>

    <td><?php echo htmlspecialchars($row['student_name']); ?></td>

    <td><?php echo htmlspecialchars($row['matric_no']); ?></td>

    <td>
        <?php
        echo htmlspecialchars($row['subject_name']);
        echo " (";
        echo htmlspecialchars($row['subject_code']);
        echo ")";
        ?>
    </td>

    <td><?php echo $row['test1']; ?></td>

    <td><?php echo $row['test2']; ?></td>

    <td><?php echo $row['final_exam']; ?></td>

    <td><?php echo $row['total_marks']; ?></td>

    <td><?php echo htmlspecialchars($row['grade']); ?></td>

    <td>
        <a href="edit.php?id=<?php echo $row['result_id']; ?>">
            Edit
        </a>

        |

        <a href="delete.php?id=<?php echo $row['result_id']; ?>"
           onclick="return confirm('Delete this record?');">
            Delete
        </a>
    </td>
</tr>

<?php
}
?>

</table>

<?php
if ($result->num_rows == 0) {
    echo "<p>No student records found.</p>";
}
?>

<?php include "footer.php"; ?>
