
<style>
table.type07 {
    border-collapse: collapse;
    text-align: left;
    line-height: 1.5;
    border: 1px solid #ccc;
//    margin: 20px 10px;
}
table.type07 thead {
    border-right: 1px solid #ccc;
    border-left: 1px solid #ccc;
    background: #e7708d;
}
table.type07 thead th {
    padding: 10px;
    font-weight: bold;
    vertical-align: top;
    color: #fff;
}
table.type07 tbody th {
    padding: 10px;
    font-weight: bold;
    vertical-align: top;
    border-bottom: 1px solid #ccc;
    background: #fcf1f4;
}
table.type07 td {
    padding: 10px;
    vertical-align: top;
    border-bottom: 1px solid #ccc;
}

</style>


<?php

	require_once("dbconfig.php");

		$n = $_POST[limit] + 30;
		$n_list = $_POST[list_num];

		echo $n;
		echo "<br>";
		echo $n_list;

		$sql = "SELECT * FROM board ORDER BY b_num DESC LIMIT $n, $n_list";
		$result = mysql_query($sql, $mydb) or die("SQL 에러");



		echo "
		<table class = type07  border = 1>
				<thead>
				 <tr>
				  <th> 번호 </th>
				  <th width = 300> 제목 </th>
				  <th> 작성자 </th>
				  <th> 작성일 </th>
				 </tr>
				</thead>";

			while($row = mysql_fetch_array($result))
			{

			echo "<tr>
			<th class=num> $row[b_num] </th>
				<td class=title>
				<a href=dpBoard2.php?num=$row[b_num]&wh=read&limit=$n&list_num=$n_list>$row[b_title]</a></td>
				<td class=writer>$row[b_id]</td>
				<td class=date>$row[b_date]</td>

			</tr>";
			}

		echo "</table>";

?>
