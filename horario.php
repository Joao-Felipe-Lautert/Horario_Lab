<?php
include ('conexao.php');
include ('function.php');


$resultList = mysqli_query($DataBase, "SELECT *
										FROM TBL_TURMA
										WHERE tu_diasemana IN ('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')
										ORDER BY FIELD(tu_diasemana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'), tu_horario ASC");

// Verificação se há resultados
if ($resultList->num_rows > 0) {
    // Inicialização do array
    $arrayDados = array();
    
    // Loop para armazenar resultados no array
    while ($row = $resultList->fetch_assoc()) {
        $arrayDados[] = $row;
    }
}








?>
<!DOCTYPE html>
<html lang="pt">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CEJU - Horários</title>
<link rel="icon" href="img/logoCEJU.png" type="image/png">
<link rel="stylesheet" href="css/horario.css">

</head>
<body>

    
    
<?php include('mod_topo.php'); ?>

<div id="corpoDias">

	<div  style="display: flex;justify-content: center;margin-bottom: 6px;">
		<form method="POST" action="horario_editar.php" enctype="multipart/form-data">
		<div  >
			<button  Style="width:390px" >Inserir nova turma</button>
		</div>
		</form>
	</div>

	<div  style="display: flex;justify-content: center;margin-bottom: 2px;">
		<form method="POST" action="horario_listar.php" enctype="multipart/form-data">
		<div>
			<button  Style="width:390px" >Visualizar horário</button>
		</div>
		</form>
	</div>

<?php
$resultList = mysqli_query($DataBase, "SELECT *
										FROM TBL_TURMA
										WHERE tu_diasemana IN ('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')
										ORDER BY FIELD(tu_diasemana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'), tu_horario ASC");



// Agrupando os registros primeiro por dia da semana e depois por horário
$turmasAgrupadas = [];

while ($row = $resultList->fetch_assoc()) {
    $dia  = $row['tu_diasemana'];
    $hora = $row['tu_horario'];

    // Cria o grupo para o dia se não existir
    if (!isset($turmasAgrupadas[$dia])) {
        $turmasAgrupadas[$dia] = [];
    }

    // Cria o grupo para o horário dentro do dia, se não existir
    if (!isset($turmasAgrupadas[$dia][$hora])) {
        $turmasAgrupadas[$dia][$hora] = [];
    }

    // Adiciona o registro ao grupo correspondente
    $turmasAgrupadas[$dia][$hora][] = $row;
}
// =================================================================
// Mostra os dias da semana ========================================
// =================================================================
foreach ($turmasAgrupadas as $dia => $horarios) { //For dos dias da semana

?>
	<div class="listaDias">
		<div class="classDia" style="font-weight: bold; "><?= $dia ?></div>
		<div class="diaLink">
			<button id="Bt<?= $dia ?>" onclick="MostrarDiv('Div<?= $dia ?>','Bt<?= $dia ?>')" Style="width:100px; height:25px; padding: 2px 2px ">Abrir</button>
		</div>
	</div>
	<div id="Div<?= $dia ?>" style="display:none"> <!-- Div do dia -->
<?php    

	// =================================================================
	// Mostra os horarios ==============================================
	// =================================================================

    foreach ($horarios as $hora => $turmas) { //For dos Horarios
		?>	
		<div class="listaHorarios" style="width:365px">
			<div class="classDia" style="width: 244px;color: #000000;"><?= $hora?> Horas</div>
				<button id="Bt<?= $dia ?><?= $hora?>" onclick="MostrarDiv('Div<?= $dia ?><?= $hora?>','Bt<?= $dia ?><?= $hora?>')" Style="width:100px; height:25px; padding: 2px 2px;background-color: #ceb00a; border: 1px solid">Abrir</button>
		</div>
		<div id="Div<?= $dia ?><?= $hora?>" style="display:none"> <!-- Div da hora -->
        
		<?php

		// =================================================================
		// Mostra as turmas ================================================
		// =================================================================
        foreach ($turmas as $turma) {   //For das Oficinas         
?>

			<div class="listaOficinas" style="width:365px;gap: 5px;">
				<div class="classDia"><li><?= $turma['tu_oficina'] ?></li></div>
					<form method="POST" action="horario_editar.php" enctype="multipart/form-data">
						<input type="hidden" name="FormTuId" id="FormTuId" value="<?= $turma['tu_id'] ?>" />
						<button type="submit" Style="width:60px; height:25px; padding: 2px 2px;background-color: #037d96; border: 1px solid">Editar</button>
					</form>
				<div class="diaLink"><button onclick="MostrarChamada('<?= $turma['tu_id'] ?>')" Style="width:80px; height:25px; padding: 2px 2px;background-color: #037d96; border: 1px solid">Visualizar</button></div>
			</div>
<?php
        } // For das Oficinas

		echo "</div> <!-- Div da hora -->";
    } // For dos Horarios

	echo "</div> <!-- Fecha Div do dia -->";
} // For dos dias da semana



?>
	</div><!--div id="corpoDias"-->




	<div id="corpoLista">		
		
	</div>


<script type="text/javascript" language="javascript">

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
	


	function MostrarChamada(tu_id) {
				
		if (tu_id == "") {
			document.getElementById("corpoLista").innerHTML = "";
			return;
		} else {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("corpoLista").innerHTML = this.responseText;
				}
			};
		
		xmlhttp.open("GET","horario_ajax.php?AC=In&tu_id="+tu_id,true);
		xmlhttp.send();
		}
				
	}
			
</script>


</body>
</html>
