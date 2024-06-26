<?php
$conn = mysqli_connect("localhost", "root", "", "barbearia");
$sql = "SELECT * FROM carrossel";
$result = mysqli_query($conn, $sql);
$carrossel_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $carrossel_data[] = $row;
}
mysqli_close($conn);
?>