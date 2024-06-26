<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbearia</title>
    <link rel="stylesheet" href="style/carrossel.css">
    <link rel="stylesheet" href="style/home.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="https://unpkg.com/scrollreveal"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluir folhas de estilo do Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Estilos personalizados para o mapa -->
    <style>
        #map {
            height: 400px;
        }
    </style>



</head>

<body>

    <header>
        <div class="logo">
            <img src="./imagens/logobarber-removebg-preview.png" alt="Logo">
        </div>



        <!-- Ícone para mostrar/ocultar informações -->
        <div class="icon-container-info" id="info-icon">
            <img src="./imagens/info.png" alt="Ícone de informações" id="info-icon-img">
        </div>

    </header>

    <div class="banner">

        <div class="banner-content">


        </div>
    </div>

    <!-- Área para exibir as informações gerais da barbearia -->
    <div class="info-overlay" id="info-overlay">
        <div class="info-content">
            <h3>Informações da Barbearia</h3>
            <?php
            // Conexão com o banco de dados
            $conn = mysqli_connect("localhost", "root", "", "barbearia");

            // Verifica a conexão
            if (!$conn) {
                die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
            }

            // Consulta SQL para obter as informações da barbearia
            $sql = "SELECT * FROM informacoes_barbearia";
            $result = mysqli_query($conn, $sql);

            // Verifica se existem informações
            if (mysqli_num_rows($result) > 0) {
                // Exibe as informações
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>Horário de Funcionamento: " . $row['horario'] . "</p>";
                    echo "<p>Endereço: " . $row['endereco'] . "</p>";
                    echo "<p>Cidade: " . $row['cidade'] . "</p>";
                    echo "<p>CEP: " . $row['cep'] . "</p>";
                    echo "<p>Estado: " . $row['estado'] . "</p>";
                    echo "<p>Telefone: " . $row['telefone'] . "</p>";
                }

                
            } else {
                echo "<p>Nenhuma informação disponível.</p>";
            }

            // Fecha a conexão com o banco de dados
            mysqli_close($conn);
            ?>
                 <title>Google Maps Integration</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAD8z0D8kAwshHJbf7ppyAYF5jV1Rjn2kM&callback=initMap" async defer></script>
    <style>
  #map {
    margin: auto;
    height: 400px;
    width: 400px;
  }
  @media (max-width: 767px) {
    #map {
        height: 300px;
      width: 300px;
    }
  }
