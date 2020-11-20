<?php
	$key='NI&u#+lFA0y@;$6Wj=5(~9';
	//服务器响应状态
    $Status_Code= array(
        'success' => array(
            'code' => '000',
            'message' => '成功'
        ),
        'failed' => array(
            'code' => '001',
            'message' => '失败'
        ),
        'params_error' => array(
            'code' => '002',
            'message' => '参数错误'
        ),
        'server_error' => array(
            'code' => '003',
            'message' => '服务器异常'
        ),
        'authenticating_api_failed' => array(
            'code' => '004',
            'message' => '验证API请求失败'
        ),
        'api_not_found' => array(
            'code' => '005',
            'message' => '请求的API不存在'
        )
    );
	$api_list= array(

        /**** 用户API 开始 ****/
        /**** 用户API 开始 ****/

        //MemberController
        //个人中心首页
        '9999' => array(
            'id' => '9999',
            'controller' => '\App\Controller\MemberController',
            'action' => 'member_info'
        ),
        //注册
        '10000' => array(
            'id' => '10000',
            'controller' => '\App\Controller\MemberController',
            'action' => 'score'
        ),
        //登录
        '10001' => array(
            'id' => '10001',
            'controller' => '\App\Controller\MemberController',
            'action' => 'login'
        ),
        //设置个人资料
        '10002' => array(
            'id' => '10002',
            'controller' => '\App\Controller\MemberController',
            'action' => 'rank_list'
        ),
        //获取短信验证码
        '10003' => array(
            'id' => '10003',
            'controller' => '\App\Controller\MemberController',
            'action' => 'get_yzm'
        ),
        //修改登录密码
        '10004' => array(
            'id' => '10004',
            'controller' => '\App\Controller\MemberController',
            'action' => 'updatepwd'
        ),

        //忘记登录密码 && 设置支付密码  && 修改支付密码
        '10005' => array(
            'id' => '10005',
            'controller' => '\App\Controller\MemberController',
            'action' => 'forgetpwd'
        ),
        //添加收藏
//        '10006' => array(
//            'id' => '10006',
//            'controller' => '\App\Controller\MemberController',
//            'action' => 'add_collection'
//        ),
//        //设置支付密码
//        '10007' => array(
//            'id' => '10007',
//            'controller' => '\App\Controller\MemberController',
//            'action' => 'set_pwd'
//        ),
        //展示收货地址
        '10008' => array(
            'id' => '10008',
            'controller' => '\App\Controller\MemberController',
            'action' => 'location'
        ),
        //添加收货地址  &&  修改收货地址
        '10009' => array(
            'id' => '10009',
            'controller' => '\App\Controller\MemberController',
            'action' => 'add_location'
        ),
        //删除收货地址
        '10010' => array(
            'id' => '10010',
            'controller' => '\App\Controller\MemberController',
            'action' => 'delete_address'
        ),
        //实名认证
        '10011' => array(
            'id' => '10011',
            'controller' => '\App\Controller\MemberController',
            'action' => 'attestation'
        ),
        //意见反馈
        '10012' => array(
            'id' => '10012',
            'controller' => '\App\Controller\MemberController',
            'action' => 'opinion'
        ),
        //关于我们
        '10013' => array(
            'id' => '10013',
            'controller' => '\App\Controller\MemberController',
            'action' => 'about'
        ),
        //设为默认地址
        '10014' => array(
            'id' => '10014',
            'controller' => '\App\Controller\MemberController',
            'action' => 'moren'
        ),
        //搜索商品
        '10015' => array(
            'id' => '10015',
            'controller' => '\App\Controller\MemberController',
            'action' => 'search'
        ),



        //获取首页信息
        '20000' => array(
            'id' => '20000',
            'controller' => '\App\Controller\IndexController',
            'action' => 'index_info'
        ),


        //WalletController
        //我的钱包
        '20001' => array(
            'id' => '20001',
            'controller' => '\App\Controller\WalletController',
            'action' => 'wallet'
        ),
        //银行卡展示
        '20002' => array(
            'id' => '20002',
            'controller' => '\App\Controller\WalletController',
            'action' => 'w_bank'
        ),
        //添加银行卡
        '20003' => array(
            'id' => '20003',
            'controller' => '\App\Controller\WalletController',
            'action' => 'add_bank'
        ),

        //解绑银行卡
        '20004' => array(
            'id' => '20004',
            'controller' => '\App\Controller\WalletController',
            'action' => 'del_bank'
        ),
//        //币种展示。item_type1金元宝 2银元宝 3金果 4余额 5金币 6银币
//        '20005' => array(
//            'id' => '20005',
//            'controller' => '\App\Controller\WalletController',
//            'action' => 'w_all_bz'
//        ),
        //余额提现
        //会员余额提现  && 区代余额提现
        '20006' => array(
            'id' => '20006',
            'controller' => '\App\Controller\WalletController',
            'action' => 'w_extract'
        ),
        //商家金果提现 && 商家元宝提现
        '20007' => array(
            'id' => '20007',
            'controller' => '\App\Controller\WalletController',
            'action' => 'merchant_tixian'
        ),
        //银楼
        '20010' => array(
            'id' => '20010',
            'controller' => '\App\Controller\WalletController',
            'action' => 'silver_store'
        ),
        //金果回购
        '20011' => array(
            'id' => '20011',
            'controller' => '\App\Controller\WalletController',
            'action' => 'fruit_recycle'
        ),
        //金果转好友 && 元宝转好友
        '20012' => array(
            'id' => '20012',
            'controller' => '\App\Controller\WalletController',
            'action' => 'transfer_to_friend'
        ),
        //金果明细 && 元宝明细 && 银币明细 &&  怡买工资(余额)明细
        '20013' => array(
            'id' => '20013',
            'controller' => '\App\Controller\WalletController',
            'action' => 'member_account'
        ),
        //聚宝盆
        '20014' => array(
            'id' => '20014',
            'controller' => '\App\Controller\WalletController',
            'action' => 'basin'
        ),
        //元宝寄存
        '20015' => array(
            'id' => '20015',
            'controller' => '\App\Controller\WalletController',
            'action' => 'basin_in'
        ),
        //元宝提取
        '20016' => array(
            'id' => '20016',
            'controller' => '\App\Controller\WalletController',
            'action' => 'basin_out'
        ),
        //聚宝盆明细
        '20017' => array(
            'id' => '20017',
            'controller' => '\App\Controller\WalletController',
            'action' => 'basin_account'
        ),
        //推荐的会员  && 推荐的商家
        '20018' => array(
            'id' => '20018',
            'controller' => '\App\Controller\WalletController',
            'action' => 'vip_list'
        ),
        //元宝充值
        '20019' => array(
            'id' => '20019',
            'controller' => '\App\Controller\WalletController',
            'action' => 'acer_buy'
        ),
        //我要升级
        '20020' => array(
            'id' => '20020',
            'controller' => '\App\Controller\WalletController',
            'action' => 'upgrade'
        ),






        //Merchant

        //申请店铺
        '30000' => array(
            'id' => '30000',
            'controller' => '\App\Controller\MerchantController',
            'action' => 'merchant_add'
        ),
        //设置店铺
        '30001' => array(
            'id' => '30001',
            'controller' => '\App\Controller\MerchantController',
            'action' => 'merchant_set'
        ),
        //展示商家店铺
        '30002' => array(
            'id' => '30002',
            'controller' => '\App\Controller\MerchantController',
            'action' => 'merchant'
        ),
        //关闭店铺   开启店铺
        '30003' => array(
            'id' => '30003',
            'controller' => '\App\Controller\MerchantController',
            'action' => 'merchant_close_open'
        ),
    );
?>