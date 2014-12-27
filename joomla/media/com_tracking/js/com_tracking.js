function displayResult() {
	var template = '<div class="row tracking_result"><div class="container"><div class="container"><div class="page-header"><h1 id="timeline">包裹号码</h1></div><ul class="timeline"><li><div class="timeline-badge danger"><em class="glyphicon glyphicon-plane"></em></div><div class="timeline-panel"><div class="timeline-heading"><h4 class="timeline-title">已出库</h4><p><small class="text-muted"><em class="glyphicon glyphicon-time"></em>2014年11月11日 13:11</small></p></div></div></li><li class="timeline-inverted"><div class="timeline-badge warning"><em class="glyphicon glyphicon-plane"></em></div><div class="timeline-panel"><div class="timeline-heading"><h4 class="timeline-title">已入库</h4><p><small class="text-muted"><em class="glyphicon glyphicon-time"></em>2014年11月11日 12:11</small></p></div></div></li></ul></div></div></div>';
	
	// double parse for chinese unicode
	var traceResult = JSON.parse(document.getElementById("tracRes").innerHTML);
	if(typeof(traceResult) === typeof('')) {
		traceResult = JSON.parse(traceResult);
	}

	var traceErrorCL = JSON.parse(document.getElementById("tracErrCL").innerHTML);
	if(typeof(traceErrorCL) === typeof('')) {
		traceErrorCL = JSON.parse(traceErrorCL);
	}
	

	document.getElementById("resDisp").innerHTML = resultDiv(traceResult, traceErrorCL);
	
}

function resultDiv(r, e) {
	var rDiv = '';
	if(r['error'] === '1000') {
		// local error
		rDiv = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">'+ 'Error 1000:' +'</span>'+ e['1000'] +'</div>';
	} else {
		// no error or no local error
		rDiv += '<div class="page-header"><h1 id="timeline">国内单号：'+ r['package_id'] +'</h1></div>';

		var TLElem = buildTLElem(r);
		// ul
		rDiv += '<ul class="timeline">';
		for (var i = TLElem.length - 1; i >= 0; i--) {
				// li
				rDiv += '<li'+ (i % 2 ? '' : ' class="timeline-inverted"') +'>';
					rDiv += '<div class="timeline-badge '+ TLElem[i]['icon']['alert-level'] +'"><em class="'+ TLElem[i]['icon']['icon'] +'"></em></div><div class="timeline-panel"><div class="timeline-heading"><h4 class="timeline-title">'+ TLElem[i]['label'] +'</h4><p><small class="text-muted"><em class="glyphicon glyphicon-time"></em>'+ TLElem[i]['time'] +'</small></p></div></div>';
				// close li
				rDiv += '</li>';
		};
		// close ul
		rDiv += '</ul>';

		// close div
		rDiv += '</div></div></div>';
	}
	return rDiv;
}

function buildTLElem(r) {
	var remoteData = JSON.parse(r['data']);
	var info = remoteData['data'];
	// code
		var c = {
			"DDJ_DATE":"等待缴费",
			"RKK_DATE":"入库",
			"CKK_DATE":"出库",
			"YSZ_DATE":"运送中",
			"DQG_DATE":"到达国内等待清关",
			"QGC_DATE":"清关成功",
			"GNP_DATE":"接入国内快递公司（国内派送中"
		};
	/*
	 * element example:
	 * [{"label":"daodaguonei","time":"time","icon":"icon"}]
	 */
	 var TLElem = [];
	 // local info
	 for(var key in c) {
	 	if(r[key]) {
	 		TLElem.push(buildElem(c[key], frGMT000(new Date(r[key])),buildIcon()));
	 	}
	 }

	 TLElem.sort(function(a,b) {
	 	return a['time'] - b['time'];
	 });

	 // remote info
	 for (var i = info.length - 1; i >= 0; i--) {
	 	TLElem.push(buildElem(info[i]['context'], cnGMT000(new Date(info[i]['time'])), buildIcon()));
	 };

	 return TLElem;
}

function frGMT000(d) {
	 // french GMT+000 : new Date(date.valueOf() + date.getTimezoneOffset() * 60000);
	return new Date(d.valueOf() + d.getTimezoneOffset() * 60000);
}

function cnGMT000(d) {
	 // beijing timeZoneOffset from GMT+0000 is -8 * 60000 * 60
	return new Date(d.valueOf() - 8 * 60000 * 60);
}

function buildIcon() {
	return {
		"alert-level" : "warning",
		"icon" : "glyphicon glyphicon-plane"
	};
}

function buildElem(label, time, icon) {
	return {"label" : label, "time" : time, "icon" : icon};
}