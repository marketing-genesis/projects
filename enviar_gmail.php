<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

// Instancie o objeto PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'barbeariaexemplar@gmail.com';
    $mail->Password = 'bore lshx nlpb pjdx';
    $mail->SMTPSecure = 'tls'; // tls ou ssl
    $mail->Port = 587; // porta do servidor SMTP

    // Configurações do remetente
    $mail->setFrom('barbeariaexemplar@gmail.com', 'Barbearia x');

    // Consulta SQL para recuperar o endereço de e-mail do destinatário da tabela
    $sql = "SELECT email FROM informacoes_barbearia WHERE id = 1"; // Altere 'tabela_destinatario' e 'id' conforme necessário
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $destinatario = $resultado->fetch_assoc();

        // Configurações do destinatário
        $mail->addAddress($destinatario['email'], 'Destinatário x');

        // Conteúdo do e-mail
        $mail->isHTML(true); // defina como verdadeiro se quiser enviar e-mails HTML
        $mail->Subject = 'Novo Horario Agendado';

        // Consulta SQL para recuperar o último horário agendado
        $sql = "SELECT * FROM horariosagendados ORDER BY id DESC LIMIT 1";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $horario = $resultado->fetch_assoc();

            // Constrói o corpo do e-mail com os dados do último horário agendado
            $body = '<h2>Você tem um novo horário agendado:</h2>';
            $body .= '<ul>';
            $body .= '<li><strong>Data:</strong> ' . $horario['data_agendamento'] . '</li>';
            $body .= '<li><strong>Dia da Semana:</strong> ' . $horario['dia_semana'] . '</li>';
            $body .= '<li><strong>Horário:</strong> ' . $horario['horario'] . '</li>';
            $body .= '<li><strong>Corte:</strong> ' . $horario['corte'] . '</li>';
            $body .= '<li><strong>Nome do Cliente:</strong> ' . $horario['nome_cliente'] . '</li>';
            $body .= '<li><strong>Telefone:</strong> ' . $horario['telefone'] . '</li>';
            $body .= '</ul>';

            $mail->Body = $body;

            // Envie o e-mail
            $mail->send();
            echo 'E-mail de notificação enviado com sucesso!';
        } else {
            echo 'Nenhum horário agendado encontrado.';
        }
    } else {
        echo 'Nenhum destinatário encontrado.';
    }
} catch (Exception $e) {
    echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
