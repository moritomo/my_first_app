<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="Content-Style-Type"content="text/css">        
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
	<title>研究室予定・実験室予約</title>
	<link rel="stylesheet" href="cal6.css" type="text/css">
</head>
<body>
					
<?php
	// 年月を取得する
	// 現在時刻しらべる
	$timeNow = time() + 9*3600 /*- date("Z")*/;
//	echo date("Y/m/d H:i:s (D)", $timeNow);

	$ym = $_SESSION['ym'];

	//GETにあれば、それ。なければ、現在
	//三項演算子で２条件できれば、こー書きたい
//	$ym = isset($_GET['ym']) ? $_GET['ym'] : date("Y-m" ,$timeNow);
//	$ym = isset($_GET['ym']) && $_GET['ym'] !== "" ) ? $_GET['ym'] : date("Y-m" ,$timeNow);
	
	if(isset($_GET['ym']) && $_GET['ym'] !== null){
		$ym = $_GET['ym'];
//		echo $_GET['ym']."tes";
	}else{
		$ym = date("Y-m" ,$timeNow);
	}
	 
	$timeStamp = strtotime($ym . "-01");
//	echo date("Y/m/d H:i:s (D)", $timeStamp);

	if($timeStamp === false){	
		$timeStamp = $timeNow;
		$ym = date("Y-m",$timeNow);
//		echo date("Y/m/d H:i:s (D)", $timeStamp);
//		echo "nulllll";
	}
	$y = date("Y", $timeStamp);
	$m = date("m", $timeStamp);
//	echo date("j", $timeNow);
//	echo $ym."tes";
	
	$prev = date("Y-m", mktime(0,0,0,date("m", $timeStamp)-1,1,date("Y",$timeStamp)));
	$next = date("Y-m", mktime(0,0,0,date("m", $timeStamp)+1,1,date("Y",$timeStamp)));
	
	session_start();
	$_SESSION['ym'] = $ym;
	


?>

<?php

header('Content-Transfer-Encoding: binary');

/*----------------------------------------------------*/

//MySQL 入力先データベースへ接続{

//データベースの名前
$db_name = "db_naka";
//ホスト名
$host_name = "localhost";
$dsn = 'mysql:dbname='.$db_name.';host='.$host_name.';charset=utf8';
//ユーザー名
$user = 'nakauser';
//パスワード
$password = 'nakapass';
//テーブル名
$table_name = "nakatable";

//}

/*----------------------------------------------------*/

//MySQLサーバにアクセス
try{
    $dbh = new PDO($dsn, $user, $password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//print('接続ok');
}catch (PDOException $e){
    print('Connection failed:'.$e->getMessage());
    exit;
}

/*----------------------------------------------------*/

// SELECT
$stmt = $dbh->query("SELECT * FROM ".$table_name);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$id = $row['id'];
	$day = $row['day'];
	$type = $row['type'];
	$name = $row['name'];
	$timeSta = $row['timeSta'];
	$timeFin = $row['timeFin'];
	$created = $row['created'];
	
	/*
	
	echo $id.' ';
	echo $day.' ';
	echo $type.' ';
	echo $name.' ';
	echo $timeSta.' ';
	echo $timeFin.' ';
	echo $created.' ';
	echo '<br>';
	
	*/
}

/*----------------------------------------------------*/

//INSERT
if(isset($_POST['insertButton'])){
	if($created != $_POST['created']){
		$day = $_POST['date'];
		$type = $_POST['type'];
		$name = htmlspecialchars(trim($_POST['name']));
		$timeSta = $_POST['timeSta'];
		$timeFin = $_POST['timeFin'];
		$created = $_POST['created'];

		$stmt2 = $dbh->prepare("insert into ".$table_name." (id, day, type, name, timeSta, timeFin, created) values (:id, :day, :type, :name, :timeSta, :timeFin, :created)");

		$stmt2->bindValue(':id', null, PDO::PARAM_INT);
		$stmt2->bindParam(':day', $day, PDO::PARAM_STR);
		$stmt2->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt2->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt2->bindParam(':timeSta', $timeSta, PDO::PARAM_STR);
		$stmt2->bindParam(':timeFin', $timeFin, PDO::PARAM_STR);
		$stmt2->bindParam(':created', $created, PDO::PARAM_STR);
		$stmt2->execute();
	}
}

//DELETE
elseif(isset($_POST['deleteButton'])){
	$stmt3 = $dbh->prepare("DELETE FROM ".$table_name." WHERE id =? ");
	$stmt3 -> execute(array($_POST['deleteName']));
}

/*----------------------------------------------------*/

?>


<?php


$val = array();
if (isset($_POST['type'])) {
	$_SESSION['types'] = $_POST['type'];
	foreach($_POST['type'] as $key => $value){
		$val[$key] = $value;
//		echo $val[$key];
	}
}


$val2 = array();
foreach($_SESSION['types'] as $key => $value){
	$val2[$key] = $value;
//	echo $val2['$key'];
}
?>

