<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

// Obter a data atual
$data_atual = date('Y-m-d');

// Calcular o tempo atual e o tempo daqui a uma hora
$tempo_atual = time();
$tempo_um_hora_depois = $tempo_atual + 3600; // 3600 segundos = 1 hora

// Converter para formato de data e hora MySQL
$tempo_atual_mysql = date('Y-m-d H:i:s', $tempo_atual);
$tempo_um_hora_depois_mysql = date('Y-m-d H:i:s', $tempo_um_hora_depois);

// Consulta SQL para obter os horários agendados para o dia atual nas próximas 24 horas
$sql = "SELECT * FROM horariosagendados WHERE dia_semana = ? AND data_agendamento BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Erro ao preparar a declaração: " . $conn->error;
} else {
    // Vincular parâmetros à declaração
    $stmt->bind_param("sss", $data_atual, $tempo_atual_mysql, $tempo_um_hora_depois_mysql);

    // Executar a declaração
    $stmt->execute();

    // Obter resultado da consulta
    $result = $stmt->get_result();

    // Verificar se há horários agendados para envio do e-mail de lembrete
    if ($result->num_rows > 0) {
        // Loop através dos horários agendados
        while($row = $result->fetch_assoc()) {
            // Verificar se o horário agendado é uma hora antes do tempo atual
            $horario_agendado_unix = strtotime($row['data_agendamento']);
            $horario_agendado_um_hora_antes_unix = $horario_agendado_unix - 3600;
            $tempo_atual_unix = time();
            if ($tempo_atual_unix >= $horario_agendado_um_hora_antes_unix) {
                // Cliente a ser notificado
                $cliente_email = $row['email'];

                // Crie uma instância do PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Configurações do servidor SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'barbeariaexemplar@gmail.com'; // Coloque seu e-mail do Gmail
                    $mail->Password = 'bore lshx nlpb pjdx'; // Coloque a senha do seu e-mail do Gmail
                    $mail->SMTPSecure = 'tls'; // tls ou ssl
                    $mail->Port = 587; // porta do servidor SMTP
                    
                    // Configurações do remetente
                    $mail->setFrom('barbeariaexemplar@gmail.com', 'Barbearia Exemplar');

                    // Configurações do destinatário
                    $mail->addAddress($cliente_email);

                    // Conteúdo do e-mail de lembrete
                    $mail->isHTML(true);
                    $mail->Subject = 'Lembrete de Agendamento';
                    $mail->Body = 'Este é um lembrete para o seu agendamento na barbearia. Data e Hora: ' . $row['data_agendamento'];

                    // Envie o e-mail
                    $mail->send();
                    echo 'E-mail de lembrete enviado para: ' . $cliente_email . '<br>';
                } catch (Exception $e) {
                    echo 'Erro ao enviar e-mail de lembrete: ' . $mail->ErrorInfo;
                }
            } else {
                echo 'Não é necessário enviar e-mail de lembrete para: ' . $row['email'] . '<br>';
            }
        }
    } else {
        echo "Nenhum e-mail de lembrete a ser enviado.";
    }

    // Fechar a declaração
    $stmt->close();
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
