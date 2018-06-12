<?php

require_once("dbconfig.php");

//mysql 입출력 인코딩
mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");
?>

<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<title>돌피 page</title>
</head>
<body>
<a href ="dpBoard.php?"><h3>돌피 게시판</h3></a>

<?php
//글 작성 UI
if($_GET[wh]=='write')
{

?>
<h3>글 작성</h3>
			<form method="post" action="dpBoard.php?mod=insert">
			<p>제목 <input type="text" name="title" size="40"></p>
			<p>이름 <input type="text" name="id" size="40"></p>
			<p>비밀번호 <input type="password" name="pwd" size="40"></p>
			<p>내용</p>
			<textarea cols="50" rows="20" name="content"></textarea> <br><br>
			<input type="submit" value="보내기"/>
			<input type="button"  value="목록" onclick="location.href='dpBoard.php?Page=1'">
			<br>
			</form>

<?
}

//글 수정 UI
if($_GET[wh]=='modify')
{

	$num = $_GET["num"];
	$title = $_POST["title"];
	$id = $_POST["id"];
	$pwd = $_POST["pwd"];
	$content = $_POST["content"];

	$sql = "SELECT * FROM board WHERE b_num = '$num'";
	$re = mysql_query($sql, $mydb) or die("SQL 에러");
	$result = mysql_fetch_array($re); //결과를 배열로 가져온다. 밑에 글 수정에 출력할 때 쓰인다.
	?>
		<form method="post" action="dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$result[b_num]?>&mod=update">

			<h3>글 수정</h3>
			<p>제목 <input type="text" name="title" size="40" value="<?=$result[b_title];?>"</p>
			<p>이름 <input type="text" name="id" size="40" value="<?=$result[b_id];?>"</p>
			<p>비밀번호 <input type="password" name="pwd" size="40"></p>
			<p>내용</p>
			<textarea cols="50" rows="20" name="content"><?=$result[b_content];?></textarea><br>

			<input type="submit" value="수정하기"/>
			<input type="button"  value="목록" onclick="location.href='dpBoard.php?Page=1'";>
		</form>
		<br>
		<hr>

<?
}

