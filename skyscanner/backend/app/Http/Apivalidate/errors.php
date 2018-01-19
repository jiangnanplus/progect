<?php
class errors
{
	// 返回值
	const E_SUCCESS            = array('code' => 0, 'msg' => '成功');

	const E_ERROR              = array('code' => 10000, 'msg' => '失败');
	const E_SYSTEM_EXCEPT      = array('code' => 10001, 'msg' => '系统异常');
	const E_DATABASE_EXCEPT    = array('code' => 10002, 'msg' => '数据操作异常'); 
	const E_SMS_ERROR      = array('code' => 10003, 'msg' => '短信模板使用场景不正确');
	const E_WECHAT_ERROR      = array('code' => 10004, 'msg' => '微信模板使用场景不正确');
	const E_TIME_OUT_ERROR      = array('code' => 10005, 'msg' => '网络超时');

	const E_PARAMS_ABSENT      = array('code' => 20000, 'msg' => '缺少系统级参数');
	const E_PARAMS_ERROR       = array('code' => 20001, 'msg' => '参数解析错误');
	const E_SIGN_ERROR         = array('code' => 20002, 'msg' => '签名错误');
	const E_TOKEN_ERROR        = array('code' => 20003, 'msg' => 'Token错误');
	const E_METHOD_ERROR       = array('code' => 20004, 'msg' => 'Method错误');

	const E_ABSENT_METHOD      = array('code' => 30000, 'msg' => '缺少METHOD');
	const E_ABSENT_V      = array('code' => 30001, 'msg' => '缺少版本号');
	const E_ABSENT_SIGN      = array('code' => 30002, 'msg' => '缺少SIGN');
	const E_ABSENT_TOKEN      = array('code' => 30003, 'msg' => '缺少TOKEN');

