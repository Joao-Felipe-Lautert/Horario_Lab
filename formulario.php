<?php
include ('conexao.php');

$rowTurma        = '';
$rowHora         = '';
$rowDia          = '';
$hoId 	 	     = '';
$hoSala 	     = '';
$hoTurma         = '';
$hoHorario       = '';
$hora            = '';
$hodia           = '';

// NOVO
if ((empty($_POST['FormId'])) and (empty($_POST['FormNome'])) and (empty($_GET['FormId']))) {

    //echo "vazio";

// INSERT
} else if ((empty($_POST['FormId'])) and (!empty($_POST['FormNome']))) {

    mysqli_query($DataBase, "INSERT INTO tbl_horario VALUES (ho_id,
                                                '".$_POST['FormNome']."',
                                                '".$_POST['FormTurma']."',
												'".$_POST['FormHorario']."')");
    echo "insert";


//CARREGAR DADOS
} else if (!empty($_GET['FormId'])) {

    $result = mysqli_query($DataBase, "SELECT * FROM tbl_horario WHERE (ho_id=" . $_GET['FormId'] . ")");
    $row = mysqli_fetch_array($result);

    $hoId 	 	= $row["ho_id"];
    $hoSala 	= $row["ho_sala"];
    $hoTurma    = $row["ho_turma"];
    $hoHorario  = $row["ho_horario"];

    echo "listando";

// UPDATE
} else if ((!empty($_POST['FormId'])) and ($_POST['FormComando'] == "Update")){

    echo "update";
// DELETE    
} else if ((!empty($_POST['FormId'])) and ($_POST['FormComando'] == "Excluir")){


}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="teste.css">
    <title>Formulário Horario</title>
    
</head>
<body>
    
    <div class="container">
        <h2>Formulário de Horário</h2>
        <form action="#" method="POST">
            <input type="hidden" id="FormComando" name="FormComando" value="Update">
            <input type="hidden" id="FormId" name="FormId" value="">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="FormNome" name="FormNome" required placeholder="Digite nome da sala" value="<?= $hoSala ?>">
                </div>
                <div class="form-group">
                    <label for="turma">Turma:</label>
                    <input type="text" id="FormTurma" name="FormTurma" required placeholder="Digite a turma" value="<?= $hoTurma ?>">
                </div>

                    <div>
                <label for="opcoes">Dias:</label>
                <select id="opcoes" name="opcoes" class="Corselecao">
                <option value="1">Segunda</option>
                <option value="2">Terça</option>
                <option value="3">Quarta</option>
                <option value="4">Quinta</option>
                <option value="5">Sexta</option>
                </select>
                    </div>

                <div class="form-group">
                    <label for="horario">Horário:</label>
                    <select id="opcoes" name="opcoes" class="Corselecao">
                <option value="1">6:50</option>
                <option value="2">7:40</option>
                <option value="3">8:30</option>
                <option value="4">9:35</option>
                <option value="5">10:25</option>
                <option value="6">11:15</option>
                </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Salvar">
                </div>
                <div class="form-group">
                    <input type="submit" value="Novo">
                </div>
                
        </form>

        <div class="form-group">
                    <input id="botao" type="submit" value="Excluir" onclick="MostrarDiv('Lista','botao')">
                </div>
    </div>
    <div>
        <div id="Lista" class="container" style="height:800px; display:">
        <h2>Matutino</h2>

<?php

$resultDia = mysqli_query($DataBase, "SELECT ho_dia FROM tbl_horario GROUP BY ho_dia ORDER BY FIELD(ho_dia,  'domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado');");    
    // Exibir cada linha da tabela
    while ($rowDia = mysqli_fetch_assoc($resultDia)) { // dia 
?>
    <!-- INICIO DO DIA -->
    <div>
        <label><?= $rowDia['ho_dia']?></label>
        <input id="botao<?= $rowDia['ho_dia']?>" type="submit" value="Abrir" onclick="MostrarDiv('div<?= $rowDia['ho_dia']?>','botao<?= $rowDia['ho_dia']?>')" style="width:100px;height: 38px;px"> <?php // Botao dia?>

        <?php 
        $resultHora = mysqli_query($DataBase, "SELECT ho_horario FROM tbl_horario WHERE (ho_dia = '".$rowDia['ho_dia']."') GROUP BY ho_horario");    
        // Exibir cada linha da tabela?>
        <div id="div<?= $rowDia['ho_dia']?>" style="display:none">

        <?php
        while ($rowHora = mysqli_fetch_assoc($resultHora)) {  // Hora
        ?>

            <!-- INICIO DA HORA -->
             
            <div id="div<?= $rowDia['ho_dia']?><?= $rowHora['ho_horario']?>" style="display:">
            <input type="button" onclick="MostrarDivBotao('divturma<?= $rowDia['ho_dia']?><?= $rowHora['ho_horario']?>')" value="<?= $rowHora['ho_horario']?>"> <?php // Botao hora?>

            </div>
        

            <?php 
            
            $resultTurma = mysqli_query($DataBase, "SELECT * FROM tbl_horario WHERE (ho_dia = '".$rowDia['ho_dia']."' and ho_horario = '".$rowHora['ho_horario']."') ");
            // Exibir cada linha da tabela
            ?>
            <div id="divturma<?= $rowDia['ho_dia']?><?= $rowHora['ho_horario']?>" style="display:none">
                
            
                <?php
                while ($rowTurma = mysqli_fetch_assoc($resultTurma)) { // while da Turma
                ?>

                
                    <!-- INICIO DA TURMA ============================================= -->
                    <!-- ============================================================= -->
                    <div id="div<?= $rowDia['ho_dia']?>" style="display:">
                    <?= $rowTurma['ho_turma']?>            
                    </div>
            

                
                <?php 
                }// fecha while da hora
                ?>

                <div id="div<?= $rowDia['ho_dia']?>" style="display:">
                    10:00
                </div> <!-- div dia/horario-->
            </div>
        <?php 
        }// fecha while da hora
        ?>
        </div>

    <div>

<?php        
    } //Final While Dia

?>
    </div>
 </div>
    

    <script>
            function MostrarDiv(DivId,Botao) {
        	
        	if (document.getElementById(Botao).textContent == "Abrir") {    
            	document.getElementById(DivId).style.display = '';
            	document.getElementById(Botao).textContent  = 'Fechar';
				document.getElementById(Botao).style.fontWeight = 'bold';
				//document.getElementById(Botao).style.color = '#A52A2A';            
            } else {            
            	document.getElementById(DivId).style.display = 'None';
            	document.getElementById(Botao).textContent  = 'Abrir';
				document.getElementById(Botao).style.fontWeight = '';
				//document.getElementById(Botao).style.color = '#000000';            
            }
            
        } 

        function MostrarDivBotao(DivId) {
        	
        	if (document.getElementById(DivId).style.display == "none") {    
            	document.getElementById(DivId).style.display = '';
            	
            } else {            
            	document.getElementById(DivId).style.display = 'None';            	
            }
            
        } 
    </script>
    
</body>
</html>
