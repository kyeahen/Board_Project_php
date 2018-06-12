<?php

require_once("dbconfig.php");

//mysql 입출력 인코딩
mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");

		if(empty($limit = $_GET[limit])){ //현재 선택한 페이지 번호
				$limit = 0;
				$list_num = 30; //한 페이지 나타낼 게시물 수
		}

		else{
				$limit = $_GET[limit];
				$list_num = $_GET[list_num];
		}
?>

<!DOCTYPE html>
<meta charset="utf-8">
<html>
<script type = "text/javascript">

	var limit = '<?= $limit ?>'; // 자바스크립트로 php 변수 전달하는 방법!
	var list_num = '<?= $list_num ?>';
	var sCount = '<?= $sCount ?>';



//function getHttprequest() {
//
//		window.onscroll = function(ev) {
//			if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
//				console.log("스크롤 인식");
//
//				var xhr = new XMLHttpRequest();
//
//				xhr.onreadystatechange = function() {
//
//					if(this.readyState == 4&&this.status == 200) {
//
//						console.log(this.response);
//						var limit_plus = this.responseText
//						document.getElementById("test").innerHTML = limit_plus
//
//					}
//				};
//
//				xhr.open("GET", "dpBoard2_append.php?limit=" + limit + "&list_num=" + list_num, true);
//				xhr.send();
//			}
//		};
//}




function scrollAutoTestFnc(){
   //현재 문서의 높이
   var scrollHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
  //현재 스크롤 탑의 값
  var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);

  //현재 화면 높이 값
  var clientHeight = document.documentElement.clientHeight;

//  if((scrollTop+clientHeight) == scrollHeight){ //스크롤이 마지막일 때

if(scrollTop == scrollHeight - clientHeight) {


    alert(limit) ;

	getHttprequest();

	limit = Number(limit) + Number(30);
	sCount++;

  }
 }



 window.onscroll=function(){
  scrollAutoTestFnc();

 }


function getHttprequest() {

				var xhr = new XMLHttpRequest();
				var formData = new FormData();

				formData.append('limit', limit);
				formData.append('list_num', 30);

				xhr.onreadystatechange = function() {

					if(this.readyState == 4) {

						if (this.status == 200) {
						alert("성공:" + xhr.responseText);
						var limit_plus = this.responseText;
						document.getElementById("test").innerHTML += limit_plus;
						}

					}

					else {
						alert("실패: " + xhr.status);
					}
				};

				xhr.open("POST", "dpBoard2_append.php", true);
				xhr.send(formData);
			}

//function getHttprequest() {
//
//				var xhr = new XMLHttpRequest();
//				var formData = new FormData();
//				formData.append('limit', limit);
//				formData.append('list_num', 30);
//
//				tags = document.getElementById("test");
//
//				xhr.onreadystatechange = function() {
//
//					clone = tags.cloneNode(false);
//
//					if(this.readyState == 4) {
//
//						if (this.status == 200) {
//						alert("성공:" + xhr.responseText);
//						var limit_plus = this.responseText;
//						clone.innerHTML += limit_plus;
//						document.getElementById("test").append(clone);
//
//						}
//
//					}
//
//					else {
//						alert("실패: " + xhr.status);
//					}
//				};
//
//				xhr.open("POST", "dpBoard2_append.php", true);
//				xhr.send(formData);
//			}
</script>

<head>
<title>돌피 Page</title>
<? echo "$limit";
echo "<br>";
echo "$list_num";?>
</head>

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