</style>
  </head>
  <body>
    <div id="map"></div>
    <script>
  let map;
  let directionsService;
  let directionsRenderer;

  function initMap() {
    const center = { lat: -34.397, lng: 150.644 };

    map = new google.maps.Map(document.getElementById("map"), {
      center: center,
      zoom: 8,
    });

    // Chame a função getAddress() quando o mapa for carregado
    getAddress();

    // Adicione um evento de clique ao mapa para calcular a rota
    map.addListener('click', function(event) {
      // Calcule a rota do local atual do usuário para o local clicado
      const request = {
        origin: { lat: () => {
          navigator.geolocation.getCurrentPosition((position) => {
            return position.coords.latitude;
          });
        }, lng: () => {
          navigator.geolocation.getCurrentPosition((position) => {
            return position.coords.longitude;
          });
        } },
        destination: event.latLng,
        travelMode: 'DRIVING'
      };

      // Calcule a rota e exiba-a no mapa
      directionsService = new google.maps.DirectionsService();
      directionsRenderer = new google.maps.DirectionsRenderer();
      directionsRenderer.setMap(map);
      directionsService.route(request, function(result, status) {
        if (status === 'OK') {
          directionsRenderer.setDirections(result);

          // Exiba o tempo estimado de chegada
          const legs = result.routes[0].legs[0];
          const duration = legs.duration;
          const durationText = google.maps.DurationFormatter.format(duration, {
            'long_unit': 'minute'
          });
          alert(`Tempo estimado de chegada: ${durationText}`);
        }
      });
    });
  }

  function getAddress() {
    $.ajax({
      url: 'maps.php',
      type: 'POST',
      data: { id: 1 },
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          var address = response.address;
          // Use o geocoder para obter a posição do endereço
          const geocoder = new google.maps.Geocoder();
          geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK') {
              // Adicione um marcador ao mapa com a posição do endereço
              const marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map
              });
              // Ajuste o zoom do mapa para mostrar o marcador
              map.setCenter(results[0].geometry.location);
              map.setZoom(15);
            } else {
              alert('Geocode failed: ' + status);
            }
          });
        } else {
          alert(response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error: ' + textStatus + ' - ' + errorThrown);
      }
    });
  }

  // Adicione um evento de clique ao botão de calcular a rota
  document.getElementById('calculate-route').addEventListener('click', function() {
    // Obtenha o modo de transporte selecionado
    const travelMode = document.getElementById('travel-mode').value;

    // Calcule a rota do local atual do usuário para o endereço da barbearia
    const request = {
      origin: { lat: () => {
        navigator.geolocation.getCurrentPosition((position) => {
          return position.coords.latitude;
        });
      }, lng: () => {
        navigator.geolocation.getCurrentPosition((position) => {
          return position.coords.longitude;
        });
      } },
      destination: '1600 Amphitheatre Parkway, Mountain View, CA',
      travelMode: travelMode
    };

    // Calcule a rota e exiba-a no mapa
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    directionsService.route(request, function(result, status) {
      if (status === 'OK') {
        directionsRenderer.setDirections(result);

        // Exiba o tempo estimado de chegada
        const legs = result.routes[0].legs[0];
        const duration = legs.duration;
        const durationText = google.maps.DurationFormatter.format(duration, {
          'long_unit': 'minute'
        });
        alert(`Tempo estimado de chegada: ${durationText}`);
      }
    });
  });
