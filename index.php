<?php

$host = 'sql203.infinityfree.com';
$db   = 'if0_41081413_frdsrank_db';
$user = 'myusernamee';
$pass = 'mypasss';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed");
}

$current_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $roll_number = $_POST['roll_number'];
    $university_name = $_POST['university_name'];
    $cgpa = $_POST['cgpa'];

    $insert = "
        INSERT INTO student_details 
        (name, age, roll_number, university_name, cgpa)
        VALUES ('$name','$age','$roll_number','$university_name','$cgpa')
    ";

    if ($conn->query($insert)) {
        $current_id = $conn->insert_id;
    }
}


$ranking = $conn->query("
    SELECT * FROM student_details
    ORDER BY cgpa DESC, name DESC
");

$user_rank = null;
$total = 0;
$current_name = '';
$current_cgpa = '';

if ($ranking) {
    $position = 1;
    while ($row = $ranking->fetch_assoc()) {
        $total++;

        if ($row['id'] == $current_id) {
            $user_rank = $position;
            $current_name = $row['name'];
            $current_cgpa = $row['cgpa'];
        }
        $position++;
    }

  
    $ranking->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Student Ranking</title>


   
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4 mb-4">
        <h3 class="text-center mb-4">Student Marks Entry</h3>

        <form method="POST" action="index.php">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Roll Number</label>
                <input type="text" name="roll_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label>University Name</label>
                <input type="text" name="university_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>CGPA</label>
                <input type="text" name="cgpa" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Generate Report
            </button>
        </form>
    </div>

    <!-- USER RESULT -->
    <?php if ($user_rank !== null) { ?>
        <div class="card p-4 mb-4 text-center">
            <h4>Your Result</h4>
            <p><strong>Name:</strong> <?php echo $current_name; ?></p>
            <p><strong>CGPA:</strong> <?php echo $current_cgpa; ?></p>
           
        </div>
    <?php } ?>

    <h4 class="text-center">Overall Ranking</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>CGPA</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $pos = 1;
        while ($row = $ranking->fetch_assoc()) {
            $highlight = ($row['id'] == $current_id) ? 'table-success' : '';
        ?>
            <tr class="<?php echo $highlight; ?>">
                <td><?php echo $pos++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['cgpa']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>
