<?php

$conn = mysqli_connect("localhost", "root", "", "barbearia");

if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}


$sql = "SELECT * FROM produtos";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
     $produtos = array();
    while ($row = mysqli_fetch_assoc($result)) {   
        $imagem = "uploads/" . $row['imagem'];
        $row['imagem'] = $imagem;
        
        $produtos[] = $row;
    }

} else {
    $produtos = array();
}

mysqli_close($conn);
return $produtos;
?>
