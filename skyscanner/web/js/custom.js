/*
2015年8月19日 威智网络 前端
*/
/**
* 图片头数据加载就绪事件 - 更快获取图片尺寸
* @param{String}图片路径
* @param{Function}尺寸就绪
* @param{Function}加载完毕 (可选)
* @param{Function}加载错误 (可选)
* @example imgReady('url', function () {
alert('size ready: width=' + this.width + '; height=' + this.height);
});
*/
var imgReady = (function() {
    var list = [],
    intervalId = null,
    // 用来执行队列
    tick = function() {
        var i = 0;
        for (; i < list.length; i++) list[i].end ? list.splice(i--, 1) : list[i]();

        ! list.length && stop();
    },
    // 停止所有定时器队列
    stop = function() {
        clearInterval(intervalId);
        intervalId = null;
    };
    return function(url, ready, load, error) {
        var onready, width, height, newWidth, newHeight, img = new Image();

        img.src = url;
        if (img.complete) { // 如果图片被缓存，则直接返回缓存数据
            ready.call(img);
            load && load.call(img);
            return;
        };
        width = img.width;
        height = img.height;
        // 加载错误后的事件
        img.onerror = function() {
            error && error.call(img);
            onready.end = true;
            img = img.onload = img.onerror = null;
        };
        // 图片尺寸就绪
        onready = function() {
            newWidth = img.width;
            newHeight = img.height;
            console.log(newWidth + ',' + newHeight);
			if (newWidth !== width || newHeight !== height || newWidth * newHeight > 1024) { // 如果图片已经在其他地方加载可使用面积检测
                ready.call(img);
                onready.end = true;
            };
        };
        onready();

        // 完全加载完毕的事件
        img.onload = function() {
            // onload在定时器时间差范围内可能比onready快
            // 这里进行检查并保证onready优先执行
            ! onready.end && onready();

            load && load.call(img);

            // IE gif动画会循环执行onload，置空onload即可
            img = img.onload = img.onerror = null;
        };

        // 加入队列中定期执行
        if (!onready.end) {
            list.push(onready);
            // 无论何时只允许出现一个定时器，减少浏览器性能损耗
            if (intervalId === null) intervalId = setInterval(tick, 40);
        };
    };
})();




/*
函数定义
*/


//新增了联系信息弹窗函数
function tanchuliuyan(){
	var liuyan = layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		fix: false,
		offset: '20px',
		area: ['500px', 'auto'],
		shade: [0.5, '#000'],
		skin: 'layui-layer-custom',
		shadeClose: false,
		scrollbar: true,
		shift:2,
		content: $('#tc-liuyan'),
		zIndex:9999
	});
	$('.tc-closebtn').on('click',function(){
		layer.close(liuyan);
	})
}

//输入框最大数值函数
function MaxScore(e){
	var max_score = parseInt(e.attr('maxscore'));
	var cur_score = e.val();
	if(!isNaN(cur_score)){
		if(cur_score > max_score){
			e.val(max_score);
		} else {}
	} else {
		e.val('');
		}
}


/*
默认执行
*/



$(function(){

	//窗口自适应 20160826 对 rem 应用做了调整
    var adjust = function(){
		if($(window).innerWidth() < 767){
			var fs = $(window).innerWidth()*100/320;
		}else{
			var fs = 320*100/320;
		}
		$('html').css('font-size',fs)
	};
	adjust();
	window.onresize = adjust;
	if ((navigator.userAgent.indexOf('MSIE') >= 0)&& (navigator.userAgent.indexOf('Opera') < 0)){
		window.attachEvent("onresize",adjust);
	};
	
	//导航下拉js
	/*$(".nav_shu").slide({ 
		type:"menu", // 效果类型，针对菜单/导航而引入的参数（默认slide）
		titCell:".nLi", //鼠标触发对象
		targetCell:".sub", //titCell里面包含的要显示/消失的对象
		effect:"slideDown", //targetCell下拉效果
		delayTime:100 , //效果时间
		defaultPlay:false,
		triggerTime:0, //鼠标延迟触发时间（默认150）
		returnDefault:true //鼠标移走后返回默认状态，例如默认频道是“预告片”，鼠标移走后会返回“预告片”（默认false）
	});*/
	
	
	//导航触发背景透明度变化效果
	$(".head").hover(function(){
		if($(this).hasClass("head_fixed")){
			$(this).addClass("head_fixed_on");
		}
	},function(){
		if($(this).hasClass("head_fixed")){
			$(this).removeClass("head_fixed_on");
		}
	});

	//关闭广告js
	$('.ad .closead').click(function(){
		$(this).parent().slideUp();
	});
})


// 浮动搜索
$(function(){
	
    // 窄屏导航
	/*$('.nav-btn').click(function(){
		var $this = $(this);
		var navmenu = $('.nav-menu');
		if($this.hasClass('closing')){
			$this.removeClass('closing');
			navmenu.removeClass('show');
		}else{
			$this.addClass('closing');
			navmenu.addClass('show');
		}
	})*/

    // 滚动监测
    if (window.screen.width > 700) {
        window.onscroll = function() {
            var scroll_top_now = $(window).scrollTop(); //当前滚动条距离
            var scroll_top_start = 0;                 //滚动条触发事件的距离
            //二级菜单浮动
            if (scroll_top_now > scroll_top_start) {
                $(".head").addClass("head_fixed");
				$(".submenu").addClass("submenu_fixed");
				$(".item_top").slideDown(); 
            } else {
                $(".head").removeClass("head_fixed");
				$(".submenu").removeClass("submenu_fixed");
				$(".item_top").slideUp();
            };			
			
        };
    }
	

	// 回到顶部
    $(".item_top").click(function() {
        $(".mainbody").animate({scrollTop: 0}, 600);
    });
	
	// 平滑滚动到锚链接
    $("a.maolink").click(function() {
		if($(window).innerWidth() < 767){
			var scroll = $($(this).attr("maoto")).offset().top - 50;
		}else{
			var scroll = $($(this).attr("maoto")).offset().top - 90;
		}
        $(".mainbody").animate({scrollTop: scroll}, 500);
        return false;
    });
	
	//右侧漂浮触发
	$('.item_open').hover(function(){
		$(this).toggleClass('on');
	},function(){
		$(this).toggleClass('on');
	})

})














