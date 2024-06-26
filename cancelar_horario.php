<?php
// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário de cancelamento
    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];
    $dia = $_POST["dia"];
    $hora = $_POST["hora"];

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($telefone) && !empty($dia) && !empty($hora)) {
        // Conecta-se ao banco de dados (substitua os valores pelos seus próprios)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "barbearia";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica se a conexão foi estabelecida com sucesso
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Prepara e executa a consulta SQL para verificar se o horário agendado existe
        $sql = "SELECT * FROM horariosagendados WHERE nome_cliente='$nome' AND telefone='$telefone' AND dia_semana='$dia' AND horario='$hora'";
        $result = $conn->query($sql);

        // Verifica se o horário agendado existe
        if ($result->num_rows > 0) {
            // Horário agendado encontrado, então procedemos com a exclusão
            $sql_delete = "DELETE FROM horariosagendados WHERE nome_cliente='$nome' AND telefone='$telefone' AND dia_semana='$dia' AND horario='$hora'";
            if ($conn->query($sql_delete) === TRUE) {
                echo "Horário agendado excluído com sucesso!";
            } else {
                echo "Erro ao excluir horário agendado: " . $conn->error;
            }
        } else {
            echo "Horário agendado não encontrado.";
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>
