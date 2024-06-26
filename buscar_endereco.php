<?php
// Conexão com o banco de dados
$servername = "localhost"; // Nome do servidor
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "barbearia"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta para buscar o endereço na tabela informacoes_barbearia
$sql = "SELECT endereco, cidade, cep, estado FROM informacoes_barbearia";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Saída de dados de cada linha
    $row = $result->fetch_assoc();
    $endereco = $row["endereco"] . ', ' . $row["cidade"] . ', ' . $row["estado"] . ', ' . $row["cep"];
    echo $endereco;
} else {
    echo "0 resultados";
}

$conn->close();
?>
