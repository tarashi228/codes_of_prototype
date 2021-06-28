$(function(){
	// $('#video_wrapper').videocontrol({
	// 	movieWidth: 320,
	// 	movieHeight: 352,
	//     chapterTarget : '#chapter', //ID名
	// 	chapterTimes: [0,10,20] //チャプターに追加した分だけ記述
	// });
	var video = $('video').get(0);

	$('.chap').on('click', function() {
		var s = $(this).find('span')[0].textContent.split(":");
		var sec = 3600 * parseInt(s[0]) + 60 * parseInt(s[1]) + parseInt(s[2]);
		video.currentTime = sec;
	})
});