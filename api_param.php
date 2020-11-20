<?php
$api_list=array(

        /**** 用户API 开始 ****/
       //客服获取手机号
          '9996' => array(
            'id' => '9996',
            'controller' => '\App\Controller\MemberController',
            'action' => 'update_mobile'
        ),
        //客服获取手机号
          '9997' => array(
            'id' => '9997',
            'controller' => '\App\Controller\MemberController',
            'action' => 'client_mobile'
        ),
          //客服登录
          '9998' => array(
            'id' => '9998',
            'controller' => '\App\Controller\MemberController',
            'action' => 'client_login'
        ),
        '9999' => array(
            'id' => '9999',
            'controller' => '\App\Controller\MemberController',
            'action' => 'check_reg'
        ),
        //注册
        '10000' => array(
            'id' => '10000',
            'controller' => '\App\Controller\MemberController',
            'action' => 'login'
        ),
        '10001' => array(
            'id' => '10001',
            'controller' => '\App\Controller\MemberController',
            'action' => 'score'
        ),
        '10002' => array(
            'id' => '10002',
            'controller' => '\App\Controller\MemberController',
            'action' => 'rank_list'
        ),
        '10003' => array(
            'id' => '10003',
            'controller' => '\App\Controller\MemberController',
            'action' => 'info'
        ),
        '10004' => array(
            'id' => '10004',
            'controller' => '\App\Controller\MemberController',
            'action' => 'address'
        ),
        '10005' => array(
            'id' => '10005',
            'controller' => '\App\Controller\MemberController',
            'action' => 'delete_address'
        ),
        '10006' => array(
            'id' => '10006',
            'controller' => '\App\Controller\MemberController',
            'action' => 'add_address'
        ),
        '10007' => array(
            'id' => '10007',
            'controller' => '\App\Controller\MemberController',
            'action' => 'infos'
        ),
        '10008' => array(
            'id' => '10008',
            'controller' => '\App\Controller\MemberController',
            'action' => 'default_address'
        ),
        '10009' => array(
            'id' => '10009',
            'controller' => '\App\Controller\IndexController',
            'action' => 'index'
        ),
        '10010' => array(
            'id' => '10010',
            'controller' => '\App\Controller\IndexController',
            'action' => 'tourism'
        ),
        '10011' => array(
            'id' => '10011',
            'controller' => '\App\Controller\IndexController',
            'action' => 'member_data'
        ),
        '10012' => array(
            'id' => '10012',
            'controller' => '\App\Controller\IndexController',
            'action' => 'set_info'
        ),
        '10013' => array(
            'id' => '10013',
            'controller' => '\App\Controller\IndexController',
            'action' => 'questions'
        ),
        '10014' => array(
            'id' => '10014',
            'controller' => '\App\Controller\IndexController',
            'action' => 'shopping'
        ),
        '10015' => array(
            'id' => '10015',
            'controller' => '\App\Controller\IndexController',
            'action' => 'store_list'
        ),
        '10016' => array(
            'id' => '10016',
            'controller' => '\App\Controller\IndexController',
            'action' => 'cuisine_cate'
        ),
        '10017' => array(
            'id' => '10017',
            'controller' => '\App\Controller\IndexController',
            'action' => 'store_detail'
        ),
        '10018' => array(
            'id' => '10018',
            'controller' => '\App\Controller\IndexController',
            'action' => 'item_detail'
        ),
        '10019' => array(
            'id' => '10019',
            'controller' => '\App\Controller\IndexController',
            'action' => 'store_collect'
        ),
        '10020' => array(
            'id' => '10020',
            'controller' => '\App\Controller\IndexController',
            'action' => 'item_praise'
        ),
        '10021' => array(
            'id' => '10021',
            'controller' => '\App\Controller\IndexController',
            'action' => 'add_cart'
        ),
        '10022' => array(
            'id' => '10022',
            'controller' => '\App\Controller\IndexController',
            'action' => 'item_comment'
        ),
        '10023' => array(
            'id' => '10023',
            'controller' => '\App\Controller\IndexController',
            'action' => 'cart_list'
        ),
        '10024' => array(
            'id' => '10024',
            'controller' => '\App\Controller\IndexController',
            'action' => 'delete_cart'
        ),
        '10025' => array(
            'id' => '10025',
            'controller' => '\App\Controller\IndexController',
            'action' => 'store_collect_list'
        ),
        '10026' => array(
            'id' => '10026',
            'controller' => '\App\Controller\OrderController',
            'action' => 'customs_index'
        ),
        '10027' => array(
            'id' => '10027',
            'controller' => '\App\Controller\OrderController',
            'action' => 'customs'
        ),
        '10028' => array(
            'id' => '10028',
            'controller' => '\App\Controller\MemberController',
            'action' => 'order_comment_info'
        ),
        '10029' => array(
            'id' => '10029',
            'controller' => '\App\Controller\IndexController',
            'action' => 'hotel_detail'
        ),
        '10030' => array(
            'id' => '10030',
            'controller' => '\App\Controller\MemberController',
            'action' => 'member_checked'
        ),
        '10031' => array(
            'id' => '10031',
            'controller' => '\App\Controller\MemberController',
            'action' => 'set_password'
        ),
        '10032' => array(
            'id' => '10032',
            'controller' => '\App\Controller\MemberController',
            'action' => 'change_zfpassword'
        ),
        '10033' => array(
            'id' => '10033',
            'controller' => '\App\Controller\MemberController',
            'action' => 'forget_zfpassword'
        ),
        '10034' => array(
            'id' => '10034',
            'controller' => '\App\Controller\IndexController',
            'action' => 'hotel_img'
        ),
        '10035' => array(
            'id' => '10035',
            'controller' => '\App\Controller\IndexController',
            'action' => 'hot_mobile'
        ),
        '10036' => array(
            'id' => '10036',
            'controller' => '\App\Controller\IndexController',
            'action' => 'tourism_price'
        ),
        '10037' => array(
            'id' => '10037',
            'controller' => '\App\Controller\IndexController',
            'action' => 'tourism_list'
        ),
        '10038' => array(
            'id' => '10038',
            'controller' => '\App\Controller\IndexController',
            'action' => 'tourism_lists'
        ),
        '10039' => array(
            'id' => '10039',
            'controller' => '\App\Controller\OrderController',
            'action' => 'hotel_order'
        ),
        '10040' => array( 
         'id' => '10040',

         'controller' => '\App\Controller\IndexController',

         'action' => 'tickets_detail'

     ),
        '10041' => array(
            'id' => '10041',
            'controller' => '\App\Controller\IndexController',
            'action' => 'direct_img'
        ),
        '10042' => array(
            'id' => '10042',
            'controller' => '\App\Controller\OrderController',
            'action' => 'immediatePayment'   
        ),  
        '10043' => array( 
            'id' => '10043',
            'controller' => '\App\Controller\IndexController',   
            'action' => 'send_email'                   
        ),   
		'10044' => array( 
            'id' => '10044',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'tickets_order'                   
        ),   
		'10045' => array( 
            'id' => '10045',
            'controller' => '\App\Controller\IndexController',   
            'action' => 'spot_search'                   
        ),   
		'10046' => array( 
            'id' => '10046',
            'controller' => '\App\Controller\IndexController',   
            'action' => 'search_list'                   
        ),  
		'10047' => array( 
            'id' => '10047',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'item_list'                   
        ),   
		'10048' => array( 
            'id' => '10048',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'item_detail'                   
        ),   
		'10049' => array( 
            'id' => '10049',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'add_cart'                   
        ),  
		'10050' => array( 
            'id' => '10050',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'cart_list'                   
        ),
		'10051' => array( 
            'id' => '10051',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'delete_cart'                   
        ), 
		'10052' => array( 
            'id' => '10052',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'confirm_order'                   
        ), 
		'10053' => array( 
            'id' => '10053',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'submit_order'                   
        ),     
		'10054' => array( 
            'id' => '10054',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'call_order'                   
        ),      
		'10055' => array( 
            'id' => '10055',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'call_order_submit'                   
        ),  
		'10056' => array( 
            'id' => '10056',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'item_collect'                   
        ),   
		'10057' => array( 
            'id' => '10057',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'item_detail'                   
        ), 
		'10058' => array( 
            'id' => '10058',
            'controller' => '\App\Controller\ItemController',   
            'action' => 'collect_list'                   
        ), 
		'10059' => array( 
            'id' => '10059',
            'controller' => '\App\Controller\MemberController',   
            'action' => 'order_list'                   
        ), 
		'10060' => array( 
            'id' => '10060',
            'controller' => '\App\Controller\MemberController',   
            'action' => 'order_delete'                   
        ), 
		'10061' => array( 
            'id' => '10061',
            'controller' => '\App\Controller\DailyGoodsController',   
            'action' => 'cate_list'                   
        ), 
		'10062' => array( 
            'id' => '10062',
            'controller' => '\App\Controller\DailyGoodsController',   
            'action' => 'store_detail'                   
        ), 
		'10063' => array( 
            'id' => '10063',
            'controller' => '\App\Controller\DailyGoodsController',   
            'action' => 'coupon_detail'                   
        ),
		'10064' => array( 
            'id' => '10064',
            'controller' => '\App\Controller\DailyGoodsController',   
            'action' => 'coupon_order_confirm'                   
        ),   
		'10065' => array( 
            'id' => '10065',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'coupon_order_submit'                   
        ),  
		'10066' => array( 
            'id' => '10066',
            'controller' => '\App\Controller\RepairManController',   
            'action' => 'repair_man_list'                   
        ),  
		'10067' => array( 
            'id' => '10067',
            'controller' => '\App\Controller\RepairManController',   
            'action' => 'repair_cate'                   
        ),             
		'10068' => array( 
            'id' => '10068',
            'controller' => '\App\Controller\MemberController',   
            'action' => 'integral_record'                   
        ),  
		'10069' => array( 
		'id' => '10069',
		'controller' => '\App\Controller\RepairManController',   
		'action' => 'repair_detial'                   
        ),    
		'10070' => array( 
		'id' => '10070',
		'controller' => '\App\Controller\OrderController',   
		'action' => 'repair_order_submit'                   
        ),  
		'10071' => array( 
		'id' => '10071',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'order_record'                   
        ), 
		'10072' => array( 
		'id' => '10072',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'order_record_delete'                   
        ),  
		'10073' => array( 
		'id' => '10073',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'member_order_list'                   
        ),  
		'10074' => array( 
		'id' => '10074',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'order_detail'                   
        ), 
		'10075' => array( 
		'id' => '10075',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'coupon_list'                   
        ), 
		'10076' => array( 
		'id' => '10076',
		'controller' => '\App\Controller\MemberController',   
		'action' => 'withdraw'                   
        ),
        '10077' => array(
            'id' => '10077',
            'controller' => '\App\Controller\MemberController',
            'action' => 'check_version'
        ),
        '10078' => array(
            'id' => '10078',
            'controller' => '\App\Controller\MemberController',
            'action' => 'save_registration_id'
        ),
        '10079' => array(
            'id' => '10079',
            'controller' => '\App\Controller\OrderController',
            'action' => 'trv_list'
        ),
        '10080' => array(
            'id' => '10080',
            'controller' => '\App\Controller\OrderController',
            'action' => 'trv_list_detail'
        ),
        '10081' => array(
            'id' => '10081',
            'controller' => '\App\Controller\OrderController',
            'action' => 'trv_list_delete'
        ),
        '10082' => array(
            'id' => '10082',
            'controller' => '\App\Controller\MemberController',
            'action' => 'add_friend'
        ),
        '10083' => array(
            'id' => '10083',
            'controller' => '\App\Controller\MemberController',
            'action' => 'update_hx_id'
        ),
        '10084' => array(
            'id' => '10084',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'deliver_order'
        ),
        '10085' => array(
            'id' => '10085',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'order_finished'
        ),
        '10086' => array(
            'id' => '10086',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'user_data'
        ),
        '10087' => array(
            'id' => '10087',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'merchant_order'
        ),
        '10088' => array(
            'id' => '10088',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'act_order'
        ),
        '10089' => array(
            'id' => '10089',
            'controller' => '\App\Controller\MemberController',
            'action' => 'user_incomings'
        ),
        '10090' => array(
            'id' => '10090',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'store_status'
        ),
        '10091' => array(
            'id' => '10091',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'store_edit'
        ),
        '10092' => array(
            'id' => '10092',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'act_item'
        ),
        '10093' => array(
            'id' => '10093',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'item_cate'
        ),
        '10094' => array(
            'id' => '10094',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'item_list'
        ),
        '10095' => array(
            'id' => '10095',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'merchant_withdraw'
        ),
        '10096' => array(
            'id' => '10096',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'alter_pwd'
        ),
        '10097' => array(
            'id' => '10097',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'act_tag'
        ),
         '10098' => array(
            'id' => '10098',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'default_img'
        ),
        '10099' => array(
            'id' => '10099',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'order_list'
        ),
        '10100' => array(
            'id' => '10100',
            'controller' => '\App\Controller\MemberController',
            'action' => 'add_user_friends'
        ),
        '10101' => array(
            'id' => '10101',
            'controller' => '\App\Controller\MemberController',
            'action' => 'friends_list'
        ),
        '10102' => array(
            'id' => '10102',
            'controller' => '\App\Controller\IndexController',
            'action' => 'search_list'
        ),
         '10103' => array(
            'id' => '10103',
            'controller' => '\App\Controller\MemberController',
            'action' => 'friend_info'
        ),
         '10104' => array(
            'id' => '10104',
            'controller' => '\App\Controller\MemberController',
            'action' => 'update_remark'
        ),
         '10105' => array(
            'id' => '10105',
            'controller' => '\App\Controller\MemberController',
            'action' => 'delete_user_friends'
        ),
        '10106' => array(
            'id' => '10106',
            'controller' => '\App\Controller\ItemController',
            'action' => 'mobile_cate'
        ),
        '10107' => array(
            'id' => '10107',
            'controller' => '\App\Controller\OrderController',
            'action' => 'get_location'
        ),
        '10108' => array(
            'id' => '10108',
            'controller' => '\App\Controller\MemberController',
            'action' => 'assign_wx_friends'
        ),
        '10109' => array(
            'id' => '10109',
            'controller' => '\App\Controller\MemberController',
            'action' => 'issue_words'
        ),
        '10110' => array(
            'id' => '10110',
            'controller' => '\App\Controller\MemberController',
            'action' => 'words_list'
        ),
        '10111' => array(
            'id' => '10111',
            'controller' => '\App\Controller\MemberController',
            'action' => 'love_click'
        ),
        '10112' => array(
            'id' => '10112',
            'controller' => '\App\Controller\MemberController',
            'action' => 'words_firt_message'
        ),
        '10113' => array(
            'id' => '10113',
            'controller' => '\App\Controller\MemberController',
            'action' => 'words_dialog_reply'
        ),
        '10114' => array(
            'id' => '10114',
            'controller' => '\App\Controller\MemberController',
            'action' => 'delete_word'
        ),
        '10115' => array(
            'id' => '10115',
            'controller' => '\App\Controller\MemberController',
            'action' => 'bg_img'
        ),
        '10116' => array(
            'id' => '10116',
            'controller' => '\App\Controller\MemberController',
            'action' => 'change_bg_img'
        ),
        '10117' => array(
            'id' => '10117',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'order_complain'
        ),
        '10118' => array(
            'id' => '10118',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'question_img'
        ),
        '10119' => array(
            'id' => '10119',
            'controller' => '\App\Controller\MemberController',
            'action' => 'questions_list'
        ),
        '10120' => array(
            'id' => '10120',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'question_record'
        ),
        '10121' => array(
            'id' => '10121',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'deliver_incomings'
        ),
        '10122' => array(
            'id' => '10122',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'job_skills'
        ),
         '10123' => array(
            'id' => '10123',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'skill_detail'
        ),
         '10124' => array(
            'id' => '10124',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'deliver_assistant'
        ),
        '10125' => array(
            'id' => '10125',
            'controller' => '\App\Controller\MemberController',
            'action' => 'account_bind'
        ),
        '10126' => array(
            'id' => '10126',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'alter_deliver_pwd'
        ),
        '10127' => array(
            'id' => '10127',
            'controller' => '\App\Controller\MainOrderController',
            'action' => 'deliver_balance'
        ),
        '10128' => array(
            'id' => '10128',
            'controller' => '\App\Controller\MerchantController',
            'action' => 'update_addr'
        ),
        '10129' => array( 
            'id' => '10129',
            'controller' => '\App\Controller\OrderController',   
            'action' => 'bargin_order'                   
        ),  
        '10130' => array( 
            'id' => '10130',
            'controller' => '\App\Controller\MainOrderController',   
            'action' => 'do_bargin'                   
        ),  
        '10131' => array( 
            'id' => '10131',
            'controller' => '\App\Controller\MainOrderController',   
            'action' => 'bargin_list'                   
        ),  
        '10132' => array( 
            'id' => '10132',
            'controller' => '\App\Controller\MainOrderController',   
            'action' => 'bargin_it'                   
        ),  
        '10133' => array( 
            'id' => '10133',
            'controller' => '\App\Controller\MainOrderController',   
            'action' => 'bargin_order'                   
        ),  
        '10134' => array( 
            'id' => '10134',
            'controller' => '\App\Controller\MainOrderController',   
            'action' => 'alter_store'                   
        ), 
        '10135' => array( 
            'id' => '10135',
            'controller' => '\App\Controller\MerchantController',   
            'action' => 'refuse_send'                   
        ),
        '10137' => array( 
            'id' => '10137',
            'controller' => '\App\Controller\MerchantController',   
            'action' => 'receive_order'                   
        ),    
        '10138' => array( 
            'id' => '10138',
            'controller' => '\App\Controller\RepairManController',   
            'action' => 'address_list'                   
        ),   
    ); 
 