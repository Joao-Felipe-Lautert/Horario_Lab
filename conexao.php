<?php
//phpinfo();


	header('Content-type: text/html; charset=utf-8');
	
	$DataBase = mysqli_connect("localhost", "userfelipe", "rootfelipe", "db_felipe");
    
	mysqli_set_charset($DataBase, "utf8mb4");
	
	
	
  /*
  	$result = mysqli_query($db, 'SELECT al_nome, DATE_FORMAT(al_nascimento,"%d/%m/%Y") AS nascimento, TIMESTAMPDIFF(YEAR,al_nascimento,CURDATE()) AS idade FROM tbl_aluno');
	
	if($result){
		while($row = mysqli_fetch_array($result)){
			$name  = $row["al_nome"];
			$nasc  = $row["nascimento"];
			$idade = $row["idade"];
			
			echo "Nome: ".$name." - Nascimento: ".$nasc." - Idade: ".$idade." anos<br/>";

		}
	}
	*/

 
//mysqli_close($db);


//echo (calcularIdade("04/06/1982"));

?>