	const E_ABSENT_USERNAME      = array('code' => 40000, 'msg' => '缺少用户名');
	const E_ABSENT_PASSWORD      = array('code' => 40001, 'msg' => '缺少密码');
	const E_ABSENT_OLD_PASSWORD      = array('code' => 40002, 'msg' => '缺少旧密码');
	const E_ABSENT_NEW_PASSWORD      = array('code' => 40003, 'msg' => '缺少新密码');
	const E_ABSENT_MOBILE_NUMBER      = array('code' => 40004, 'msg' => '缺少收件人联系电话');
	const E_ABSENT_SITE_CODE      = array('code' => 40005, 'msg' => '缺少站点');
	const E_ABSENT_HEAD_PIC      = array('code' => 40006, 'msg' => '缺少头像');
	const E_ABSENT_IMG           = array('code' => 40007, 'msg' => '缺少图片');
	const E_PROVINCE_NOT_EXIST     = array('code' => 40008, 'msg' => '缺少省份');
	const E_PASSWORD_NOT_EXIST   = array('code' => 40009, 'msg' => '缺少旧密码');
	const E_CONSIGNEE__NOT_EXIST     = array('code' => 40010, 'msg' => '缺少收件人');
	const E_ADDRESS__NOT_EXIST     = array('code' => 40011, 'msg' => '缺少收件人');
	const E_CONTENT_NOT_EXIST     = array('code' => 40012, 'msg' => '缺少寄托物内容');
	const E_INVENTORY_NOT_EXIST     = array('code' => 40013, 'msg' => '运单未入库');
	const E_WAYBILL_SHELF_EXIST     = array('code' => 40014, 'msg' => '该货架号已存在');
	const E_SETTLEMENT_NOT_EXIST     = array('code' => 40015, 'msg' => '缺少结算方式');
	const E_VERSION_NOT_EXIST     = array('code' => 40016, 'msg' => '缺少数据版本号');
	const E_STATUS_NOT_EXIST     = array('code' => 40017, 'msg' => '缺少状态');
	const E_WAYBILL_NO_NOT_EXIST     = array('code' => 40018, 'msg' => '缺少运单号');
	const E_COUNT_NOT_EXIST     = array('code' => 40019, 'msg' => '缺少数量');
	const E_UID_PHONE_NOT_EXIST = array('code' => 40020, 'msg' => '缺少UID或者手机号');
	const E_TOTAL_CHARGE_NOT_EXIST     = array('code' => 40021, 'msg' => '缺少总费用');
	const E_ORDER_NO_NOT_EXIST  = array('code' => 40022, 'msg' => '缺少订单号');
	const E_PASSWORD_NOT_SAME_EXCEPT = array('code' => 40023, 'msg' => '旧密码不正确');
	const E_NEW_PASSWORD_NOT_EXIST   = array('code' => 40024, 'msg' => '缺少新密码');
	const E_WXCHAT_NOT_EXIST = array('code' => 40025, 'msg' => '请求微信扫码支付失败');
	const E_USERNAME_ROOM_NUMBER_NOT_EXIST = array('code' => 40026, 'msg' => '没有该客户信息');
	const E_MISS_NOT_EXIST = array('code' => 40027, 'msg' => '请先执行点货或序列入位操作');
	const E_ORDER_RESULT_NO_NOT_EXIST = array('code' => 40028, 'msg' => '没有订单信息');
	const E_NO_THIS_PEOPLE = array('code' => 40029, 'msg' => '查无此人');
	const E_GROUP_NO_NOT_EXIST = array('code' => 40030, 'msg' => '缺少货组号');
	const E_POSITION_NO_NOT_EXIST = array('code' => 40031, 'msg' => '缺少货架号');
	const E_SHELF_NOT_EXIST = array('code' => 40032, 'msg' => '该货架不存在');
	const E_ABSENT_IMEI_NOT_EXIST = array('code' => 40033, 'msg' => '设备编号不存在');
	const E_DEVICE_NO_REG = array('code' => 40034, 'msg' => '该设备未授权登录');
	const E_MONTHLY_CHARGE_NOT_EXIST = array('code' => 40035, 'msg' => '请在后台月结客户揽费管理设置价格体系');
	const E_SHIPMENT_CHARGE_NOT_EXIST = array('code' => 40036, 'msg' => '请在后台揽费管理设置价格体系');
	const E_WAYBILL_IS_LOCKED = array('code' => 40037, 'msg' => '该运单已锁定或丢失,不允许操作');
	const E_DEVICE_SITE_CODE_ERROR = array('code' => 40038, 'msg' => '该设备与站点不匹配');
	const E_WAYBILL_IS_LOST = array('code' => 40039, 'msg' => '该运单已丢失,不允许操作');
	const E_VENDOR_CUSTOMER_NOT_EXIST = array('code' => 40040, 'msg' => '请在后台快递月结客户揽费设置通用价格');
	const E_SHIPMENT_CODE_ERROR = array('code' => 40041, 'msg' => '无效的发件码');
	const E_QUANTITY_ERROR = array('code' => 40042, 'msg' => '数量超出1000');
	const E_SETTLEMENT_MODE_NOT_EXIST = array('code' => 40044, 'msg' => '缺少支付方式');
	const E_IMAGES_CODE_NOT_SAME_EXCEPT = array('code' => 40045, 'msg' => '验证码不正确');
	const E_ABSENT_IMAGES_CODE      = array('code' => 40000, 'msg' => '缺少验证码');
	


	const E_USERNAME_NOT_EXIST      = array('code' => 50000, 'msg' => 'TOKEN失效');
	const E_USERNAME_EXIST      = array('code' => 50001, 'msg' => '用户名已被占用');
	const E_PASSWORD_ERROR      = array('code' => 50002, 'msg' => '访问码错误');
	const E_MOBILE_NUMBER_NOT_EXIST      = array('code' => 50003, 'msg' => '手机号码不存在');
	const E_MOBILE_NUMBER_EXIST     = array('code' => 50004, 'msg' => '手机号码已被占用');
	const E_USERNAME_IS_DISABLE     = array('code' => 50005, 'msg' => '该账号已停用');
	const E_USERNAME_IS_DELETE     = array('code' => 50006, 'msg' => '该账号不存在');

	const E_VENDER_EXIST     = array('code' => 60001, 'msg' => '快递公司不存在');
	const E_WAYBILL_EXIST     = array('code' => 60001, 'msg' => '该运单不存在');
	const E_COUNT_ERROR     = array('code' => 60002, 'msg' => '数量错误');
	const E_TIME_ERROR     = array('code' => 60003, 'msg' => '缺少时间');
	const E_ELECTRONIC_NOT_EXIST     = array('code' => 60004, 'msg' => '揽件信息不存在');
	const E_WAYBILL_IS_DELETED     = array('code' => 60005, 'msg' => '该运单已删除,不允许操作');
	const E_ELECTRONIC_NO_PAY     = array('code' => 60006, 'msg' => '该运单未支付');
	const E_REMAIN_NUMBER_ERROR     = array('code' => 60007, 'msg' => '快递公司剩余运单号不足');
}