//글 보기  UI
if($_GET[wh] == 'read') {

			$num = $_GET["num"];


			$re = mysql_query("SELECT * FROM board WHERE b_num = '$num'");

			//전체 리스트 수
			$total_record = mysql_num_rows($re);

			$result = mysql_fetch_array($re); //결과를 배열로 가져온다. 밑에 글 보기에 출력할 때 쓰인다.
			echo $_GET[Page];
			echo $limit;
			?>

			<h3>글 보기</h3>
			<form method="post" action="dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$result[b_num];?>&wh=modify&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>&field=$field">


			<table class = 'read' border = '1'>
			<thead>
			<tr><td>제목 </td><td width = "300"><?=$result[b_title];?></td>
			<tr><td>이름 </td><td width = "300"><?=$result[b_id];?></td>
			<tr><td>내용</td><td width = "300" height = "100"><?=$result[b_content];?></td>
			</thead>
			</table>
			<br>
				<input type="submit"  value="수정" ;>
				<input type="button"  value="목록" onclick="location.href='dpBoard.php?Page=1'">
				<input type="button" value="삭제" onClick="location.href='dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$result[b_num];?>&wh=delete&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>'">
			</form>

			<!-- 댓글 UI -->
			<h4>댓글 달기</h4>
			<div id="comment">
				<form action="dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$result[b_num];?>&wh=comment_insert&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>" method="post" name="comment_input">
				<input type="hidden" value="<?= $num?>" name="num"/>
					<table>
						<tr>
							<td><textarea cols="80" rows="6" placeholder="댓글을 입력해주세요." name="c_content"></textarea></td>
							<td style="padding:0 0 3px 15px; ">
							<input type="submit" value="댓글달기"  class="c_btn" /></td>
						</tr>
					</table>
				</form>
			</div>

			<!-- 댓글 출력 -->
			<div id="comment_result">
			<table width="750" >
			<?php
			$sql = "SELECT * FROM comment where b_num ='$num' order by c_num desc"; //comment 테이블 출력

			$result = mysql_query($sql,$mydb);

			while($row = mysql_fetch_array($result)){ //위 결과 값을 하나 하나 배열로 저장
				$c_num= $row['c_num'];
				$c_content = $row['c_content'];
				$c_id = $row['c_id'];
				$c_date = $row['c_date'];

			?>

				<tr>
					<div><b><?=$c_id?></b></div>
					<td width='620'>
						<div style='border-left:1px solid #888888;'>
						<div id='re_content'><?=$c_content?></div>
						<div id='re_date'><?=$c_date?></div>
						</div>
					</td>
				</tr>
			<?
			}
			?>
			</table>
		</div>
		<br>
		<hr>
<?}

	//글 삭제 UI
	 if($_GET[wh] =='delete') {
		 $num = $_GET["num"];
		$title = $_POST["title"];
		$id = $_POST["id"];
		$pwd = $_POST["pwd"];
		$content = $_POST["content"];

		$re = mysql_query("SELECT * FROM board WHERE b_num = '$num'") or die("SQL 에러");
		$result = mysql_fetch_array($re); //결과를 배열로 가져온다. 밑에 삭제에 출력할 때 쓰인다.

	?>
	<!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->
	<h3>글 삭제</h3>
	<form method="post" action="dpBoard.php?Page=<?=$_GET[Page]?>&num=<?=$result[b_num]?>&mod=delete1">


	<table width=300 border=0  cellpadding=2 cellspacing=1 bgcolor=#777777>
	<tr>
		<td height=20 align=center bgcolor=#999999>
			<font color=white><B>비 밀 번 호 확 인</B></font>
		</td>
	</tr>
	<tr>
		<td align=center >
			<font color=white><B>비밀번호 : </b>
			<INPUT type=password name=pwd size=8 maxlength=8>
			<INPUT type=submit value="확 인">
			<INPUT type=button value="취 소" onclick="location.href='dpBoard.php?Page=1'">
		</td>
	</tr>
	</table>
	</form>
	<br>
	<hr>
	<?
	 }


	//댓글 삽입 기능
	if($_GET[wh] == 'comment_insert') {
		$c_id = $_POST['c_id'];
		$num = $_GET['num'];
		$c_num = $_POST['c_num'];
		$c_content = $_POST['c_content'];
		$c_date = date("Y-m-d H:i:s");
		$limit = $_GET['limit'];
		$list_num = $_GET['list_num'];
		$Page = $_GET['Page'];

			$sql = "INSERT INTO comment SET c_content='$c_content', c_date='$c_date', c_id='$c_id', b_num='$num'";

			mysql_query($sql, $mydb) or die("SQL 에러");
			echo "<script>location.replace('dpBoard.php?Page=$Page&wh=read&num=$num&limit=$limit&list_num=$list_num');</script>";

	}


	//글 삭제 기능
	if($_GET[mod] == 'delete1') {

				$num = $_GET["num"];
				$pwd = $_POST["pwd"];
				$result = mysql_query("SELECT * FROM board WHERE b_num = '$num'") or die("SQL 에러");
				$row = mysql_fetch_array($result);//위 결과 값을 하나 하나 배열로 저장



				if($pwd == $row[b_pwd]) {
				mysql_query("DELETE FROM board WHERE b_num = '$num'");


				}

	}


	//글 삽입 기능
	if($_GET[mod]=='insert')
	{

				$num = $_GET["num"];
				$title = $_POST["title"];
				$id = $_POST["id"];
				$pwd = $_POST["pwd"];
				$content = $_POST["content"];
				$date = date("Y-m-d H:i:s");

				$sql = "INSERT INTO board SET b_num='$num',b_title='$title', b_content='$content', b_date='$date', b_id='$id', b_pwd='$pwd'";

				mysql_query($sql, $mydb) or die("SQL 에러");

				$re = mysql_query("SELECT * FROM board WHERE b_num = '$num'");
				$result = mysql_fetch_array($re);

	}


	//글 수정 기능
	if($_GET[mod]=='update')
	{
				$num = $_GET["num"];
				$title = $_POST["title"];
				$id = $_POST["id"];
				$pwd = $_POST["pwd"];
				$content = $_POST["content"];

				$result = mysql_query("SELECT b_pwd FROM board WHERE b_num = '$num'");
				$row = mysql_fetch_array($result);

				if($pwd == $row[b_pwd]) {

				mysql_query("UPDATE board SET b_title = '$title', b_content = '$content' WHERE b_num = '$num'");

				}
	}


	//검색(제목, 내용, 글쓴이) 기능
	//글 분류
	if(isset($_GET['field'])) //반환값이 있으면 true, 없으면 false
	{
		$field = $_GET['field'];
	}

	//검색어
	if(isset($_GET['search_text']))//반환값이 있으면 true, 없으면 false
	{
		$search_text = $_GET['search_text'];
	}

	if(isset($_GET['search_text']) && isset($_GET['field']))//반환값이 있으면 true, 없으면 false
	{
		$query = "where $field like '%$search_text%'";

	}else {
		$query = "";
	}

