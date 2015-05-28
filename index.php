<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="Content-Style-Type"content="text/css"> 
	<meta http-equiv="Content-Script-Type" content="text/javascript" /> 
	
	<title>Moritomo Top</title>
	<link rel="stylesheet" href="cal6.css" type="text/css">
	
	<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
</head>
<body align="center">
	<br><br><br><br>
	<h2>
		<div id="enter" >
			<a href="index.php" onclick="init();return false;">中西研予定ページ</a>
		</div>
	</h2>
	
<!--	<?php
	
	$timeStampStamp = time();  //ローカル時刻にGMTとの時差を引く
	echo date("Y/m/d H:i:s (D)", $timeStampStamp);
	$timeStampStamp = $timeStampStamp + 9*3600;  //ついでに日本時間を表示
	echo "<BR>";
	echo date("Y/m/d H:i:s (D)", $timeStampStamp);
	
	?>
-->

	
<script type="text/javascript">
	function init(){
		psswd = prompt("パスワードを入力", "*******");
		if(psswd == "dragons"){
			alert ("そう、ドラゴンズは最強。");
			document.getElementById('enter').innerHTML = '<a href="cal6.php" size="10px">入口</a>';
		}else{
			alert ("パスワードが違います。");
		}		
	}
</script>
	
</body>
</html>