<form action="cal6.php?ym=<?php echo $_SESSION['ym'] ?>" method="post">
	<div class="appoSelect">
		<span class=ckbox>
		<span class="set"><input type="checkbox" name="type[]" value="0" class="hand" id="check0" onchange="submit(this.form)"
		<?php
			if (isset($_SESSION['types']) && isset($_POST['type'])) {
				foreach($_SESSION['types'] as $key => $value){
					$val[$key] = $value;
					if($val[$key]=="0"){
						echo "checked";
					}
				}
			}else{
				echo "checked";
			}
		?>
		><label class="hand" for="check0">輪講</label></span>&nbsp;
		<span class="set"><input type="checkbox" name="type[]" value="1" class="hand" id="check1" onchange="submit(this.form)"
		<?php
			if (isset($_SESSION['types'])) {
				foreach($_SESSION['types'] as $key => $value){
					$val[$key] = $value;
					if($val[$key]=="1"){
						echo "checked";
					}
				}
			}else{
				echo "checked";
			}
		?>
		><label class="hand" for="check1">実験室の予約</label></span>&nbsp;
		<span class="set"><input type="checkbox" name="type[]" value="2" class="hand" id="check2" onchange="submit(this.form)"
		<?php
			if (isset($_SESSION['types'])) {
				foreach($_SESSION['types'] as $key => $value){
					$val[$key] = $value;
					if($val[$key]=="2"){
						echo "checked";
					}
				}
			}else{
				echo "checked";
			}
		?>
		><label class="hand" for="check2">中西さんの予定</label></span>&nbsp;
		<span class="set"><input type="checkbox" name="type[]" value="3" class="hand" id="check3" onchange="submit(this.form)"
		<?php
			if (isset($_SESSION['types'])) {
				foreach($_SESSION['types'] as $key => $value){
					$val[$key] = $value;
					if($val[$key]=="3"){
						echo "checked";
					}
				}
			}else{
				echo "checked";
			}
		?>
		><label class="hand" for="check3">研究室の予定</label></span>&nbsp;
		<span class="set"><input type="checkbox" name="type[]" value="4" class="hand" id="check4" onchange="submit(this.form)"
		<?php
			if (isset($_SESSION['types'])) {
				foreach($_SESSION['types'] as $key => $value){
					$val[$key] = $value;
					if($val[$key]=="4"){
						echo "checked";
					}
				}
			}else{
				echo "checked";
			}
		?>
		><label class="hand" for="check4">その他</label></span>&nbsp;
	</span>
	</div>

	<span>
		<a class="todayLink" href="cal6.php">today</a>
	</span>
</form>

<h2>	
	<table class="above">
		<tr>
			<th><a href="?ym=<?php echo $prev; ?>">&laquo;</a></th>
			<th><?php echo date("Y",$timeStamp)."-".date("n",$timeStamp); ?></th>
			<th><a href="?ym=<?php echo $next; ?>">&raquo;</a></th>
		</tr>
	</table>
</h2>


<table border="1" class="cal">
    <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
    </tr>
 	<tr>
		<?php
		// 1日の曜日を取得
		$wd1 = date("w", mktime(0, 0, 0, $m, 1, $y));
		// その数だけ空白を表示
		for ($i = 1; $i <= $wd1; $i++) {
    		echo "<td>　</td>";
		}
 
		// 1日から月末日までの表示
		$d = 1;
		
//		echo date("Y/m/d H:i:s (D)", $timeStamp);
//		echo date("Y/m/d H:i:s (D)", $timeNow);
		
		while (checkdate($m, $d, $y)) {
			if($y == date("Y",$timeNow) && $m == date("m",$timeNow) && $d == date("j",$timeNow)){
				echo "<td class='today'>";
				//echo "tes";
			}else{
				echo "<td class='dayOk'>";
				//echo "nooo";
			}
			echo "<a href='cal62.php?yearmonth=$ym&day=$d'>";
			echo "$d";
			if($y == date("Y",$timeNow) && $m == date("m",$timeNow) && $d == date("j",$timeNow)){
		?>
				<span class="schedule" class="todayMark">&nbsp;<?php echo " <u>Today</u>";?></span>
		<?php		
			}
			echo "<br>";
			$stmt = $dbh->query('SELECT type,name,timeSta,timeFin FROM '.$table_name.' where day = "'.$y.'-'.$m.'-'.$d.'"');
			
			$count = 0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$type = $row['type'];
				$name = $row['name'];
				$timeSta = $row['timeSta'];
				$timeFin = $row['timeFin'];
				
				$val3 = array();
				foreach($_SESSION['types'] as $key => $value){
					$val3[$key] = $value;
				//	echo $val3['$key'];
					if($val3[$key] == $type){
						echo "<div class='schedule'>";
						echo "・";
						echo $name.'(';
						echo $timeSta.'-';
						echo $timeFin.')';
						echo "</div>";
					}
				}
				
				
				$count++;
				if($count >= 3 && $stmt->rowCount() >= 4){
					$other = $stmt->rowCount() - $count;
					echo "<div class='schedule'>...他".$other."件</div>";
					break;
				}
				
				
			}
			
			echo"</a>
				<!--テスト-->
				</td>";
			// 今日が土曜日の場合は…
			if (date("w", mktime(0, 0, 0, $m, $d, $y)) == 6) {
			    // 週を終了
			    echo "</tr>";
				// 次の週がある場合は新たな行を準備
		    	if (checkdate($m, $d + 1, $y)) {
		        	echo "<tr>";
		    	}
			}
			$d++;
		}
		// 最後の週の土曜日まで移動
		$wdx = date("w", mktime(0, 0, 0, $m + 1, 0, $y));
		for ($i = 1; $i < 7 - $wdx; $i++) {
		    echo "<td>　</td>";
		}
		?>
    </tr>
</table>
	
	<br>
	<a href="index.php">戻る</a>
<!--	
	<?php 
		echo $_SESSION['types'][2]; 
		echo count($_SESSION['types']);
	?>
-->
</body>
</html>