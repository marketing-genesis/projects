<?php
// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos foram enviados
    if (isset($_POST['dia']) && isset($_POST['hora']) && isset($_POST['corte']) && isset($_POST['nome']) && isset($_POST['telefone']) && isset($_POST['email'])) {
        // Conecta ao banco de dados
        $conexao = new mysqli("localhost", "root", "", "barbearia");

        // Verifica se a conexão foi bem-sucedida
        if ($conexao->connect_error) {
            die("Erro de conexão: " . $conexao->connect_error);
        }

        // Prepara a consulta SQL para inserir os dados
        $sql = "INSERT INTO horariosagendados (dia_semana, horario, corte, nome_cliente, telefone, email) VALUES (?, ?, ?, ?, ?, ?)";

        // Prepara a declaração
        $stmt = $conexao->prepare($sql);

        // Verifica se a declaração está pronta
        if ($stmt) {
            // Vincula os parâmetros da declaração
            $stmt->bind_param("ssssss", $dia_semana, $horario, $corte, $nome_cliente, $telefone, $email);

            // Define os valores dos parâmetros
            $dia_semana = $_POST['dia'];
            $horario = $_POST['hora'];
            $corte = $_POST['corte'];
            $nome_cliente = $_POST['nome'];
            $telefone = $_POST['telefone'];
            $email = $_POST['email']; // Adiciona o campo de e-mail

            // Executa a declaração
            if ($stmt->execute()) {
                // Executa o script para enviar o e-mail de confirmação
                require_once 'enviar_gmail.php';

                
            } else {
                echo "Erro ao agendar horário: " . $stmt->error;
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração: " . $conexao->error;
        }

        // Fecha a conexão
        $conexao->close();
    } else {
        echo "Todos os campos são obrigatórios!";
    }
} else {
    echo "Acesso não permitido!";
}
?>