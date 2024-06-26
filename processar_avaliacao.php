<?php
// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $conn = mysqli_connect("localhost", "root", "", "barbearia");

    // Verifica a conexão
    if (!$conn) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $corte = $_POST['corte'];
    $atendimento = $_POST['atendimento'];
    $comentarios = $_POST['comentarios'];

    // Prepara a instrução SQL para inserir os dados na tabela 'avaliacoes'
    $sql = "INSERT INTO avaliacoes (nome_cliente, corte, atendimento, comentarios) VALUES ('$nome', '$corte', '$atendimento', '$comentarios')";

    // Executa a instrução SQL e verifica se foi bem-sucedida
    if (mysqli_query($conn, $sql)) {
        echo "Avaliação enviada com sucesso!";
    } else {
        echo "Erro ao enviar avaliação: " . mysqli_error($conn);
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conn);
} else {
    // Se o formulário não foi enviado via método POST, redireciona para a página de erro
    header("Location: erro.php");
    exit();
}
?>
