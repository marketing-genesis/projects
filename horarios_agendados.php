<?php
// Conexão com o banco de dados (substitua pelos seus dados)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barbearia";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta SQL para obter os horários agendados
$sql = "SELECT * FROM horariosagendados";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Array para armazenar os dados dos horários agendados
    $horarios_agendados = array();

    // Loop pelos resultados da consulta
    while($row = $result->fetch_assoc()) {
        // Adiciona os dados de cada horário agendado ao array
        $horario = array(
            "dia" => $row["dia_semana"],
            "horario" => $row["horario"],
            "status" => $row["status"]
        );
        array_push($horarios_agendados, $horario);
    }

    // Retorna os dados como JSON
    echo json_encode($horarios_agendados);
} else {
    echo "0 resultados";
}
$conn->close();
?>
