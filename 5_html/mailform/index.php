<?php
//================================================================================
//メールフォーム	Ver.101
//製作・著作権所有：携帯サイトnet（Copyright(c) http://www.keitai-site.net/）
//================================================================================
/////////////////////////////////////////▼変数定義▼
$conf['a_id'] = "admin";								//管理用ＩＤ（必ず変更して下さい）
$conf['a_pw'] = "admin";								//管理用パスワード（必ず変更して下さい）
$conf['sitename'] = "あなたのサイト名";						//サイト名
$conf['pagename'] = "お問い合わせ";						//ページ名
$conf['metakey'] = "メールフォーム,フォームメール";				//metaキーワード
$conf['metadesc'] = "お問い合わせメールフォームです。";			//meta説明
$conf['dir'] = "/mailform/";							//設置するディレクトリ
$conf['fname1'] = "./log1.csv";							//お問い合わせデータ
$conf['css'] = "./mailform.css";							//スタイルシート
$conf['line_page'] = 15;								//お問い合わせデータの１ページ当りの表示件数
$conf['mailto'] = "xxx@yyy.zzz";							//管理者メールアドレス
$conf['mailon'] = "1";								//ユーザーへの自動返信メール機能を使用："1"、不使用："0"
$conf['attach'] = "1";								//ファイル添付機能を使用："1"、不使用："0"
$conf['maxsize'] = 100000;								//添付ファイルの制限サイズ
//ファイルサイズはphp.iniのupload_max_filesizeおよびpost_max_sizeに依存するので、これらのサイズを超えない値を指定してください。
$conf['maxdata'] = 300;								//保存できるお問い合わせ件数の上限件数

/////////////////////////////////////////▲変数定義終端▲
/////////////////////////////////////////▼初期処理▼
$fp1 = @fopen($conf['fname1'],"r");					//読み込み専用オープン
$aryfl1 = @file($conf['fname1']);
@fclose($fp1);
$line_total = count($aryfl1);						//ファイルの総行数取得
$page_total = floor($line_total/$conf['line_page']);			//総ページ数
if(fmod($line_total,$conf['line_page']) > 0){$page_total++;}		//総ページ数の余りがあれば＋１ページ
$from = @array_fill(0,$page_total,TRUE);					//ページ数分の配列生成
$page = @array_fill(0,$page_total,TRUE);					//ページ数分の配列生成
$admin = $_REQUEST['admin'];
$msg = @array_fill(0,20,NULL);						//メッセージ用の配列生成
/////////////////////////////////////////▲初期処理終端▲
/////////////////////////////////////////▼携帯判別▼
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if(eregi("DoCoMo",$user_agent)){$ua = "docomo";}
elseif(eregi("UP\.Browser",$user_agent)){$ua = "au";}
elseif(eregi("J-PHONE",$user_agent)){$ua = "softbank";}
elseif(eregi("Vodafone",$user_agent)){$ua = "softbank";}
elseif(eregi("SoftBank",$user_agent)){$ua = "softbank";}
elseif(eregi("J-EMULATOR",$user_agent)){$ua = "softbank";}
else{$ua = "pc";}
/////////////////////////////////////////▲携帯判別終端▲
/////////////////////////////////////////▼メインルーチン開始▼
switch($_REQUEST['act']){

case 'login':				//▼login
head1();
login();
foot1();
break;

case 'admin':				//▼admin
head1();
if($_REQUEST['a_id'] == $conf['a_id'] && $_REQUEST['a_pw'] == $conf['a_pw']){
admin();
}
else{$msg[1] = "認証は認められませんでした。";msg();login();}
foot1();
break;

case 'form_reg':			//▼form_reg
head1();
form_reg();
foot1();
break;

case 'form_del_a':			//▼form_del_a
head1();
admin();
form_del_a();
foot1();
break;

case 'delete':			//▼delete
delete();
head1();
admin();
msg();
foot1();
break;

case 'regist':			//▼regist
regist();
head1();
if($admin == 1){admin();}
msg();
foot1();
break;

default:				//▼default
head1();
form_reg();
foot1();
}
/////////////////////////////////////////▲メインルーチン終端▲
/////////////////////////////////////////▼関数▼

function login(){								//▼login
global $conf,$aryfl1,$ua,$admin,$msg;

print <<<END
<h2>{$conf['pagename']}</h2>
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="hidden" name="act" value="admin" />
<p>ユーザ名：<input type="text" name="a_id" size="10"><br />
パスワード：<input type="password" name="a_pw" size="10"></p>
<input type="submit" value="ログイン">
</form>
END;
}

