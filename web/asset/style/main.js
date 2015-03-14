/**
 * Created by loveyu on 2015/3/14.
 */
var MainObj = {
	loginStatus: function ($) {
		$.ajax({
			url: URL_MAP.api + "Member/loginInfo",
			dataType: "json",
			xhrFields: {
				withCredentials: true
			},
			success: function (result) {
				if (result.status) {
					//已登陆
					$("#Header .login_status").html("<a href=\"" + URL_MAP.my + "\" title=\"用户中心\"><img src=\""
					+ result.data.avatar + "\" alt=\"avatar\" />&nbsp;" + result.data.name + "</a>" + "&nbsp;|&nbsp;" +
					"<a href=\"" + URL_MAP.my + "Home/logout\" title=\"退出登录\">退出</a>");
				} else {
					//未登陆
				}
			}
		});
	}
};
jQuery(function ($) {
	MainObj.loginStatus($);
});