// ------------------------------------------------------------------------------
// javascript tool
// ------------------------------------------------------------------------------

// ------------------------------------------------------------------------------
// 常にトップに表示（フレーム対策）
// ------------------------------------------------------------------------------
function topLocation() {
	if(self != top) {
		 top.location = self.location;
	}
}

// ------------------------------------------------------------------------------
// 一定期間New/Upマークを表示する
// 書式：newAndUpIcon(2006, 10, 01, new)
// ------------------------------------------------------------------------------
function newAndUpIcon(y, m, d, c) {
  delDay = 14; // 何日後に削除するか
  oldDay = new Date(y + "/" + m + "/" +d);
  newDay = new Date();
  d =(newDay - oldDay) / (1000 * 24 * 3600);
  if(d <= delDay) {
    // Newマーク
    if(c == "new") document.write("<img src='image/icon_new.gif' border='0'>");
    // Upマーク
    if(c == "up") document.write("<img src='image/icon_up.gif' border='0'>");
  }
}

// ------------------------------------------------------------------------------
// 著作権の年の自動表示
// ------------------------------------------------------------------------------
function copyrightYear() {
	myDate = new Date();
	myYear = myDate.getFullYear();
	document.write("- " + myYear);
}

// ------------------------------------------------------------------------------
// Last Updateの表示 
// ------------------------------------------------------------------------------
function lastUpdate (){
	var brw_v = navigator.appVersion.charAt(0);
	var brw_n = navigator.appName.charAt(0);
	last_update = new Date(document.lastModified);
	var d_Year = last_update.getYear();
	var d_Mon = last_update.getMonth()+1;
	var d_Day = last_update.getDate();
	var d_Hour = last_update.getHours();
	var d_Min = last_update.getMinutes();
	
	if(100 <= d_Year && d_Year <= 2000){
		d_Year += 1900;
	}
	if(d_Hour >= 24){
		d_Day++;
	}
	
	document.write(" Last Update:"+d_Year+"/"+d_Mon+"/"+d_Day);
}

// ------------------------------------------------------------------------------
// ログ収集 
// ------------------------------------------------------------------------------
function accessLog() {
	document.write("<img src='http://www.gimix-web.com/acc/acclog.cgi?");
	document.write("referrer="+document.referrer+"&");
	document.write("width="+screen.width+"&");
	document.write("height="+screen.height+"&");
	document.write("color="+screen.colorDepth+"'style='display: none;'>");
}

// ------------------------------------------------------------------------------
// 問い合わせ入力フォーム内容チェック
// ------------------------------------------------------------------------------
function formCheck(){
    if(document.form.name.value==""){
        alert("お名前を入力してください。");
        return false;
    }
    
    if(document.form.email.value==""){
        alert("メールアドレスを入力してください。");
        return false;
    }
    
    if(document.form.email.value.indexOf("@")<=0){
        alert("メールアドレスが正しくありません。");
        return false;
    }
    
		if(document.form.comment.value==""){
        alert("お問い合わせ内容を入力してください。");
        return false;
    }

    if (confirm ("以下の内容で送信します。\n\nお名前：\n" + document.form.name.value + "\n\nメールアドレス：\n" + document.form.email.value + "\n\nお問い合わせ内容：\n" + document.form.comment.value + "\n\nよろしいですか。")){
        return true;
    }else{
        return false;
    }
}