function admin(){								//▼admin
global $conf,$aryfl1,$ua,$admin,$msg;

print <<<END
<h2>管理メニュー</h2>
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="hidden" name="act" value="form_del_a" />
<input type="hidden" name="admin" value="1" />
<input type="submit" value="管理者削除フォーム">
</form>
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="submit" value="ログアウト">
</form>
<br />
END;
}

function form_reg(){								//▼form_reg
global $conf,$aryfl1,$ua,$admin,$msg;

print <<<END
<h2>{$conf['pagename']}</h2>
END;
$vol = $conf['maxsize'];
if($vol < 1000000){$vol = round($vol/1000,1)."KB";}else{$vol = round($vol/1000000,1)."MB";}
print <<<END
<!--
-->
<form action="{$_SERVER[REQUEST_URI]}" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$conf['maxsize']}" />
<input type="hidden" name="act" value="regist" />
<ul>
<li>お名前（全角２５文字以内）<input type="text" name="name" size="35">必須</li>
<li>メール（半角８０文字以内）<input type="text" name="mail" size="35">必須</li>
<li>件名（全角３０文字以内）<input type="text" name="title" size="45">必須</li>
<li>お問い合わせ内容（全角２０００文字以内）<br /><textarea name="comment" cols="50" rows="5"></textarea>必須</li>
END;
if($conf['attach'] == 1){
print <<<END
<li>添付ファイル（半角８０文字以内）<input type="file" name="upfile" size="50"><br />添付可能なファイルサイズ：$vol
</li>
END;
}
print <<<END
</ul>
<input type="submit" value="送信"> 
</form>
<hr />
END;

/////▼広告リンク▼（改変禁止）
$ad = array(
"<li><a href=\"http://www.jhoken.com/\" class=\"ad\">自動車保険</a></li>",
"<li><a href=\"http://www.kaitori-db.com/\" class=\"ad\">車買取</a></li>",
"<li><a href=\"http://www.kaitori-db.com/\" class=\"ad\">車査定</a></li>",
"<li><a href=\"http://www.cardhikaku.net/\" class=\"ad\">クレジットカード</a></li>",
"<li><a href=\"http://www.fxnavi.net/\" class=\"ad\">ＦＸ</a></li>",
"<li><a href=\"http://www.cardhikaku.net/cash/\" class=\"ad\">キャッシング</a></li>"
);
$num = count($ad);
$today = date("d", mktime(0,0,0,date("m"),date("d"),date("Y")));			//今日
$today %= $num;
$ad_a = array_slice($ad,$today);
$ad_b = array_slice($ad,0,$today);
print "<ul class=\"ad\">";
foreach($ad_a as $data){print "$data";}
foreach($ad_b as $data){print "$data";}
print "</ul>";
/////▲広告リンク▲（改変禁止）
}

function mojifilter(&$str){							//▼mojifilter
//$str = ereg_replace("\r\n\|\r|\n","",$str);			//＜＜使用しない事＞＞
$str = ereg_replace("[\r\n\]","",$str);				//改行除去（文字間）
$str = trim($str);							//空白除去
$str = str_replace('\"','”',$str);				//ダブルクオート置換
$str = str_replace('\'','’',$str);				//シングルクオート置換
$str = str_replace(',','，',$str);					//カンマ置換
$str = mb_convert_kana($str,"KV","SJIS");				//半角カナ変換
if(get_magic_quotes_gpc()){$str = stripslashes($str);}		//￥除去
return $str;
}