</script>
        </div>
       
    </div>
    <div class="section-4">
        <div class="carousel">
            <!-- Iterar sobre os dados do carrossel -->
            <?php

            include("obter_carrossel.php");
            if (isset($carrossel_data) && !empty($carrossel_data)) : ?>
                <?php foreach ($carrossel_data as $item) : ?>
                    <div class="carousel-item">
                        <div class="carousel-box">
                            <div class="title" id="title<?= $item['id'] ?>"></div>
                            <div class="num">0<?= $item['id'] ?></div>
                            <img id="image<?= $item['id'] ?>" src="">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="carousel-item">
                    <div class="carousel-box">
                        <div class="title">Nenhum dado encontrado</div>
                        <div class="num">01</div>
                        <img id="image01" src="">
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <script>
            // Iterar sobre os dados do carrossel e preencher os campos
            <?php if (isset($carrossel_data) && !empty($carrossel_data)) : ?>
                <?php foreach ($carrossel_data as $item) : ?>
                    document.getElementById('title<?= $item['id'] ?>').innerText = '<?= $item['titulo'] ?>';
                    document.getElementById('image<?= $item['id'] ?>').src = '<?= $item['imagem'] ?>';
                <?php endforeach; ?>
            <?php endif; ?>
        </script>


    </div>
    <div class="layout"></div>
    
    </div>




    <section class="horarios">
        <h2>Agendar Horário</h2>
        <table>
            <thead>
                <tr>
                    <th>
                        <select id="select-dia">
                            <option value="Segunda-feira">Segunda-feira</option>
                            <option value="Terca-feira">Terça-feira</option>
                            <option value="Quarta-feira">Quarta-feira</option>
                            <option value="Quinta-feira">Quinta-feira</option>
                            <option value="Sexta-feira">Sexta-feira</option>
                            <option value="sSabado">Sábado</option>
                        </select>
                    </th>
                    <th>Horário</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td class="horario-disponivel" data-dia="segunda" data-horario="09:00">09:00 - 10:00</td>
                    <td class="status-disponivel">Disponível</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="horario-disponivel" data-dia="segunda" data-horario="10:00">10:00 - 11:00</td>
                    <td class="status-disponivel">Disponível</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="horario-disponivel" data-dia="segunda" data-horario="14:00">14:00 - 15:00</td>
                    <td class="status-disponivel">Disponível</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="horario-disponivel" data-dia="segunda" data-horario="15:00">15:00 - 16:00</td>
                    <td class="status-disponivel">Disponível</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="horario-disponivel" data-dia="segunda" data-horario="16:00">16:00 - 17:00</td>
                    <td class="status-disponivel">Disponível</td>
                </tr>
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>



    </section>

    <button id="btn-cancelar-agendamento">Cancelar Agendamento</button>



    <!-- HTML do modal de agendamento -->
    <div id="modal" class="modal">
        <div id="modal-content" class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2>Agendar Horário</h2>
            <form id="agendamento-form" method="POST" action="agendar_horario.php">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" placeholder="Ex: (68) 99999-9999" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="Ex: exemplo@email.com" required>

                <label for="corte">Estilo de Corte:</label>
                <select id="corte" name="corte" required>
                    <!-- Opções de estilo de corte serão adicionadas dinamicamente com JavaScript -->
                </select>

                <!-- Campo para armazenar o dia selecionado -->
                <input type="hidden" id="dia" name="dia" value="">

                <!-- Campo para armazenar o horário selecionado -->
                <input type="hidden" id="hora" name="hora" value="">

                <button type="submit">Agendar</button>
            </form>

            <div id="mensagem-confirmacao" style="display: none;">
                <h2>Horário agendado com sucesso!</h2>
               
            </div>

        </div>

    </div>


    <div id="modal-cancelamento" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="fecharModalCancelamento()">&times;</span>
            <h2>Cancelar Horário</h2>
            <form id="cancelamento-form" method="POST" action="cancelar_horario.php">
                <label for="nome-cancelamento">Nome:</label>
                <input type="text" id="nome-cancelamento" name="nome" placeholder="Ex: João Silva" required>

                <label for="telefone-cancelamento">Telefone:</label>
                <input type="text" id="telefone-cancelamento" name="telefone" placeholder="Ex: (68) 99999-9999" required>

                <label for="dia-cancelamento">Dia do Agendamento:</label>
                <input type="text" id="dia-cancelamento" name="dia" placeholder="Ex: Segunda-feira" required>

                <label for="hora-cancelamento">Horário do Agendamento:</label>
                <input type="text" id="hora-cancelamento" name="hora" placeholder="Ex: 10:00" required>

                <button type="submit">Cancelar Horário</button>
            </form>

            <div id="mensagem-cancelamento" style="display: none;">
                <h2>Horário cancelado com sucesso!</h2>
                <p>O seu horário foi cancelado com sucesso.</p>
            </div>
        </div>
    </div>


    <section class="produtos">
    <h1>Produtos Relacionados</h1>
    <div class="carrossel-produtos">
        <?php
        include('obter_produtos.php');
        // Verifica se existem produtos
        if (!empty($produtos)) {
            // Itera sobre os produtos e exibe cada um
            foreach ($produtos as $produto) {
        ?>
                <div class="produto-item">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h2><?php echo $produto['preco']; ?></h2>
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p><?php echo $produto['descricao']; ?></p>
                    <!-- Modificação do botão Comprar -->
                    <button onclick="adicionarAoCarrinho(<?php echo $produto['id']; ?>)">Comprar</button>
                </div>
        <?php
            }
        } else {
            // Se não houver produtos, exibe uma mensagem
            echo "<p>Nenhum produto disponível no momento.</p>";
        }
        ?>
    </div>
</section>

