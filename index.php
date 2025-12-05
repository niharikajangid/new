<?php include "db.php"; ?>

<!DOCTYPE html>
<html>
<head>
<title>Personal Budget Management System</title>
<style>
body{ font-family: Times New Roman; background:#e9f1ff; }
.container{ width:520px; background:white; margin:40px auto; padding:25px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.2); }
input,select{ width:100%; padding:10px; margin:5px 0; border-radius:5px; }
button{ padding:10px; width:100%; background:#007bff; color:white; border:none; margin-top:5px; cursor:pointer; border-radius:5px; }
button:hover{ background:#0056c7; }
table{ width:100%; border-collapse:collapse; margin-top:20px; }
td,th{ border:1px solid #ddd; padding:8px; text-align:center; }
h2, h3{text-align:center;}
.delete-btn{ background:#e91e1e; padding:5px 10px; color:white; border-radius:4px; text-decoration:none; }
.filter-box{ background:#f1f7ff; padding:10px; margin-top:15px; border-radius:6px; }
</style>
</head>
<body>

<div class="container">
<h2>Expense Calculater.</h2>


<form method="POST">
    <input type="text" name="title" placeholder="Enter Title" required>
    <input type="number" name="amount" placeholder="Enter Amount" required>
    <select name="type" required>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select>
    <button type="submit" name="add">Add Transaction</button>
</form>

<?php

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    mysqli_query($conn, "INSERT INTO transactions(title, amount, type) VALUES('$title', '$amount', '$type')");
    echo "<script>location.href='index.php';</script>";
}


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM transactions WHERE id=$id");
    echo "<script>location.href='index.php';</script>";
}
?>


<div class="filter-box">
<form method="GET">
    <label>Select Month:</label>
    <input type="month" name="month" value="<?php if(isset($_GET['month'])) echo $_GET['month']; ?>">
    <button type="submit">Filter</button>
</form>
</div>

<table>
<tr>
    <th>Title</th>
    <th>Amount</th>
    <th>Type</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php
$income = 0;
$expense = 0;

if(isset($_GET['month']) && $_GET['month'] != ""){
    $m = $_GET['month'];
    $sql = "SELECT * FROM transactions WHERE DATE_FORMAT(created_at, '%Y-%m') = '$m' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM transactions ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)){

    if($row['type'] == "income") $income += $row['amount'];
    if($row['type'] == "expense") $expense += $row['amount'];

    echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['type']}</td>
            <td>{$row['created_at']}</td>
            <td><a class='delete-btn' href='index.php?delete={$row['id']}'>Delete</a></td>
          </tr>";
}
?>
</table>

<!-- BALANCE -->
<h3>Total Income: ₹ <?php echo $income; ?></h3>
<h3>Total Expense: ₹ <?php echo $expense; ?></h3>
<h2 style="color:blue;">Balance: ₹ <?php echo $income - $expense; ?></h2>

</div>
</body>
</html>