function regist(){								//▼regist
global $conf,$aryfl1,$ua,$admin,$msg;

$in_check_ok = TRUE;
$reg_time = time();
$name = $_REQUEST['name'];
$mail = $_REQUEST['mail'];
$url = $_REQUEST['url'];
$title = $_REQUEST['title'];
$comment = $_REQUEST['comment'];
$upfile = $_FILES['upfile']['name'];
$vol = $_FILES['upfile']['size'];
$dllimit = $_REQUEST['dllimit'];
if($dllimit > 0 && is_numeric($dllimit)){$zan = $dllimit - $dlcount;}
else{$zan = "-"; $dllimit = "-";}
$dlkey = $_REQUEST['dlkey'];
//eregi("(\jpg|\jpeg|\png|\bmp|\gif)$",$_FILES['upfile']['name'],$ext);		//ユーザファイル名から拡張子抜出し
preg_match("/\.[^.]*$/i",$_FILES['upfile']['name'],$ext);		//ユーザファイル名から拡張子抜出し

$pass = $_REQUEST['pass'];
$dlcount = 0;
$host = $_SERVER['REMOTE_ADDR'];

mojifilter($title);
mojifilter($comment);

if($name == ""){$in_check_ok = FALSE; $msg[2] = "お名前が入力されていないようです。";}
else{if(strlen($name) > 50){$in_check_ok = FALSE; $msg[2] = "お名前が２５文字を超えています。";}}
if($name == ""){$in_check_ok = FALSE; $msg[3] = "メールアドレスが入力されていないようです。";}
else{if(strlen($mail) > 80){$in_check_ok = FALSE; $msg[3] = "メールアドレスが８０文字を超えています。";}}
if($title == ""){$in_check_ok = FALSE; $msg[5] = "件名が入力されていないようです。";}
else{if(strlen($title) > 60){$in_check_ok = FALSE; $msg[5] = "件名が３０文字を超えています。";}}

if($comment == ""){$in_check_ok = FALSE; $msg[6] = "お問い合わせ内容が入力されていないようです。";}
else{if(strlen($comment) > 4000){$in_check_ok = FALSE; $msg[6] = "お問い合わせ内容が２０００文字を超えています。";}}
if($conf['attach'] == 1){
if($_FILES['upfile']['size'] > $conf['maxsize']){$in_check_ok = FALSE; $msg[7] = "添付ファイルが大き過ぎます。";}
}

if($aryfl1 == ""){							//ファイルなしの場合新規作成
	$id1 = 1;
	$fp1 = @fopen($conf['fname1'],"a");	
	@chmod($conf['fname1'],0606);
	@fclose($fp1);
}
else{									//ファイルありの場合リロード対策処理
	foreach($aryfl1 as $value){list($id1,,,,$name_prev,,,,,,,,,,,,,$host_prev) = explode(",",mb_convert_encoding($value,"SJIS","ASCII,JIS,UTF-8,EUC-JP,SJIS"));}						//最終行を読み込み
	if(strcmp($name_prev,$name) == 0){				//管理者送信の場合チェックしない
		if($admin != 1){$in_check_ok = FALSE; $msg[1] = "すでに送信されています。";}else{$id1 += 1;}
	}
	else{$id1 += 1;}
}

if($in_check_ok){	//▼▼▼エラー無ければ更新処理▼▼▼
//▼件数制限処理▼
if($conf['maxdata'] <= count($aryfl1)){
$i = 0;
foreach($aryfl1 as $value){list(,,,,,,,,,$upfiledel,,,,,$thumbfiledel,,,) = explode(",",mb_convert_encoding($value,"SJIS","ASCII,JIS,UTF-8,EUC-JP,SJIS"));
	if($i == 0){
		unset($aryfl1[$i]);					//先頭データ行削除
		@unlink('uf/'.$upfiledel);				//アップロードファイル削除
		@unlink('uf/'.$thumbfiledel);			//サムネイルファイル削除
	}
	$fp1 = @fopen($conf['fname1'],"w");			//書き込みオープン
	@flock($fp1,LOCK_EX);
	foreach($aryfl1 as $line){@fwrite($fp1, $line);}
	@flock($fp1,LOCK_UN);
	@fclose($fp1);
	$i++;
}
}

//▼アップロード処理▼
if($conf['attach'] == 1){
if(is_file("{$_FILES['upfile']['tmp_name']}")){
$upfile = date("YmdHis",$reg_time).$ext[0];					//保存用ファイル名生成
@move_uploaded_file($_FILES['upfile']['tmp_name'],'uf/'.$upfile);		//テンポラリディレクトリからの移動処理
}
}

//▼データ更新処理▼
$adddata = array($id1,$id2,$id3,$reg_time,$name,$mail,$url,$title,$comment,$upfile,$dllimit,$dlkey,$pass,$vol,$thumbfile,$dlcount,$zan,$host);	//変数群から配列生成
$adddata = implode(",",$adddata);					//配列要素をカンマで連結
$adddata = mb_convert_encoding($adddata,"SJIS","ASCII,JIS,UTF-8,EUC-JP,SJIS");
$fp1 = @fopen($conf['fname1'],"a");				//追加書き込みオープン
@flock($fp1,LOCK_EX);
@fwrite($fp1,$adddata."\r\n");
@flock($fp1,LOCK_UN);
@fclose($fp1);
$msg[0] = "送信完了";
$msg[1] = "送信が完了しました。";

//▼メール通知処理（管理者宛）▼
$mail_str = "以下のお問い合わせがありました\n\n";
$mail_str .= "お名前：".$name."　様\n";
$mail_str .= "メールアドレス：".$mail."\n";
$mail_str .= "お問い合わせ件名：".$title."\n";
$mail_str .= "お問い合わせ内容：".$comment."\n";
$mail_str .= "送信時間：".date('Y/m/d (D) H:i')."\n";
$mail_str .= "ホスト：".$host."\n";
$mail_str .= "ユーザーエージェント：".$_SERVER['HTTP_USER_AGENT']."\n\n";
mb_language("Ja");
mb_internal_encoding("sjis");
@mb_send_mail($conf['mailto'],$title,$mail_str,'From:'.mb_encode_mimeheader("{$conf['sitename']}").$conf['mailto']);
//▼メール通知処理（ユーザー宛）▼
//if(!$admin && $conf['mailon'] == "1"){
if($conf['mailon'] == "1"){
$subject = "お問い合わせ確認メール";
$mail_str = "お問い合わせありがとうございました。\n";
$mail_str .= "以下の内容にて承りました。\n\n";
$mail_str .= "お名前：".$name."　様\n";
$mail_str .= "メールアドレス：".$mail."\n";
$mail_str .= "お問い合わせ件名：".$title."\n";
$mail_str .= "お問い合わせ内容：".$comment."\n";
$mail_str .= "送信時間：".date('Y/m/d (D) H:i')."\n\n";
$mail_str .= "----------------------------------------------------\n";
$mail_str .= "{$conf['sitename']}\n";
$mail_str .= "----------------------------------------------------\n";
mb_language("Ja");
mb_internal_encoding("sjis");
@mb_send_mail($mail,$subject,$mail_str,'From:'.mb_encode_mimeheader("{$conf['sitename']}").$conf['mailto']);
}

}
else{$msg[0] = "送信できませんでした。";$msg[16] = "再入力はブラウザの戻るボタンを押してください。";}

}

