<style>
body{
	font-size:11px;
	font-family:sans-serif;
}
table, tr, td{
	border:1px solid #CCC;
	line-height:25px;
}
</style>

<?php
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db('erp', $link);
if (!$db_selected) {
    die ('Erro na seleççao do DB : ' . mysql_error());
}

$sql = "select p.id,p.descricao as produto,(select sum(qtd_mov) from movimentacao where tipo = 1 and id_produto = p.id) as 'Entrada',
               (select sum(qtd_mov) from movimentacao where tipo = 2 and id_produto = p.id) as 'Saida' from produtos p
				left join movimentacao m
				on(p.id = m.id_produto) 
				group by m.id_produto 
				order by p.descricao";
   
$result = mysql_query($sql);
 
echo "<h2>Lista Movimentação de Estoque</h2>"; 
echo "<table border=1 width=600px cellspacing=5>
		<tr>
			<td>Produto</td>
			<td>Entrada</td>
			<td>Saída</td>
			<td>Quantidade Disponível</td>
    	</tr>";

$entrada = 0;
$saida = 0;
$qtd = 0;

 while ($row = mysql_fetch_assoc($result)){
	 
	 $entrada = $row["Entrada"];
	 $saida = $row["Saida"];
	 $qtd = $entrada - $saida;
	  
	 
	 if($qtd<0){
		 echo "<tr style=color:red;>";
	 }
	 else{
		  echo "<tr>";
	 }

		 echo "<td>{$row["produto"]}</td>";  
		 echo "<td>{$entrada}</td>"; 
		 echo "<td>{$saida}</td>"; 
		 echo "<td>{$qtd}</td>"; 
	  
	echo "</tr>";
 
}

echo "</table>";


?>