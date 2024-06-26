<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta ao banco de dados
    $conexao = new mysqli("localhost", "root", "", "barbearia");
    
    // Verifica se a conexão foi estabelecida com sucesso
    if ($conexao->connect_error) {
        die("Erro ao conectar ao banco de dados: " . $conexao->connect_error);
    }
    
    // Diretório de destino para o carrossel normal
    $diretorio_destino = "./uploads/";
    // Diretório de destino para o carrossel administrador
    $diretorio_destino_adm = "../uploads/";
    
    // Itera sobre os campos do formulário
    for ($i = 1; $i <= 10; $i++) {
        // Verifica se o arquivo de imagem foi enviado sem erros
        if ($_FILES["imagem_$i"]["error"] == UPLOAD_ERR_OK) {
            // Define o diretório de destino para salvar a imagem
            $caminho_imagem = $diretorio_destino . basename($_FILES["imagem_$i"]["name"]);
            $caminho_imagem_adm = $diretorio_destino_adm . basename($_FILES["imagem_$i"]["name"]);
            
            // Move o arquivo de imagem para o diretório de destino
            if (move_uploaded_file($_FILES["imagem_$i"]["tmp_name"], $caminho_imagem)) {
                // Recupera o título do item
                $titulo = $_POST["titulo_$i"];
                
                // Insere ou atualiza os dados na tabela 'carrossel'

                // Verifica se já existe um registro para este item na tabela 'carrossel'
                $sql_select = "SELECT id FROM carrossel WHERE id = $i";
                $resultado = $conexao->query($sql_select);
                
                if ($resultado->num_rows > 0) {
                    // Atualiza o registro existente na tabela 'carrossel'
                    $sql_update = "UPDATE carrossel SET titulo = '$titulo', imagem = '$caminho_imagem' WHERE id = $i";
                    if ($conexao->query($sql_update) === TRUE) {
                        echo "Dados do item $i atualizados com sucesso na tabela 'carrossel'!<br>";
                    } else {
                        echo "Erro ao atualizar os dados do item $i na tabela 'carrossel': " . $conexao->error . "<br>";
                    }
                } else {
                    // Insere um novo registro na tabela 'carrossel' se não existir
                    $sql_insert = "INSERT INTO carrossel (id, titulo, imagem) VALUES ($i, '$titulo', '$caminho_imagem')";
                    if ($conexao->query($sql_insert) === TRUE) {
                        echo "Dados do item $i inseridos com sucesso na tabela 'carrossel'!<br>";
                    } else {
                        echo "Erro ao inserir os dados do item $i na tabela 'carrossel': " . $conexao->error . "<br>";
                    }
                }
            } else {
                echo "Erro ao fazer upload da imagem do item $i.<br>";
            }
            
            // Insere ou atualiza os dados na tabela 'carrossel_adm'

            // Verifica se já existe um registro para este item na tabela 'carrossel_adm'
            $sql_select_adm = "SELECT id FROM carrossel_adm WHERE id = $i";
            $resultado_adm = $conexao->query($sql_select_adm);
            
            if ($resultado_adm->num_rows > 0) {
                // Atualiza o registro existente na tabela 'carrossel_adm'
                $sql_update_adm = "UPDATE carrossel_adm SET titulo = '$titulo', imagem = '$caminho_imagem_adm' WHERE id = $i";
                if ($conexao->query($sql_update_adm) === TRUE) {
                    echo "Dados do item $i atualizados com sucesso na tabela 'carrossel_adm'!<br>";
                } else {
                    echo "Erro ao atualizar os dados do item $i na tabela 'carrossel_adm': " . $conexao->error . "<br>";
                }
            } else {
                // Insere um novo registro na tabela 'carrossel_adm' se não existir
                $sql_insert_adm = "INSERT INTO carrossel_adm (id, titulo, imagem) VALUES ($i, '$titulo', '$caminho_imagem_adm')";
                if ($conexao->query($sql_insert_adm) === TRUE) {
                    echo "Dados do item $i inseridos com sucesso na tabela 'carrossel_adm'!<br>";
                } else {
                    echo "Erro ao inserir os dados do item $i na tabela 'carrossel_adm': " . $conexao->error . "<br>";
                }
            }
        }
    }
    
    // Fecha a conexão com o banco de dados
    $conexao->close();
}
?>