<script>
    function adicionarAoCarrinho(idProduto) {
        // Redireciona para a página de checkout com o ID do produto na URL
        window.location.href = "./checkout/index.php?id=" + idProduto;
    }
</script>



    <section class="cortes-section">
    <h2>Cortes Feitos na Barbearia</h2>

    <div class="container">
        <?php
        include('obter_cortes.php');
        // Verifica se existem cortes de cabelo
        if (!empty($cortes_cabelo)) {
            // Itera sobre os cortes de cabelo e exibe cada um
            $contador = 0;
            foreach ($cortes_cabelo as $corte) {
                // Mostrar apenas os três primeiros cortes inicialmente
                $display_style = ($contador < 3) ? 'block' : 'none';
        ?>
                <div class="corte" style="display: <?php echo $display_style; ?>">
                    <img src="<?php echo $corte['imagem']; ?>" alt="<?php echo $corte['nome']; ?>">
                    <div class="descricao">
                        <h3><?php echo $corte['nome']; ?></h3>
                        <p><?php echo $corte['descricao']; ?></p>
                    </div>
                </div>
        <?php
                $contador++;
            }
            // Se houver mais de três cortes, exibir o botão "Ver mais"
            if (count($cortes_cabelo) > 3) {
                echo '<button id="btn-ver-mais" style="display: block;">Ver mais</button>';
            }
        } else {
            // Se não houver cortes de cabelo, exibe uma mensagem
            echo "<p>Nenhum corte de cabelo disponível no momento.</p>";
        }
        ?>
    </div>
</section>



    <?php
    // Conexão com o banco de dados
    $conn = mysqli_connect("localhost", "root", "", "barbearia");

    // Verifica a conexão
    if (!$conn) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Consulta SQL para obter as avaliações
    $sql = "SELECT * FROM avaliacoes";
    $result = mysqli_query($conn, $sql);

    // Verifica se existem avaliações
    if (mysqli_num_rows($result) > 0) {
        // Array para armazenar os dados das avaliações
        $avaliacoes = array();

        // Itera sobre os resultados da consulta
        while ($row = mysqli_fetch_assoc($result)) {
            // Adiciona os dados da avaliação ao array
            $avaliacoes[] = $row;
        }
    } else {
        // Se não houver avaliações, define um array vazio
        $avaliacoes = array();
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conn);
    ?>

<section class="avaliacoes-section">
    <h2>Avaliações dos Clientes</h2>
    <div class="avaliacoes-container" data-quantidade="<?php echo count($avaliacoes); ?>" data-visiveis="<?php echo min(count($avaliacoes), 4); ?>">
        <?php foreach ($avaliacoes as $avaliacao) : ?>
            <div class="avaliacao" style="display: <?php echo ($contador < 4) ? 'block' : 'none'; ?>">
                <p><strong>Cliente:</strong> <?php echo $avaliacao['nome_cliente']; ?></p>
                <p><strong>Corte:</strong> <?php echo $avaliacao['corte']; ?></p>
                <p><strong>Atendimento:</strong> <?php echo $avaliacao['atendimento']; ?></p>
                <p><strong>Comentários:</strong> <?php echo $avaliacao['comentarios']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($avaliacoes) > 4) : ?>
        <button id="btn-ver-mais" style="display: block;">Ver mais</button>
    <?php endif; ?>
</section>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const avaliacoesContainer = document.querySelector('.avaliacoes-container');
    const todasAvaliacoes = avaliacoesContainer.querySelectorAll('.avaliacao');
    const btnVerMais = document.getElementById('btn-ver-mais');
    
    // Ocultar todas as avaliações além das primeiras quatro
    for (let i = 4; i < todasAvaliacoes.length; i++) {
        todasAvaliacoes[i].style.display = 'none';
    }
    
    // Adicionar evento de clique ao botão "Ver mais"
    if (btnVerMais) {
        btnVerMais.addEventListener('click', function() {
            // Exibir todas as avaliações restantes
            for (let i = 4; i < todasAvaliacoes.length; i++) {
                todasAvaliacoes[i].style.display = 'block';
            }
            // Ocultar o botão "Ver mais" após clicar nele
            btnVerMais.style.display = 'none';
        });
    }
});

