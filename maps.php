<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barbearia";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT endereco, cidade, estado, cep FROM informacoes_barbearia WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $address = $row['endereco'] . ', ' . $row['cidade'] . ', ' . $row['estado'] . ' ' . $row['cep'];
    echo json_encode(array('status' => 'success', 'address' => $address));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Address not found'));
}

$stmt->close();
$conn->close();
?>