function delete(){								//▼delete
global $conf,$aryfl1,$ua,$admin,$msg;

if(is_numeric($_REQUEST['del_id'])){
$del_id = $_REQUEST['del_id'];
$i = 0;
foreach($aryfl1 as $value){list($id1,$id2,$id3,$reg_time,$name,$mail,$url,$title,$comment,$upfile,$dllimit,$dlkey,$pass,$vol,$thumbfile,$dlcount,$zan,$host) = explode(",",mb_convert_encoding($value,"SJIS","ASCII,JIS,UTF-8,EUC-JP,SJIS"));
if(strcmp($id1,$del_id) == 0){
	unset($aryfl1[$i]);				//指定データ行削除
	@unlink('uf/'.$upfile);			//アップロードファイル削除
	@unlink('uf/'.$thumbfile);			//サムネイルファイル削除
}
$i++;
}

$fp1 = @fopen($conf['fname1'],"w");				//書き込みオープン
@flock($fp1,LOCK_EX);
foreach($aryfl1 as $line){@fwrite($fp1, $line);}
@flock($fp1,LOCK_UN);
@fclose($fp1);

$msg[0] = "削除完了";
$msg[1] = "データ削除が完了しました。";
}
else{
$msg[0] = "削除未完了";
$msg[1] = "入力値が数値ではありません。";
}
}

function form_del_a(){							//▼form_del_a
global $conf,$aryfl1,$ua,$admin,$msg;
global $line_total,$page_total,$page,$from;

print <<<END
<h2>{$conf['pagename']}</h2>
<p>削除したい登録IDを指定してください。</p>
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="hidden" name="act" value="delete" />
<input type="hidden" name="admin" value="1" />
<p>削除したい登録ID：<input type="text" name="del_id" size="20">
<input type="submit" value="削除する"></p>
</form>
END;

if($_REQUEST['from']){$line_from = $_REQUEST['from'];}
else{$line_from = $line_total;}
$line_to = $line_from - $conf['line_page'];
if($line_from < $conf['line_page']){$line_to = 0;}
print <<<END
<br />新着順：全 $line_total 件中 $line_from - $line_to 件目<br /><br />
<hr />
END;

for($i=$line_from;$i>$line_to;$i--){
list($id1,$id2,$id3,$reg_time,$name,$mail,$url,$title,$comment,$upfile,$dllimit,$dlkey,$pass,$vol,$thumbfile,$dlcount,$zan,$host) = explode(",",mb_convert_encoding($aryfl1[$i-1],"SJIS","ASCII,JIS,UTF-8,EUC-JP,SJIS"));
$reg_time = date("Y/m/d H:i:s",$reg_time);
$path = $conf['dir'].'uf/';
print <<<END
登録ID：$id1<br />
お名前：$name メール：$mail 件名：$title<br />
お問い合わせ内容：$comment<br />
添付ファイル：<a href="$path$upfile">$upfile</a><br />日時：$reg_time<br />
ホスト： $host
<hr />
END;
}

/////▼改ページ処理▼
echo "ページ：";
$line_prev = $line_from + $conf['line_page'];			//ページ前へ
if($line_prev <= $line_total){
print <<<END
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="hidden" name="act" value="form_del_a" />
<input type="hidden" name="from" value="$line_prev" />
<input type="hidden" name="admin" value="1" />
<input type="submit" value="前ページ $line_prev 件目"></p>
</form>
END;
}

$line_next = $line_from - $conf['line_page'];			//ページ次へ
if($line_next > 0){
print <<<END
<form action="{$_SERVER[REQUEST_URI]}" method="post">
<input type="hidden" name="act" value="form_del_a" />
<input type="hidden" name="from" value="$line_next" />
<input type="hidden" name="admin" value="1" />
<input type="submit" value="次ページ $line_next 件目"></p>
</form>
END;
}
/////▲改ページ処理▲

print <<<END
<hr />
END;
}

