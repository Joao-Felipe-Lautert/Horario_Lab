<?php
include('conexao.php');

// Exemplo de uso de prepared statements para a operação de INSERT
if (empty($_POST['FormId']) && !empty($_POST['FormNome'])) {
    $stmt = $DataBase->prepare("INSERT INTO tbl_horario (ho_sala, ho_turma, ho_horario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['FormNome'], $_POST['FormTurma'], $_POST['FormHorario']);
    $stmt->execute();
    echo "insert";
} else if (!empty($_GET['FormId'])) {
    $stmt = $DataBase->prepare("SELECT * FROM tbl_horario WHERE ho_id = ?");
    $stmt->bind_param("i", $_GET['FormId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $hoId      = $row["ho_id"];
    $hoSala    = $row["ho_sala"];
    $hoTurma   = $row["ho_turma"];
    $hoHorario = $row["ho_horario"];
    echo "listando";
} else if (!empty($_POST['FormId']) && $_POST['FormComando'] == "Update"){
    echo "update";
} else if (!empty($_POST['FormId']) && $_POST['FormComando'] == "Excluir"){
    echo "delete";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Utilizando Bootstrap para um design mais moderno e responsivo -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="teste.css">
  <title>Formulário de Horário</title>
</head>
<body>
  <div class="container mt-5">
    <header class="mb-4">
      <h2 class="text-center">Formulário de Horário</h2>
    </header>
    <main>
      <form action="#" method="POST" class="mb-4">
        <input type="hidden" name="FormComando" value="Update">
        <input type="hidden" name="FormId" value="">
        
        <div class="form-group">
          <label for="FormNome">Nome:</label>
          <input type="text" id="FormNome" name="FormNome" class="form-control" placeholder="Digite nome da sala" value="<?= isset($hoSala) ? htmlspecialchars($hoSala) : '' ?>" required>
        </div>
        
        <div class="form-group">
          <label for="FormTurma">Turma:</label>
          <input type="text" id="FormTurma" name="FormTurma" class="form-control" placeholder="Digite a turma" value="<?= isset($hoTurma) ? htmlspecialchars($hoTurma) : '' ?>" required>
        </div>
        
        <div class="form-group">
          <label for="opcoes">Dias:</label>
          <select id="opcoes" name="opcoes" class="form-control">
            <option value="Segunda">Segunda</option>
            <option value="Terça">Terça</option>
            <option value="Quarta">Quarta</option>
            <option value="Quinta">Quinta</option>
            <option value="Sexta">Sexta</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="FormHorario">Horário:</label>
          <select id="FormHorario" name="FormHorario" class="form-control">
            <option value="6:50">6:50</option>
            <option value="7:40">7:40</option>
            <option value="8:30">8:30</option>
            <option value="9:35">9:35</option>
            <option value="10:25">10:25</option>
            <option value="11:15">11:15</option>
          </select>
        </div>
        
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Salvar</button>
          <button type="reset" class="btn btn-secondary">Novo</button>
        </div>
      </form>
      
      <div class="mb-4">
        <button id="botao" class="btn btn-danger" onclick="toggleDiv('Lista', 'botao')">Excluir</button>
      </div>
      
      <section id="Lista">
        <h2>Matutino</h2>
        <?php
        $resultDia = mysqli_query($DataBase, "SELECT ho_dia FROM tbl_horario GROUP BY ho_dia ORDER BY FIELD(ho_dia, 'domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado')");
        while ($rowDia = mysqli_fetch_assoc($resultDia)) { ?>
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5><?= htmlspecialchars($rowDia['ho_dia']) ?></h5>
              <button id="botao<?= htmlspecialchars($rowDia['ho_dia']) ?>" class="btn btn-outline-info" onclick="toggleDiv('div<?= htmlspecialchars($rowDia['ho_dia']) ?>', 'botao<?= htmlspecialchars($rowDia['ho_dia']) ?>')">Abrir</button>
            </div>
            <div id="div<?= htmlspecialchars($rowDia['ho_dia']) ?>" style="display: none;">
              <?php 
              $resultHora = mysqli_query($DataBase, "SELECT ho_horario FROM tbl_horario WHERE ho_dia = '".$rowDia['ho_dia']."' GROUP BY ho_horario");
              while ($rowHora = mysqli_fetch_assoc($resultHora)) { ?>
                <div class="mb-2">
                  <button class="btn btn-outline-secondary" onclick="toggleDiv('divturma<?= htmlspecialchars($rowDia['ho_dia']).htmlspecialchars($rowHora['ho_horario']) ?>')"><?= htmlspecialchars($rowHora['ho_horario']) ?></button>
                  <?php 
                  $resultTurma = mysqli_query($DataBase, "SELECT * FROM tbl_horario WHERE ho_dia = '".$rowDia['ho_dia']."' and ho_horario = '".$rowHora['ho_horario']."'");
                  ?>
                  <div id="divturma<?= htmlspecialchars($rowDia['ho_dia']).htmlspecialchars($rowHora['ho_horario']) ?>" style="display: none;" class="ml-3">
                    <?php while ($rowTurma = mysqli_fetch_assoc($resultTurma)) { ?>
                      <div><?= htmlspecialchars($rowTurma['ho_turma']) ?></div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </section>
    </main>
  </div>
  
  <!-- Scripts centralizados -->
  <script>
    function toggleDiv(divId, buttonId = null) {
      const element = document.getElementById(divId);
      if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
        if (buttonId) {
          document.getElementById(buttonId).textContent = "Fechar";
        }
      } else {
        element.style.display = "none";
        if (buttonId) {
          document.getElementById(buttonId).textContent = "Abrir";
        }
      }
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