</script>
</section>


    <section class="feedback-section">

        <button id="openModalBtn">Deixar Avaliação</button>
        <div id="avaliacaoModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="feedback-form">
                    <form action="processar_avaliacao.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome do Cliente:</label>
                            <input type="text" name="nome" id="nome" placeholder="Seu Nome" required>
                        </div>

                        <div class="form-group">
                            <label for="corte">Avalie o corte:</label>
                            <input type="radio" name="corte" value="pessimo"> Péssimo
                            <input type="radio" name="corte" value="ruim"> Ruim
                            <input type="radio" name="corte" value="regular"> Regular
                            <input type="radio" name="corte" value="bom"> Bom
                            <input type="radio" name="corte" value="otimo"> Ótimo
                        </div>

                        <div class="form-group">
                            <label for="atendimento">Avalie o atendimento:</label>
                            <input type="radio" name="atendimento" value="pessimo"> Péssimo
                            <input type="radio" name="atendimento" value="ruim"> Ruim
                            <input type="radio" name="atendimento" value="regular"> Regular
                            <input type="radio" name="atendimento" value="bom"> Bom
                            <input type="radio" name="atendimento" value="otimo"> Ótimo
                        </div>

                        <!-- Adicione mais critérios de avaliação conforme necessário -->

                        <div class="form-group">
                            <label for="comentarios">Comentários:</label>
                            <textarea name="comentarios" id="comentarios" placeholder="Descreva sua experiência aqui..."></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit">Enviar Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>



    <footer>



        <!-- Adicione mais ícones conforme necessário -->


        <div class="footer-container">
            <div class="footer-links">
                <a href="#">Políticas de Privacidade</a>

                <a href="#">Termos de Uso</a>
                <a href="#">Quem Somos</a>
                <!-- Adicione outros links relevantes aqui -->
            </div>
            <?php
            // Conexão com o banco de dados
            $conn = mysqli_connect("localhost", "root", "", "barbearia");

            // Verifica a conexão
            if (!$conn) {
                die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
            }

            // Consulta SQL para obter as informações da barbeari
            $sql = "SELECT * FROM informacoes_barbearia";
            $result = mysqli_query($conn, $sql);

            // Verifica se existem informações
            if (mysqli_num_rows($result) > 0) {
                // Itera sobre os resultados da consulta
                while ($row = mysqli_fetch_assoc($result)) {
                    // Exibe as informações na página
                    echo "<div class='footer-info'>&copy; 2024 " . $row['nome_barbearia'] . " | CNPJ: " . $row['cnpj'] . "</div>";
                }
            } else {
                // Se não houver informações, exibe uma mensagem
                echo "<div class='footer-info'>Nenhuma informação disponível</div>";
            }

            // Fecha a conexão com o banco de dados
            mysqli_close($conn);
            ?>



            <div class="social-icons">
                <div class="icon-container">
                    <a href="<?php echo $facebook_link; ?>" target="_blank">
                        <img src="./imagens/face.png" alt="Facebook">
                    </a>
                </div>
                <div class="icon-container">
                    <a href="<?php echo $instagram_link; ?>" target="_blank">
                        <img src="./imagens/insta.png" alt="Instagram">
                    </a>
                </div>
                <div class="icon-container">
                    <a href="<?php echo $whatsapp_link; ?>" target="_blank">
                        <img src="./imagens/whattsss.png" alt="WhatsApp">
                    </a>
                </div>
            </div>


    </footer>
    <script src="./script/carrossel.js"></script>
    <script src="./script/home.js"></script>
    <script src="./script/script2.js"></script>
    <script src="./script/script3.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>