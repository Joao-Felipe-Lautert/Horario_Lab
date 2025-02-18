<?php

include ('conexao.php');

//mysqli_query($DataBase,



// Define a ordem dos dias (ajuste conforme necessário)
$dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'];

// Define os turnos e suas condições de filtragem (baseadas na função HOUR do MySQL)
$turnos = [
    "Matutino"   => "AND HOUR(tu_horario) BETWEEN 7 AND 12",
    "Vespertino" => "AND HOUR(tu_horario) BETWEEN 13 AND 18",
    "Noturno"    => "AND HOUR(tu_horario) BETWEEN 17 AND 23"
];

/**
 * Função que busca os dados para um determinado turno.
 * Agrupa os registros por horário, retornando um array associativo:
 *  - Chave: horário formatado (ex.: '08:00')
 *  - Valor: array associativo onde a chave é o dia da semana e o valor é a(s) turma(s).
 */
function buscarDadosTurno($DataBase, $condicao) {
    $sql = "SELECT tu_diasemana, tu_horario, GROUP_CONCAT(tu_oficina SEPARATOR '<br> ') AS turmas 
            FROM tbl_turma 
            WHERE 1 $condicao 
            GROUP BY tu_diasemana, tu_horario 
            ORDER BY tu_horario";
    $result = mysqli_query($DataBase, $sql);

    
    $dados = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Formata o horário para exibição (HH:MM)
            $hora = date("H:i", strtotime($row['tu_horario']));
            // Armazena o dado na estrutura: $dados[horario][dia] = turmas
            $dados[$hora][$row['tu_diasemana']] = $row['turmas'];
        }
    }
    // Ordena os horários em ordem crescente
    ksort($dados);
    return $dados;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Horário CEJU</title>
  <link rel="icon" href="img/logoCEJU.png" type="image/png">
  <style>
    /* Define para impressão em A4 na orientação paisagem */
    @page {
      size: A4 landscape;
      margin: 10mm;
    }
    
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: #e0e0e0;
      margin: 0;
      padding: 20px;
    }
    .container {
      width: 100%;
      max-width: 1200px;
      margin: auto;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    h3 {
      background-color: #2c2c2c;
      padding: 10px;
      margin: 20px 0 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
      background-color: #1e1e1e;
    }
    th, td {
      border: 1px solid #333;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #2c2c2c;
    }
  </style>
</head>
<body>
<?php include('mod_topo.php'); ?>
  <div class="container">
    <h2>Horário CEJU</h2>
    <?php
      // Para cada turno, busca os dados e monta uma tabela no estilo de horário escolar
      foreach ($turnos as $turno => $condicao) {
          echo "<h3>$turno</h3>";
          $dados = buscarDadosTurno($DataBase, $condicao);
          
          if (!empty($dados)) {
              echo "<table>";
              // Cabeçalho da tabela: primeira coluna vazia para os horários e depois os dias
              echo "<tr><th>Horário</th>";
              foreach ($dias as $dia) {
                  echo "<th>$dia</th>";
              }
              echo "</tr>";
              
              // Para cada horário existente, cria uma linha na tabela
              foreach ($dados as $horario => $valores) {
                  echo "<tr>";
                  echo "<td>$horario </td>";
                  // Para cada dia da semana definido no array $dias
                  foreach ($dias as $dia) {
                      // Se houver dados para aquele dia e horário, exibe; caso contrário, célula vazia
                      echo "<td style='text-align: left;'>" . (isset($valores[$dia]) ? $valores[$dia] : "") . "</td>";
                  }
                  echo "</tr>";
              }
              echo "</table>";
          } else {
              echo "<p>Nenhum horário cadastrado para o turno $turno.</p>";
          }
      }
      $DataBase->close();
    ?>
  </div>
</body>
</html>
