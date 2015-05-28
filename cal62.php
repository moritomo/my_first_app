<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type"content="text/css"> 
    <meta http-equiv="Content-Script-Type" content="text/javascript" /> 
	<title>登録ページ</title>
	<link rel="stylesheet" href="cal6.css" type="text/css">
</head>
<body class="cal62">
<!--<div align="center">-->

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
?>

<?php
	$yearmonth = $_GET['yearmonth'];
	$day = sprintf("%02d", $_GET['day']);
	
	session_start();
//	echo $_SESSION['ym'];
//	echo $_SESSION['timeNow'];

	$types = $_SESSION['types'];
	$_SESSION['types'] = $types;

?>
	<h2><?php echo $yearmonth; ?>-<?php echo $day; ?></h2>
	<h4>■予定一覧</h4>
<?php
	$stmt = $dbh->query('SELECT id,type,name,timeSta,timeFin FROM '.$table_name.' where day = "'.$yearmonth.'-'.$day.'"');
	
	//var_dump($stmt->rowCount());
	if($stmt->rowCount() !== 0){
?>
		<form id="deleteForm" action="cal6.php?ym=<?php echo $_SESSION['ym'] ?>" method="post">
			<div style="display:none;"><input type="radio" value="hoge" name="deleteName" class="radio"></div>
		<table border='1' class='allAppo'>
			<tr class='allAppo'>
				<th class='allAppo'>内容</th>
				<th class='allAppo'>詳細（名前）</th>
				<th class='allAppo'>時間</th>
				<th class='allAppo'><input type="submit" value="削除" name="deleteButton" class="deleteButton"></th>
			</tr>
	
<?php
			//$deleteCount = 0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$id = $row['id'];
				$type = $row['type'];
				$name = $row['name'];
				$timeSta = sprintf("%02d", $row['timeSta']);
				$timeFin = sprintf("%02d", $row['timeFin']);
?>
			<tr class="allAppo">
				<td class="allAppo">
					<?php
						switch ($type){
					    	case 0:
					        	echo "輪講";
					        	break;
					    	case 1:
					    		echo "実験室";
					    		break;
					    	case 2:
					        	echo "中西さん";
					        	break;
					    	case 3:
					        	echo "研究室";
					        	break;
					    	default:
					        	echo "その他";
								break;
						}
					?>
					
				</td>
				<td class="allAppo"><?php echo $name; ?></td>
				<td class="allAppo"><?php echo $timeSta ?> - <?php echo $timeFin ?></td>
				<td class="allAppo">
					&nbsp;&nbsp;<input type="radio" value="<?php echo $id ?>" name="deleteName" class="hand">
				</td>
			</tr>
<?php
			//$deleteCount++;
			}
?>
		</table>
		
		
		</form>
<?php
	}else{
		echo "まだ予定はありません。<br><br>";
	}
?>
	

<?php
	$createTime = time() + 9*3600 /*- date("Z")*/;
	$created = date('Y-m-d H:i:s', $createTime);
//	echo $created;
?>

<br>
<h4>■新規登録</h4>

<form id="aForm" action="cal6.php?ym=<?php echo $_SESSION['ym'] ?>" method="post">
<table id="insertTable">
	<tr>
		<td>日付：</td>
		<td><input type="date" name="date" value="<?php echo $yearmonth ?>-<?php echo $day ?>" class="insertArea">
		</td>
	</tr>
	<tr>
		<td>内容：</td>
		<td><select name="type" class="insertArea">
				<option value="">＜選んでください＞</option>
				<option value="0">輪講</option>
				<option value="1">実験室の予約</option>
				<option value="2">中西さんの予定</option>
				<option value="3">研究室の予定</option>
				<option value="4">その他</option>
  			</select>
		</td>
	</tr>
	<tr>
		<td>詳細(名前)：</td>
		<td><input type="text" name="name" size="20" class="insertArea">
		</td>
	</tr>
	<tr>
		<td>時間：</td>
		<td><select name="timeSta">
	  <option value="7">7時</otion>
	  <option value="8">8時</otion>
	  <option value="9">9時</otion>
	  <option value="10">10時</otion>
	  <option value="11">11時</otion>
	  <option value="12">12時</otion>
	  <option value="13">13時</otion>
	  <option value="14">14時</otion>
	  <option value="15">15時</otion>
	  <option value="16">16時</otion>
	  <option value="17">17時</otion>
	  <option value="18">18時</otion>
	  <option value="19">19時</otion>
	  <option value="20">20時</otion>
	  <option value="21">21時</otion>
	  </select>
  から<select name="timeFin">
	  <option value="8">8時</otion>
	  <option value="9">9時</otion>
	  <option value="10">10時</otion>
	  <option value="11">11時</otion>
	  <option value="12">12時</otion>
	  <option value="13">13時</otion>
	  <option value="14">14時</otion>
	  <option value="15">15時</otion>
	  <option value="16">16時</otion>
	  <option value="17">17時</otion>
	  <option value="18">18時</otion>
	  <option value="19">19時</otion>
	  <option value="20">20時</otion>
	  <option value="21">21時</otion>
	  </select>
  </td>
</tr>
<tr>
	
	<td>
		<input type="hidden" name="created" value="<?php echo $created ?>">
		<input type="submit" value="登録する" name="insertButton" class="insertButton">
	</td>
</tr>
</table>


</form>

	<br><br>
	<a href="cal6.php?ym=<?php echo $_SESSION['ym'] ?>">戻る</a>
	


<script type="text/javascript">
//名前チェック
	window.onload = function() {
		document.getElementById("aForm").onsubmit = function(){
			if(aForm.name.value == ""){
				if(aForm.type.value == ""){
					alert( "内容を選択、詳細（名前）を入力してください" );
					return false;
				}else{
					alert( "詳細（名前）を入力してください" );
					//alert( aForm.type.value );
					return false;
				}
				
			}else{
				if(aForm.type.value == ""){
					alert( "内容を選択してください" );
					return false;
				}else{
					alert( "登録しました" );
					return true;
				}							
				
			}
		}
		
		document.getElementById("deleteForm").onsubmit = function(){
			if(deleteForm.deleteName.value == ""){
				alert( "削除したい予定を選択してください。" );
				return false;
			}else{
				//alert(deleteForm.deleteName.value);
				return confirm( "本当に削除してよろしいですか？" );
			}
			
						
		}
	}
</script>
<!--</div>-->
</body> 
</html>
