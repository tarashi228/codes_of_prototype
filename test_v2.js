var video = $('video').get(0);

function clicked(n) {
	var s = $('#chap'+n).find('span')[0].textContent.split(":");
	var sec = 3600 * parseInt(s[0]) + 60 * parseInt(s[1]) + parseInt(s[2]);
	video.currentTime = sec;
	var inp = $('input[name=\"Chapter' + n + '\"]');
	var val = parseInt(inp.val())+1;
	console.log(typeof(val));
	inp.val(val.toString());

	document.data_form.submit();
}

data.forEach(element => {
	$('#datas').append('<input type=\"hidden\" name=\"' + element[0] + '\" value=\"' + element[1] + '\">');
});
