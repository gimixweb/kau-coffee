// -----------------------------------------------
// コンテンツボックス（open close）
// 下記のソースを<head></head>の間に挿入
// metaタグのリンクでは動作しない
// -----------------------------------------------

// 初期クローズ

function contentBox(inputData) {
	var objID=document.getElementById( "layer_" + inputData );
	var buttonID=document.getElementById( "button_" + inputData );
	
	if (objID.className == 'close') {
		objID.style.display='block';
		objID.className='open';
		buttonID.src='images/button_close.png'; // イメージ
	} else {
		objID.style.display='none';
		objID.className='close';
		buttonID.src='images/button_open.png'; // イメージ
	}
}