function head1(){								//▼head1
global $conf,$aryfl1,$ua,$admin,$msg;

/////////////////////////////////////////▼携帯判別▼
$DOCTYPE = array(
"pc"=>"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">",
"docomo"=>"<!DOCTYPE html PUBLIC \"-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.1) 1.0//EN\" \"i-xhtml_4ja_10.dtd\">",
"au"=>"<!DOCTYPE html PUBLIC \"-//OPENWAVE//DTD XHTML 1.0//EN\" \"http://www.openwave.com/DTD/xhtml-basic.dtd\">",
"softbank"=>"<!DOCTYPE html PUBLIC \"-//J-PHONE//DTD XHTML Basic 1.0 Plus//EN\" \"xhtml-Basic10-Plus.dtd\">"
);
/////////////////////////////////////////▲携帯判別終端▲

$currenturl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$title = $conf['sitename']." - ".$conf['pagename'];		//titleのデフォルト設定

print <<<END
$DOCTYPE[$ua]
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<title>$title</title>
<meta http-equiv="content-type" content="text/html; charset=Shift_JIS" />
<meta http-equiv="content-style-type" content="text/css" />
<meta name="keywords" content="{$conf['metakey']}" />
<meta name="description" content="{$conf['metadesc']}" />
<link rel="start" href="http://{$_SERVER[HTTP_HOST]}/" title="{$conf['sitename']}" />
<link rel="stylesheet" href="{$conf['css']}" type="text/css" />
</head>
<!--
-->

<body>
<!--▼layer1▼--><div id="layer1">
<!--▼header▼--><div id="header">
<a id="top" class="aname"></a>
<h1>{$conf['pagename']}</h1>
<div id="sitename"><a href="/" title="{$conf['sitename']}">{$conf['sitename']}</a></div>
<!--▲header▲--></div>
<!--▼layer2▼--><div id="layer2">
<!--▼contents▼--><div id="contents">
END;

}

function foot1(){								//▼foot1
global $conf,$aryfl1,$ua,$admin,$msg;

$d = htmlentities(urlencode('http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]));
$currenturl = 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
if($conf['pagename'] == ""){$top = $conf['sitename'];}
else{$top = $conf['pagename'];}

print <<<END
<!--▲contents▲--></div>

<!--▼navi1▼--><div id="navi1">
<div class="subj">ＭＥＮＵ</div>
<div class="link"><a href="/" title="{$conf['sitename']}">{$conf['sitename']}</a></div>
<div class="link"><a href="{$conf['dir']}">お問い合わせ</a></div>
<div class="link"><a href="{$conf['dir']}?act=login">管理</a></div>
<div class="com">
</div>
<!--▲navi1▲--></div>
<!--▲layer2▲--></div>

<!--▼navi2▼--><div id="navi2">
<!--▲navi2▲--></div>

<!--▼footer▼--><div id="footer">
<div class="pagetop"><a href="$currenturl" title="$top ページトップへ">▲ページトップへ</a></div>
<div class="pagetop">Powered by <a href="http://www.keitai-site.net/php/mailform_php/" title="メールフォーム">メールフォーム</a></div>
<!--▲footer▲--></div>
<!--▲layer1▲--></div>
</body>
</html>
END;
}

function msg(){								//▼msg
global $conf,$aryfl1,$ua,$admin,$msg;

$num = count($msg);
print <<<END
<h2>$msg[0]</h2>
END;
for($i=1;$i<=$num;$i++){
if($msg[$i] != NULL){print("<p>$msg[$i]</p>");}
}
print <<<END
<br />
<p><a href="{$conf['dir']}">{$conf['pagename']}</a>へ戻る</p>
END;
}
/////////////////////////////////////////▲関数終端▲
?>
