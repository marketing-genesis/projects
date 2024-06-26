<?php
// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "root", "", "barbearia");

// Verifica a conexão
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Consulta SQL para obter os cortes de cabelo
$sql = "SELECT * FROM cortes_cabelo";
$result = mysqli_query($conn, $sql);

// Verifica se existem cortes de cabelo
if (mysqli_num_rows($result) > 0) {
    // Array para armazenar os dados dos cortes de cabelo
    $cortes_cabelo = array();

    // Itera sobre os resultados da consulta
    while ($row = mysqli_fetch_assoc($result)) {
        // Monta o caminho completo da imagem
        $imagem = "caminho_para_imagens/" . $row['imagem'];
        
        // Atualiza o valor do campo 'imagem' no array de cortes de cabelo
        $row['imagem'] = $imagem;

        // Adiciona os dados do corte de cabelo ao array
        $cortes_cabelo[] = $row;
    }
} else {
    // Se não houver cortes de cabelo, retorna um JSON vazio
    $cortes_cabelo = array();
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);

// Retorna os dados dos cortes de cabelo como JSON
return $cortes_cabelo;
?>