input {
  border: 1px solid #bcbcbc;
  border-radius: 0px;
  -webkit-appearance: none;
  height: 30px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

</style>
<body>
<a href ="dpBoard2.php?"><h3>돌피 게시판</h3></a>

<?php
//글작성 UI
if($_GET[wh]=='write')
{

?>
<h3>글 작성</h3>
			<form method="post" action="dpBoard2.php?mod=insert">
			<p>제목 <input type="text" name="title" size="40"></p>
			<p>이름 <input type="text" name="id" size="40"></p>
			<p>비밀번호 <input type="password" name="pwd" size="40"></p>
			<p>내용</p>
			<textarea cols="50" rows="20" name="content"></textarea> <br><br>
			<input type="submit" value="보내기"/>
			<input type="button"  value="목록" onclick="location.href='dpBoard2.php?'">
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
		<form method="post" action="dpBoard2.php?num=<?=$result[b_num]?>&mod=update">

			<h3>글 수정</h3>
			<p>제목 <input type="text" name="title" size="40" value="<?=$result[b_title];?>"</p>
			<p>이름 <input type="text" name="id" size="40" value="<?=$result[b_id];?>"</p>
			<p>비밀번호 <input type="password" name="pwd" size="40"></p>
			<p>내용</p>
			<textarea cols="50" rows="20" name="content"><?=$result[b_content];?></textarea><br>

			<input type="submit" value="수정하기"/>
			<input type="button"  value="목록" onclick="location.href='dpBoard2.php?'";>
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

			$result = mysql_fetch_array($re); //겱화를 배열로 가져온다. 밑에 글 보기에 출력할 때 쓰인다.
			echo $_GET[Page];
			echo $limit;
						echo $sCount;
			?>

			<h3>글 보기</h3>
			<form method="post" action="dpBoard2.php?num=<?=$result[b_num];?>&wh=modify&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>&field=$field">


			<table class = 'type07' border = '1'>
			<tr><th>제목 </th><td width = "300"><?=$result[b_title];?></td>
			<tr><th>이름 </th><td width = "300"><?=$result[b_id];?></td>
			<tr><th>내용</th><td width = "300" height = "100"><?=$result[b_content];?></td>
			</table>
			<br>
				<input type="submit"  value="수정" ;>
				<input type="button"  value="목록" onclick="location.href='dpBoard2.php?'">
				<input type="button" value="삭제" onClick="location.href='dpBoard2.php?Page=<?=$_GET[Page];?>&num=<?=$result[b_num];?>&wh=delete&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>'">
			</form>

			<!-- 댓글 UI -->
			<h4>댓글 달기</h4>
			<div id="comment">
				<form action="dpBoard2.php?num=<?=$result[b_num];?>&wh=comment_insert&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>" method="post" name="comment_input">
				<input type="hidden" value="<?= $num?>" name="num"/>
					<table>
						<tr>
							<td><textarea cols="80" rows="6" placeholder="댓글을 입력해주세요." name="c_content"></textarea></td>
							<td style="padding:0 0 3px 15px; ">
							<input type="submit" value="댓글 달기"  class="c_btn" /></td>
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
	<form method="post" action="dpBoard2.php?num=<?=$result[b_num]?>&mod=delete1">


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
			<INPUT type=button value="취 소" onclick="location.href='dpBoard2.php?'">
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
			echo "<script>location.replace('dpBoard2.php?wh=read&num=$num&limit=$limit&list_num=$list_num');</script>";

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

				mysql_query($sql, $mydb) or die("SQL 에런");

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
	if(isset($_GET['field'])) //반환 값이 있으면 true, 없으면 false
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

<table class = "type07" border = 1>
<thead>
 <tr>
  <th> 번호 </th>
  <th width = "300"> 제목 </th>
  <th> 작성자 </th>
  <th> 작성일 </th>
 </tr>
</thead>


 <?php
		if(empty($limit = $_GET[limit])){ //현재 선택한 페이지 번호
				$limit = 0;
				$list_num = 30; //한 페이지 나타낼 게시물 개수
		}

		else{
				$limit = $_GET[limit];
				$list_num = $_GET[list_num];
		}


		$totalCnt = "SELECT count(*) FROM board $query"; //총 레코드 수
		$re2 = mysql_query($totalCnt, $mydb) or die("SQL 에러");
		$array = mysql_fetch_array($re2);
		$cnt = $array[0]; // 총 레코드 수
	echo $cnt;


		$sql = "SELECT * FROM board $query ORDER BY b_num DESC LIMIT $limit, $list_num";
		$result = mysql_query($sql, $mydb) or die("SQL 에러");
		$total_record = mysql_num_rows($result); //게시판에서 총 개시물 수를 뽑아낼 때 쓰는 함수

			while($row = mysql_fetch_array($result))
			{
			?>
			<tr>
			<th class="num"><?= $row["b_num"];?></th>
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

						<a href="dpBoard2.php?num=<?=$row["b_num"];?>&wh=read&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>&field=<?=$_GET[field]?>&search_text=<?=$_GET[search_text]?>"><?=$row["b_title"];?></a></td>
						<td class="writer"><?=$row["b_id"];?></td>
						<td class="date"><?= $row["b_date"];?></td>

					<?
					}

					else{// 여기에서는 링크 넣기
						?>
						<a href="dpBoard2.php?num=<?=$row["b_num"];?>&wh=read&limit=<?=$_GET[limit];?>&list_num=<?=$_GET[list_num];?>"><?=$row["b_title"];?></a></td>
						<td class="writer"><?=$row["b_id"];?></td>
						<td class="date"><?= $row["b_date"];?></td>
					<?}
				?>
			</tr>
			<?php
				}?>
		</table>

	<?


	if ($cnt == 0) { //검색 결과 데이터가 없을 때, 출력
		echo '검색 결과 없음';
	}

	?>
		<div id = 'test'>
	</div>

</div>







	<input type="button"  value="작성" onclick="location.href='dpBoard2.php?wh=write&limit=<?=$_GET[limit]?>&list_num=<?=$_GET[list_num]?>'";>
	<input type="button"  value="목록" onclick="location.href='dpBoard2.php?'";>





<!-- 글 검색 UI -->
<form name=search method=get action='<?=$_SERVER['REQUEST_URI']?>'> <!-- 현재 실행 중인 자신의 경로-->

<input type=hidden name=num value='<?=$_GET[num];?>'>
<input type=hidden name=wh value='<?=$_GET[wh];?>'>
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