?>


<br>

<div class = "board_list">

<table class = "list" border = 1>
<thead>
 <tr>
  <td> 번호 </td>
  <td width = "300"> 제목 </td>
  <td> 작성자 </td>
  <td> 작성일 </td>
 </tr>
</thead>


 <?php
		if(empty($limit = $_GET[limit])){ //현재 선택한 페이지 번호
				$limit = 0;
				$list_num = 10; //한 페이지 나타낼 게시물 개수
		}

		else{
				$limit = $_GET[limit];
				$list_num = $_GET[list_num];
		}


		$totalCnt = "SELECT count(*) FROM board $query"; ///총 레코드 수
		$re2 = mysql_query($totalCnt, $mydb) or die("SQL 에러");
		$array = mysql_fetch_array($re2);
		$cnt = $array[0]; // 총 레코드 수


		$sql = "SELECT * FROM board $query ORDER BY b_num DESC LIMIT $limit, $list_num";
		$result = mysql_query($sql, $mydb) or die("SQL 에러");
		$total_record = mysql_num_rows($result); //게시판에서 총 게시물 수를 뽑아낼 때 쓰는 함수

			while($row = mysql_fetch_array($result))
			{
			?>
			<tr>
			<td class="num"><?= $row["b_num"];?></td>
				<td class="title">

				<?php

					if($_GET[num] == $row["b_num"]){ // 여기서는 링크를 없애고
					?>
						<?=$row["b_title"];?></a></td>
						<td class="writer"><?=$row["b_id"];?></td>
						<td class="date"><?= $row["b_date"];?></td>
					<?
					}

					else if (!empty($_GET[search_text] && $_GET[field])) { //검색 리스트에서 글 클릭할 때
					?>

						<a href="dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$row["b_num"];?>&wh=read&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>&field=<?=$_GET[field]?>&search_text=<?=$_GET[search_text]?>"><?=$row["b_title"];?></a></td>
						<td class="writer"><?=$row["b_id"];?></td>
						<td class="date"><?= $row["b_date"];?></td>

					<?
					}

					else{// 여기에서는 링크 넣기
						?>
						<a href="dpBoard.php?Page=<?=$_GET[Page];?>&num=<?=$row["b_num"];?>&wh=read&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>"><?=$row["b_title"];?></a></td>
						<td class="writer"><?=$row["b_id"];?></td>
						<td class="date"><?= $row["b_date"];?></td>
					<?}
				?>
			</tr>
			<?php
				}?>
		</table>

		<!-- 페이징 -->
		<?

		$list_num = 10; //페이지 당 데이터 수
		$pageNum = ceil($cnt / $list_num); //올림함수, 총 페이지 수



		$num_pages = ceil($cnt / $list_num); //총페이지수
		$page_scale = 10; //페이징 할 수
		$cur_page = isset($_GET['Page']) ? $_GET['Page'] : 1; //현재 페이지




		$page_links = '';
		$temp = (ceil(($cur_page) / $page_scale)-1) * ($page_scale) + 1 ; //버림 함수, 페이지!!=page
		$limit = $_GET[limit];


	if ($cnt == 0) { //검색 결과 데이터가 없을 때, 출력
		echo '검색 결과 없음';
	}

	 else if (!empty($_GET[field] && $_GET[search_text] && $_GET[wh] && $_GET[num])) { //검색시 하단 페이징 유지
		 if($temp > 1) {
		   $page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($_GET[wh]).'&num='.($num).'&Page=1'.'&field='.($field).'&search_text='.($search_text).'">[처음]</a>
			<a href="'.$_SERVER['PHP_SELF'].'?wh='.($_GET[wh]).'&num='.($num).'&Page='.($temp - $page_scale).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit - ($list_num * $page_scale)).'&list_num='.($list_num).'">[이전]</a>';
		  }


		  for($i = 0; $i < $page_scale; $i++) {

			$limit = ($temp + $i - 1) * $list_num;


		   if(($temp + $i) > $num_pages) { //총 페이지 수보다 크면 나가기!
			break;
		   }

		   if($cur_page == $temp + $i) { //현재 페이지면 링크 삭제
			$page_links .= ($temp + $i).'&nbsp;';
		   }

		   else { //기본값
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($_GET[wh]).'&num='.($num).'&Page='.($temp + $i).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit).'&list_num='.($list_num).'">'.($temp+$i).'&nbsp;</a>';
		   }
		  }

		  if($temp + $page_scale <= $num_pages) {
		   $page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($_GET[wh]).'&num='.($num).'&Page='.($temp+$page_scale).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit + 10).'&list_num='.($list_num).'">[다음]</a>
			<a href="'.$_SERVER['PHP_SELF'].'?wh='.($_GET[wh]).'&num='.($num).'&Page='.($num_pages).'&field='.($field).'&search_text='.($search_text).'&limit='.($cnt -($cnt % $list_num)).'&list_num='.($list_num).'">[마지막]</a>';
		  }
		  echo $page_links;
	}


	else if(!empty($_GET[field] && $_GET[search_text])){ //검색 할 때의 페이징 - 검색 결과 페이징 유지


		if($temp > 1) {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page=1'.'&field='.($field).'&search_text='.($search_text).'">[처음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp - $page_scale).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit - ($list_num * $page_scale)).'&list_num='.($list_num).'">[이전]</a>';
		}


		for($i = 0; $i < $page_scale; $i++) {

			$limit = ($temp + $i - 1) * $list_num;

			if(($temp + $i) > $num_pages) { //총 페이지 수보다 크면 나가기!
				break;
			}

			if($cur_page == $temp + $i) { //현재 페이지면 링크 삭제
				$page_links .= ($temp + $i).'&nbsp;';
			}

			else { //기본값
				$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp + $i).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit).'&list_num='.($list_num).'">'.($temp+$i).'&nbsp;</a>';
			}
		}

		if($temp + $page_scale <= $num_pages) { //10단위 페이징
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp+$page_scale).'&field='.($field).'&search_text='.($search_text).'&limit='.($limit + 10).'&list_num='.($list_num).'">[다음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?Page='.($num_pages).'&field='.($field).'&search_text='.($search_text).'&limit='.($cnt -($cnt % $list_num)).'&list_num='.($list_num).'">[마지막]</a>';
		}
		echo $page_links;

	}

	else if(!empty($_GET[wh])) { //수정, 삭제, 보기 할때 - 하단 페이징 유지

	$wh = $_GET[wh];
	$num = $_GET[num];
	$field = $_GET[field];
	$search_text = $_GET[search_text];


		if($temp > 1) {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($wh).'&num='.($num).'&Page=1">[처음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?wh='.($wh).'&num='.($num).'&Page='.($temp - $page_scale).'&limit='.($limit - ($list_num * $page_scale)).'&list_num='.($list_num).'">[이전]</a>';
		}


		for($i = 0; $i < $page_scale; $i++) {

			$limit = ($temp + $i - 1) * $list_num;

			if(($temp + $i) > $num_pages) { //총 페이지 수보다 크면 나가기!
				break;
			}

			if($cur_page == $temp + $i) { //현재 페이지면 링크 삭제
				$page_links .= ($temp + $i).'&nbsp;';
			}


			else { //기본값
				$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($wh).'&num='.($num).'&Page='.($temp + $i).'&limit='.($limit).'&list_num='.($list_num).'">'.($temp+$i).'&nbsp;</a>';
			}
		}

		if($temp + $page_scale <= $num_pages) {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?wh='.($wh).'&num='.($num).'&Page='.($temp+$page_scale).'&limit='.($limit + 10).'&list_num='.($list_num).'">[다음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?wh='.($wh).'&num='.($num).'&Page='.($num_pages).'&limit='.($cnt -($cnt % $list_num)).'&list_num='.($list_num).'">[마지막]</a>';
		}
		echo $page_links;

	}

	else { //검색 하지 않을 때의 페이징 - defalut

	// 여기


		if($temp > 1) {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page=1">[처음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp - $page_scale).'&limit='.($limit - ($list_num * $page_scale)).'&list_num='.($list_num).'">[이전]</a>';
		}


		for($i = 0; $i < $page_scale; $i++) {

			$limit = ($temp + $i - 1) * $list_num;

			if(($temp + $i) > $num_pages) { //총 페이지 수보다 크면 나가기!
				break;
			}

			if($cur_page == $temp + $i) { //현재 페이지면 링크 삭제
				$page_links .= ($temp + $i).'&nbsp;';
			}


			else { //기본값
				$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp + $i).'&limit='.($limit).'&list_num='.($list_num).'">'.($temp + $i).'&nbsp;</a>';
			}

		}

		if($temp + $page_scale <= $num_pages) {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?Page='.($temp+$page_scale).'&limit='.($limit + 100).'&list_num='.($list_num).'">[다음]</a>
				<a href="'.$_SERVER['PHP_SELF'].'?Page='.($num_pages ).'&limit='.($cnt -($cnt % $list_num) ).'&list_num='.($list_num).'">[마지막]</a>';
		}
		echo $page_links;


	}
		?>
</div>

	<input type="button"  value="작성" onclick="location.href='dpBoard.php?Page=<?=$_GET[Page];?>&wh=write&limit=<?=$_GET[limit]?>&list_num=<?=$_GET[list_num]?>'";>
	<input type="button"  value="목록" onclick="location.href='dpBoard.php?Page=1'";>

<!-- 글 검색 UI -->
<form name=search method=get action='<?=$_SERVER['REQUEST_URI']?>'> <!-- 현재 실행중인 자신의 경로-->

<input type=hidden name=num value='<?=$_GET[num];?>'>
<input type=hidden name=wh value='<?=$_GET[wh];?>'>
<input type=hidden name=Page value='<?=$_GET[Page];?>'>
<input type=hidden name=limit value='<?=$_GET[limit];?>'>
<input type=hidden name=list_num value='<?=$_GET[list_num];?>'>
	<select name=field>
	<option value=b_title>제 목</option>
	<option value=b_content>내 용</option>
	<option value=b_id>글쓴이</option>
	</select>
	<input type=text name=search_text size=20><input type=submit value="검색">
</form>
</body>
</html>
