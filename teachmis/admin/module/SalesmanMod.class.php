<?php
class SalesmanMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function myClient()
    {
        $p = 10 * ($_GET['page'] - 1);
        $createTimeStart = '';
        $createTimeEnd = '';
        $loginTimeStart = '';
        $loginTimeEnd = '';
        $areaCode = '';
        if ($_GET['order_create_time_start']) {
            $createTimeStart = "and o.createTime >= '{$_GET['order_create_time_start']}'";
        }
        if ($_GET['order_create_time_end']) {
            $createTimeEnd = "and o.createTime <= '{$_GET['order_create_time_end']}'";
        }

        if ($_GET['login_time_start']) {
            $loginTimeStart = "and lul.loginTIme >= '{$_GET['login_time_start']}'";
        }
        if ($_GET['login_time_end']) {
            $loginTimeEnd = "and lul.loginTIme <= '{$_GET['login_time_end']}'";
        }
        if ($_GET['area_code']) {
            $areaCode = " and u.areaCode = '{$_GET['area_code']}'";
        }
        $myClient = $this->model
            ->query("
                            SELECT u.userId, shopName, shopName, COUNT(o.orderId) orderNum, MAX(o.createTime) createTime, MAX(lul.loginTime) loginTime, areaCode 
                            FROM caiba_users u 
                            JOIN caiba_orders o ON o.userId = u.userId 
                            JOIN caiba_log_user_logins lul ON lul.userId = u.userId 
                            WHERE invited_code = 10806242 {$createTimeStart} {$createTimeEnd} {$loginTimeStart} {$loginTimeEnd} {$areaCode} 
                            GROUP BY shopName LIMIT {$p}, 10
                        ");
        echo json_encode(['errno' => 0, 'message' => '获取成功', 'data' => $myClient], JSON_UNESCAPED_UNICODE);
    }
    //=====用户统计=======
    public function Census(){
        //获取到传过来的日期筛选
        // 1为日 2为周 3为月
        $_POST['type']=3;
        $_POST['id']=1;
        $invited_code=1111;
        $type=empty($_POST['type'])?'1':$_POST['type'];
        $whereTime='';
        switch ($type) {
            case '1':
                $whereTime="createTime>'".date("Y-m-d")." 00:00:00'";
                $lastWhereTime="lastTime>'".date("Y-m-d")." 00:00:00'";
                $owhereTime="o.createTime>'".date("Y-m-d")." 00:00:00'";
                // 小于今天的
                $NewOrderwhereTime="createTime<'".date("Y-m-d")." 00:00:00'";
            break;
            case '2':
                $whereTime="YEARWEEK(createTime,1)=YEARWEEK(NOW(),1)"; 
                $owhereTime="YEARWEEK(o.createTime,1)=YEARWEEK(NOW(),1)"; 
                //小于上周的时间
                $times=strtotime(date('Y-m-d')." 00:00:00");
                $week=date('w');
                if($week==0){
                    $week=7;
                }
                $week--;
                $time=$week*86400;
                $time=$times-$time;
                $time=date('Y-m-d H:i:s',$time);
                // echo $week;
                $NewOrderwhereTime="createTime<'".$time."'";
                $lastWhereTime="lastTime>'".$time."'";
            break;
            case '3':
                $whereTime="DATE_FORMAT( createTime, '%Y%m' ) = DATE_FORMAT( Now() , '%Y%m' )";
                $owhereTime="DATE_FORMAT( o.createTime, '%Y%m' ) = DATE_FORMAT( Now() , '%Y%m' )";
                //小于上一个月的时间
                $NewOrderwhereTime="createTime<'".date('Y-m')."-01 00:00:00'";
                $lastWhereTime="lastTime>'".date('Y-m')."-01 00:00:00'";
            break;  
            default:
                die;
                echo "未知错误";
            break;
        }
        // //目标注册完成数
        $targetNumSql="select * from att_target where c_userId = 1";
        $targetNum = $this->model->query($targetNumSql);
        //注册客户
        $registerSql="select * from caiba_users where invited_code={$invited_code} and ".$whereTime;
        $register = $this->model->query($registerSql);
        //=========注册数================
        $registerNum['factNum']=empty(count($register))?0:count($register);
        //目标注册数
        switch ($type) {
            case '1':
                //注册的目标
                $registerNum['targetNum']=$targetNum[0]['Dregister'];
                //目标下单数
                $newOrder['targetNum']=$targetNum[0]['DnewOrderUser'];
                //活跃客户数
                $activeUser['targetNum']=$targetNum[0]['Dactive'];
                //下单用户数
                $orderNum['targetNum']=$targetNum[0]['Dorder'];
                //客单价
                $userPrice['targetNum']=$targetNum[0]['Dprice'];
                //总销量
                $userPrices['targetNum']=$targetNum[0]['Dzprice'];
            break;
            case '2':
                //注册的目标
                $registerNum['targetNum']=$targetNum[0]['Wregister'];
                //目标下单数
                $newOrder['targetNum']=$targetNum[0]['WnewOrderUser'];
                //活跃客户数
                $activeUser['targetNum']=$targetNum[0]['Wactive'];
                //下单用户数
                $orderNum['targetNum']=$targetNum[0]['Worder'];
                //客单价
                $userPrice['targetNum']=$targetNum[0]['Wprice'];
                //总销量
                $userPrices['targetNum']=$targetNum[0]['Wzprice'];
            break;
            case '3':
                //注册的目标
                $registerNum['targetNum']=$targetNum[0]['Mregister'];
                //目标下单数
                $newOrder['targetNum']=$targetNum[0]['MnewOrderUser'];
                //活跃客户数
                $activeUser['targetNum']=$targetNum[0]['Mactive'];
                //下单用户数
                $orderNum['targetNum']=$targetNum[0]['Morder'];
                //客单价
                $userPrice['targetNum']=$targetNum[0]['Mprice'];
                //总销量
                $userPrices['targetNum']=$targetNum[0]['Mzprice'];
            break;
        }
        //所占百分比
        $registerNum['percent']=floor($registerNum['factNum']/$registerNum['targetNum']*100)."%";
        //========注册数end==================

        //=======新客户下单数============
        //这个业务员的用户的下单
        $newOrdersSql="select o.userId,sum(o.realTotalMoney) money from caiba_orders o,caiba_users u where o.userId=u.userId and o.orderStatus in (1,2,3,4) and ".$owhereTime." and u.invited_code={$invited_code} group by o.userId";
        $newOrders = $this->model->query($newOrdersSql);
        // //判断有几个新用户下单
        $newOrder['factNum']=0;
        foreach ($newOrders  as $v) {
            $userNewOrderSql="select * from caiba_orders where userId=".$v['userId']." and orderStatus in (1,2,3,4) and ".$NewOrderwhereTime." limit 1";
            $oldOrders = $this->model->query($userNewOrderSql);
            if(empty($oldOrders)){
                $newOrder['factNum']++;
            }
        }
        //所占百分比
        $newOrder['percent']=floor($newOrder['factNum']/$newOrder['targetNum']*100)."%";
        //=======新客户下单数end=========

        // //=======活跃客户数=============
        $activeUserNumSql="select count(userId) num from caiba_users where invited_code={$invited_code} and ".$lastWhereTime;
        $activeUserNum = $this->model->query($activeUserNumSql);
        //实际活跃客户数
        $activeUser['factNum']=empty($activeUserNum)?0:$activeUserNum[0]['num'];
        //所占百分比
        $activeUser['percent']=floor($activeUser['factNum']/$activeUser['targetNum']*100)."%";
        //=======活跃客户数end==========

        //===============下单客户数==================
        $orderNum['factNum']=empty(count($newOrders))?0:count($newOrders);
        $orderNum['percent']=floor($orderNum['factNum']/$orderNum['targetNum']*100)."%";
        //===============下单客户数end==================

        //===========销售总价========
        $userPrices['factNum']=0;
        // var_dump($newOrders);
        foreach ($newOrders as $val) {
            $userPrices['factNum'] += $val['money'];
        }
        $userPrices['percent']=floor($userPrices['factNum']/$userPrices['targetNum']*100)."%";
        //===========销售总价end============
        
        //===========客单价=======
        $userPrice['factNum']=floor($userPrices['factNum']/$orderNum['factNum']*100)/100;
        $userPrice['percent']=floor($userPrice['factNum']/$userPrice['targetNum']*100)."%";
        //=========客单价end====

        //=======注册客户总数===========
        $Zregister = $this->model->table('caiba_users')->where(['invited_code'=>$invited_code])->count(); 
        
        $ZactiveUserNumSql="select count(userId) num from caiba_users where invited_code={$invited_code} and lastTime >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
        $ZactiveUserNum = $this->model->query($ZactiveUserNumSql);
        //实际活跃人数
        $ZactiveUser=empty($ZactiveUserNum)?0:$ZactiveUserNum[0]['num'];
        //=========注册客户总数end======


        $re=[
            'errno'=>"0",
            'message'=>'数据',
            'Zregister'=>$Zregister,
            'ZactiveUser'=>$ZactiveUser,
            'data'=>[
                //注册的目标
                'registerNum'=>$registerNum,
                //目标下单数
                'newOrder'=>$newOrder,
                //活跃客户数
                'activeUser'=>$activeUser,
                //下单用户数
                'orderNum'=>$orderNum,
                //客单价
                'userPrice'=>$userPrice,
                //总销量
                'userPrices'=>$userPrices
            ]
        ];
        echo json_encode($re, JSON_UNESCAPED_UNICODE);

    }

    public function clientDetail()
    {
        $p = 10 * ($_GET['page'] - 1);
        $orderList = $this->model
            ->query("
                            SELECT orderId, orderNo, createTime, realTotalMoney 
                            FROM caiba_orders 
                            WHERE userId = {$_GET['user_id']} AND orderStatus != -1 
                            ORDER BY createTime DESC 
                            limit {$p}, 10
                         ");
        echo json_encode(['errno' => '0', 'message' => '获取成功', 'data' => $orderList], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function clientOrderDetail()
    {
        $orderGoods = $this->model
            ->query("
                            SELECT g.goodsSn, og.goodsName, g.guige, og.goodsPrice, og.goodsNum, og.goodsPrice*og.goodsNum subtotal, o.orderRemarks, payType, isPay 
                            FROM caiba_order_goods og 
                            JOIN caiba_orders o on o.orderId = og.orderId 
                            JOIN caiba_goods g on og.goodsId = g.goodsId 
                            WHERE o.orderId = {$_GET['order_id']}
                          ");
        if ($orderGoods[0]['payType'] == 0) {
            $payType = '货到付款';
        } else if ($orderGoods[0]['payType'] == 1 && $orderGoods[0]['isPay'] == 1) {
            $payType = '线上已支付';
        } else {
            $payType = '线上未支付';
        }
        echo json_encode(['errno' => '0', 'message' => '获取成功', 'orderRemarks' => $orderGoods[0]['orderRemarks'], 'payType' => $payType, 'data' => $orderGoods], JSON_UNESCAPED_UNICODE);
    }

    public function Hukouduoshi()
    {
        $p = 10 * ($_GET['page'] - 1);
        $createTimeStart = '';
        $createTimeEnd = '';
        $loginTimeStart = '';
        $loginTimeEnd = '';
        $areaCode = '';
        if ($_GET['order_create_time_start']) {
            $createTimeStart = "and o.createTime >= '{$_GET['order_create_time_start']}'";
        }
        if ($_GET['order_create_time_end']) {
            $createTimeEnd = "and o.createTime <= '{$_GET['order_create_time_end']}'";
        }

        if ($_GET['login_time_start']) {
            $loginTimeStart = "and lul.loginTIme >= '{$_GET['login_time_start']}'";
        }
        if ($_GET['login_time_end']) {
            $loginTimeEnd = "and lul.loginTIme <= '{$_GET['login_time_end']}'";
        }
        if ($_GET['area_code']) {
            $areaCode = " and u.areaCode = '{$_GET['area_code']}'";
        }
        $myClient = $this->model
            ->query("
                            SELECT u.userId, shopName, shopName, COUNT(o.orderId) orderNum, MAX(o.createTime) createTime, MAX(lul.loginTime) loginTime, areaCode 
                            FROM caiba_users u 
                            JOIN caiba_orders o ON o.userId = u.userId 
                            JOIN caiba_log_user_logins lul ON lul.userId = u.userId 
                            WHERE invited_code = 10806242 {$createTimeStart} {$createTimeEnd} {$loginTimeStart} {$loginTimeEnd} {$areaCode} 
                            AND (PERIOD_DIFF(DATE_FORMAT(NOW( ), '%Y%m' ) , DATE_FORMAT( 'o.createTime', '%Y%m' )) = 2  or PERIOD_DIFF(DATE_FORMAT(NOW( ), '%Y%m' ) , DATE_FORMAT( 'lul.loginTIme', '%Y%m' )) = 2)
                            GROUP BY shopName LIMIT {$p}, 10
                        ");
        echo json_encode(['errno' => 0, 'message' => '获取成功', 'data' => $myClient], JSON_UNESCAPED_UNICODE);
    }
}