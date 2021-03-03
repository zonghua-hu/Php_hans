<?php

namespace download;

use download\activitiesStatistics\DownFullReduceLogic;
use download\activitiesStatistics\DownPriceDeduceLogic;
use download\activitiesStatistics\DownRedPacketLogic;
use download\activitiesStatistics\DownSendGiftWithPurchaseLogic;
use download\activitiesStatistics\DownWheelGameLogic;
use Helper;
use Logger;
use Constant;
use oil\ICCardExportLogic;
use oil\OrderListExportLogic;
use oil\OrderSettlementExportLogic;
use WPLib\Caching\Redis;
use WPLib\WPApi;
use Phalcon\Exception;
use helpers\ArrayHelper;
use AdminDownloadRecordModel;

/**
 * @desc 下载列表
 * 一.新增一种导出任务:
 *  1.增加常量数据类型EXPORT_TYPE
 *  2.在下方增加一个EXPORT_XXXX常量,
 *    data_from   数据来源,作为数据导出的唯一标识.由数据类型+递增数字组成,总长4位,0填充
 *    type        数据类型,与EXPORT_TYPE中的key匹配
 *    title       导出报表标题
 *    request_api 获取数据需请求底层服务的URL,若调用其他Logic为空
 *  3.在doExport方法中添加一个case匹配新的data_from
 *  4.根据doExport方法中的调用,创建对应的DownXxxxxLogic,并完善其中的逻辑
 *
 * 二.创建一条导出任务
 *  调用createRecord方法
 *
 * @author wenyaobo
 * @Date: 2020-04-08
 */
class DownloadLogic extends \BaseLogic
{
    //导出状态
    const STATUS_WAIT_EXPORT = 1;  //待生成
    const STATUS_EXPORTING = 2;  //正在生成
    const STATUS_EXPORT_FAIL = 9;  //生成失败
    const STATUS_EXPORT_FINISH = 16; //生成成功

    //频繁操作 控制时间(s)
    const FREQUENT_OPERATION_TIME = 60;
    //获取数据时 每页数据量(默认每页1千条)
    const GET_DATA_PAGE_LIMIT = 1000;
    //表格内容强制转换为文本格式符
    const SET_DATA_STRING = "\t";
    //错误提示前缀
    const ERROR_PREFIX = '数据导出';
    //请求数据接口时超时时间
    const REQUEST_TIME_OUT = 1000;
    //自动修复限时 维持数据生成中状态的时间
    const AUTO_FIX_SECONDS = 300;
    //tmp路径
    const TMP_PATH = BASE_PATH . 'data/tmp/';

    //队列信息
    const QUEUE_STREAM = 'download_stream_' . ENVIRON1;
    const QUEUE_GROUP = 'download_group_' . ENVIRON1;
    const QUEUE_CONSUMER = 'download_consumer_' . ENVIRON1;
    const QUEUE_MESSAGE_TYPE_DOWNLOAD = 1;

    //数据类型
    const EXPORT_TYPE = [
        1 => '财务'
        , 2 => '客户'
        , 3 => '积分'
        , 5 => '通知'
        , 6 => '数据'
        , 7 => '订单'
    ];

    //+--------------------------------------
    //| 数据导出配置
    //| 请确保执行导出的class有export()方法 !!!
    //+--------------------------------------

    const EXPORT_LIST = [

        //1 财务
        1001 => ['type' => 1, 'title' => '财务对账', 'class' => DownFinanceStatementsLogic::class],
        1002 => ['type' => 1, 'title' => '财务结算', 'class' => DownSettlementStatementsLogic::class],

        //2 客户
        2001 => ['type' => 2, 'title' => '客户列表', 'class' => DownCustomerListLogic::class],
        2002 => ['type' => 2, 'title' => '客群详情', 'class' => DownCustomerLogic::class],
        2003 => ['type' => 2, 'title' => '客户概览', 'class' => CustomerDataLogic::class],
        2004 => ['type' => 2, 'title' => '客户导入', 'class' => DownCustomerImportLogic::class], //客户导入数据
        2005 => ['type' => 2, 'title' => '实时客户数据', 'class' => DownCustomerRealtimeLogic::class], //客户列表
        2006 => ['type' => 2, 'title' => '客户标签', 'class' => DownCustomerTagLogic::class],
        2007 => ['type' => 2, 'title' => '个人卡客户', 'class' => DownPersonalCardDataLogic::class],

        //3积分
        3001 => ['type' => 3, 'title' => '积分记录', 'class' => DownUserPointsLogic::class],

        //5通知
        5001 => ['type' => 5, 'title' => '短信通知', 'class' => DownMessageNoticeLogic::class],

        //6数据
        6001 => ['type' => 6, 'title' => '满额减', 'class' => DownFullReduceLogic::class],
        6002 => ['type' => 6, 'title' => '油站月报', 'class' => DownMonthReportLogic::class],
        6003 => ['type' => 6, 'title' => '整体看板导出数据', 'class' => DownOverviewDataLogic::class],
        6004 => ['type' => 6, 'title' => '优惠券明细', 'class' => DownCouponRecordLogic::class],
        6005 => ['type' => 6, 'title' => '红包', 'class' => DownRedPacketLogic::class],
        6006 => ['type' => 6, 'title' => '抽奖(大转盘)', 'class' => DownWheelGameLogic::class],
        6007 => ['type' => 6, 'title' => '满额送', 'class' => DownSendGiftWithPurchaseLogic::class],
        6008 => ['type' => 6, 'title' => '价立减', 'class' => DownPriceDeduceLogic::class],
        6009 => ['type' => 6, 'title' => '数据-车辆', 'class' => DownIdentifyDataLogic::class], //车辆认证数据
        6010 => ['type' => 6, 'title' => '充值送券', 'class' => DownRechargeSendCouponLogic::class],
        6011 => ['type' => 6, 'title' => '销售明细', 'class' => OrderListExportLogic::class],   //导出油机加油单
        6012 => ['type' => 6, 'title' => '交班统计', 'class' => OrderSettlementExportLogic::class], //导出加油单交班统计数据
        6013 => ['type' => 6, 'title' => '加油统计', 'class' => DownPerformanceOilLogic::class],    //员工绩效-加油统计
        6014 => ['type' => 6, 'title' => '充值统计', 'class' => DownPerformanceRechargeLogic::class],   //员工绩效-充值统计
        6015 => ['type' => 6, 'title' => '销售报表', 'class' => DownloadSaleReportLogic::class],
        6016 => ['type' => 6, 'title' => '回罐明细', 'class' => DownloadBankTankLogic::class],  //油品回罐明细
        6017 => ['type' => 6, 'title' => 'IC卡汇总', 'class' => ICCardExportLogic::class],  //IC卡汇总导出
        // 7订单
        7001 => ['type' => 7, 'title' => '订单明细', 'class' => DownloadOrderLogic::class],  //订单明细
    ];

    static public $errorMsg = null;

    /**
     * @Desc: 生成报表
     *  1.该方法由swoole的异步任务(onTask方法)调用
     *  2.根据data_from匹配具体的导出Logic执行生成文档的操作
     *  3.生成成功返回一个文件路径string,失败返回false
     * @Author: WenYaobo
     * @DateTime: 2020-04-09 18:32
     */
    static public function doExport($download_id)
    {
        if (self::$errorMsg !== null) {
            self::$errorMsg = null;
        }
        if ($download_id) {

            $download_model = new AdminDownloadRecordModel();
            $download_model->ensureConnection($download_model::MODE_CONNECTION_ALL);
            $downloadInfo = $download_model->findFirst($download_id);

            if ($downloadInfo === false) {
                Logger::info('导出任务不存在');
                return false;
            }
            if ($downloadInfo->status == self::STATUS_EXPORT_FINISH) {
                Logger::info('导出任务已完成');
                return false;
            }

            if (!isset(self::EXPORT_LIST[$downloadInfo->data_from])) {
                $msg = '未获取到任务配置信息(EXPORT_LIST),请联系管理员';
                self::$errorMsg = $msg;
                Logger::info($msg);
                return false;
            }

            $download_class = self::EXPORT_LIST[$downloadInfo->data_from]['class'];

            if (!class_exists($download_class)) {
                $msg = '未获取到导出执行类(class:'.$download_class.'),请联系管理员';
                self::$errorMsg = $msg;
                Logger::info($msg);
                return false;
            }

            try {
                //匹配相应的导出逻辑
                //请确保执行导出的class有export()方法 !!!
                if (!method_exists($download_class, 'export')) {
                    $msg = '未获取到导出执行方法(export),请联系管理员';
                    self::$errorMsg = $msg;
                    Logger::info($msg);
                    return false;
                }
                $obj = new $download_class;
                return call_user_func([$obj, 'export'], $downloadInfo->request_params);

            } catch (\Exception $e) {
                $msg = '执行导出任务失败,请联系管理员 ' . $e->getMessage();
                self::$errorMsg = $msg;
                Logger::info($msg);
                return false;
            }
        }
    }

    /**
     * @Desc: 添加下载记录
     * @Author: WenYaobo
     * @DateTime: 2020-04-09 19:41
     */
    public static function createRecord(array $adminInfo, int $dataFrom, array $params)
    {
        try {
            if (!isset($adminInfo['admin_id']) || empty($adminInfo['admin_id'])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, 'admin_id empty！');
            }
            if (!isset($adminInfo['merchant_id']) || empty($adminInfo['merchant_id'])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, 'merchant_id empty！');
            }
            if (!isset($adminInfo['merchant_type']) || empty($adminInfo['merchant_type'])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, 'merchant_type empty！');
            }
            if (!in_array($adminInfo['merchant_type'], [Constant::MERCHANT_TYPE_STATION,
                Constant::MERCHANT_TYPE_COMPANY, Constant::MERCHANT_TYPE_GROUP])
            ) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, 'merichant_type error！');
            }
            if (empty(self::EXPORT_LIST[$dataFrom])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, '导出配置有误,请联系管理员');
            }
            $exportInfo = self::EXPORT_LIST[$dataFrom];

            if (!isset($exportInfo['type']) || empty($exportInfo['type'])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, '导出配置有误:type empty！');
            }
            if (!isset($exportInfo['title']) || empty($exportInfo['title'])) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, '导出配置有误:title empty！');
            }
            if (empty($params)) {
                return Helper::addError(Constant::EMPTY_VAL_CODE, 'params empty！');
            }

            //校检该类型任务60s内不重复生成
            $lockKey = self::getDownloadLockKey(
                $adminInfo['admin_id'],
                $adminInfo['merchant_id'],
                $adminInfo['merchant_type'],
                $dataFrom
            );

            if (false == self::checkDownloadLock($lockKey, 60)) {
                return false;
            }

            $model = new AdminDownloadRecordModel();
            $model->export_title = self::createExportTitle(
                $dataFrom,
                $exportInfo['title']
            );
            $model->type = $exportInfo['type'];
            $model->admin_id = $adminInfo['admin_id'];
            $model->merchant_id = $adminInfo['merchant_id'];
            $model->merchant_type = $adminInfo['merchant_type'];
            $model->data_from = $dataFrom;
            $model->request_params = json_encode($params);
            $model->create_time = time();
            $model->environ1 = self::getEnviron1();

            $res = $model->save();
            if ($res === false) {
                return Helper::addError(Constant::CHECK_NOT_CODE, current($model->getMessages()));
            }

            $download_id = $model->id;

            /**
             * 向redis队列添加任务
             * @var $redis Redis
             */
            $di = \Phalcon\Di::getDefault();
            $redis = $di->get('redis');
            $redisResult = $redis->xAdd(
                DownloadLogic::QUEUE_STREAM,
                [
                    'id' => $download_id,
                    'type' => DownloadLogic::QUEUE_MESSAGE_TYPE_DOWNLOAD,
                ],
                '*'
            );
            if ($res === false) {
                return Helper::addError(Constant::COMMON_STATUS, '添加任务队列失败');
            }

            return $redisResult;
        } catch (\Exception $e) {
            Logger::ERROR('create:' . $e);
            return Helper::addError(Constant::ERROR_CODE, $e->getMessage());
        }
    }


    /**
     * 获取正在下载的任务数量
     * @author huph
     */
    public static function getDownloadingNum ($type = 0)
    {
        $str = 'status = 2';
        if ($type > 0) {
            $str .= ' and type = ' . $type;
        }
        
        $refundObj = AdminDownloadRecordModel::query();
        return $refundObj->where($str)->execute()->count();
    }

    public static function getDownloadLockKey($adminId, $merchantId, $merchantType, $dataFrom)
    {
        return sprintf(
            'download::lock::%s_%s_%s_%s',
            $adminId,
            $merchantId,
            $merchantType,
            $dataFrom
        );
    }

    /**
     * @Desc:
     * @Author: WenYaobo
     * @DateTime: 2020-11-10 17:37
     */
    public static function checkDownloadLock($key, $expire = 60)
    {
        if (empty($key)) {
            return Helper::addError(Constant::CHECK_NOT_CODE, 'key of redis lock empty');
        }

        /**
         * @var $redis Redis
         */
        $di = \Phalcon\Di::getDefault();
        $redis = $di->get('redis');
        $res = $redis->set($key, 1, ['nx', 'ex' => $expire]);

        if ($res == false) {
            return Helper::addError(Constant::ERROR_CODE, '请勿频繁操作!');
        }

        return true;
    }

    /**
     * @Desc: 获取下载列表
     * @Author: WenYaobo
     * @DateTime: 2020-04-13 10:34
     */
    public static function getDownloadList($params)
    {
        //自定义查询字段
        $fields = ArrayHelper::remove($params, 'fields');
        //WHERE最前面的查询条件
        $before = ArrayHelper::remove($params, 'before');
        //WHERE最后面的查询条件
        $after = ArrayHelper::remove($params, 'after');

        $searchModel = new \Search([
            'targetClass' => 'AdminDownloadRecordModel',
            'fields' => !empty($fields) ? $fields : '*',
            'filterDeleted' => true,
            'ignorePage' => false, //分页
            'orderBy' => ArrayHelper::remove($params, 'order_by'),
            'groupBy' => ArrayHelper::remove($params, 'group_by'),
        ]);

        $where = []; //查询条件
        if (!empty($params)) {
            foreach ($params as $fieldName => $fieldVal) {
                switch ($fieldName) {
                    case 'type' :
                        $where[] = [$fieldName, '=', $fieldVal];
                        break;
                    case 'merchant_id' :
                        $where[] = [$fieldName, 'IN', $fieldVal];
                        break;
                    case 'create_time' :
                        $where[] = [$fieldName, 'BETWEEN', $fieldVal[0] ?? 0, $fieldVal[1] ?? 0];
                        break;
                    case 'export_title' :
                        $where[] = [$fieldName, 'LIKE', $fieldVal];
                        break;
                    case 'page_size' :
                    case 'current_page' :
                        $where[$fieldName] = $fieldVal;
                        break;
                    default :
                        $fieldVal = is_numeric($fieldVal) ? $fieldVal : "'$fieldVal'";
                        $where[] = [$fieldName, '=', $fieldVal];
                        break;
                }
            }
        }

        try {
            $res = $searchModel->search($where, [
                'before' => function ($builder) use ($before) {
                    if (!empty($before) && is_string($before)) {
                        $builder->where($before);
                    }
                    return $builder;
                },
                'after' => function ($builder) use ($after) {
                    if (!empty($before) && is_string($before)) {
                        $builder->andWhere($after);
                    }
                    return $builder;
                },
            ]);
            return $res;

        } catch (\Exception $e) {
            return Helper::addError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @Desc: 获取文件下载地址
     * @incr: true:下载次数自增1 false:不递增
     * @Author: WenYaobo
     * @DateTime: 2020-04-13 16:26
     */
    public static function getFilePath($admin_id, $download_id, $incr = false)
    {
        if (empty($admin_id)) {
            return Helper::addError(Constant::ERROR_CODE, 'admin_id 不能为空!');
        }
        if (empty($download_id)) {
            return Helper::addError(Constant::ERROR_CODE, 'download_id 不能为空!');
        }

        $download = AdminDownloadRecordModel::findFirst($download_id);
        if ($download === false) {
            return Helper::addError(Constant::ERROR_CODE, '下载记录ID有误');
        }
        if ($download->admin_id != $admin_id) {
            return Helper::addError(Constant::ERROR_CODE, '当前下载账号与导出账号不一致，请导出后再下载');
        }
        if (empty($download->file_path)) {
            return Helper::addError(Constant::ERROR_CODE, '未获取到数据文件,请重新导出数据');
        }
        if ($incr !== false) {
            $download->update_time = time();
            $download->download_times++; //自增
            $res = $download->save();
            if ($res === false) {
                return Helper::addError(Constant::ERROR_CODE, current($download->getMessages()));
            }
        }
        return $download->file_path;
    }

    /**
     * @Desc: 更新导出任务状态
     * 导出成功|导出失败
     * @Author: WenYaobo
     * @DateTime: 2020-04-09 20:16
     */
    public static function updateExportStatus($download_id, $file_path)
    {
        $status = empty($file_path) ?
            self::STATUS_EXPORT_FAIL : self::STATUS_EXPORT_FINISH;
        if (empty($download_id)) {
            Logger::info(self::ERROR_PREFIX . ' id 不能为空');
            return false;
        }
        if (empty($status)) {
            Logger::info(self::ERROR_PREFIX . ' status 不能为空');
            return false;
        }
        $time = time();
        $download = AdminDownloadRecordModel::findFirst($download_id);
        $download->status = $status;
        $download->file_path = $file_path;
        $download->error_msg = empty($file_path) && self::$errorMsg !== null ?
            self::$errorMsg : '';
        $download->update_time = $time;
        $download->file_create_time = $time;
        $res = $download->save();
        if ($res === false) {
            Logger::info(self::ERROR_PREFIX . ' ' . current($download->getMessages()));
        }
        return $res;
    }

    /**
     * @Desc: 将生成中的任务置为待生成
     * @Author: WenYaobo
     * @DateTime: 2020-06-10 10:59
     */
    public static function reSetStatus($downloadIdArr = [])
    {
        $condition = " status = " . self::STATUS_EXPORTING;
        if (!empty($downloadIdArr)) {
            $condition .= " AND id in (" . implode(',', $downloadIdArr) . ") ";
        }

        $download_model = new AdminDownloadRecordModel();
        $download_model->ensureConnection($download_model::MODE_CONNECTION_READ);
        $list = $download_model->find([
            'conditions' => $condition,
            'columns' => 'id'
        ]);

        if (!empty($list)) {
            foreach ($list as $v) {
                $down = AdminDownloadRecordModel::findFirst($v->id);
                $down->status = DownloadLogic::STATUS_WAIT_EXPORT;
                $res = $down->save();
                if (!$res) {
                    Logger::error(self::ERROR_PREFIX . ' 重置状态出错');
                }
            }
        }
        return true;
    }

    /**
     * @Desc: 获取导出数据
     * @Author: WenYaobo
     * @DateTime: 2020-04-10 15:08
     */
    public static function getExportData($url, $params)
    {
        $res = WPApi::call($url, ['data' => $params], 'POST', ['is_ignore_monitor' => 1], self::REQUEST_TIME_OUT, 5);
        if (!isset($res['status'])) {
            Logger::info(self::ERROR_PREFIX . ' 服务器出错');
            self::$errorMsg = '服务器出错,请重试';
            return false;
        } elseif ($res['status'] != 200) {
            Logger::info(self::ERROR_PREFIX . ' ' . $res['info']);
            self::$errorMsg = $res['info'];
            return false;
        } else {
            return $res;
        }
    }

    /**
     * @Desc: 生成导出报表名称
     * @Author: WenYaobo
     * @DateTime: 2020-04-09 19:40
     */
    public static function createExportTitle($data_from, $title)
    {
        //组装where条件
        $conditions = "data_from = '{$data_from}'";
        $model = new AdminDownloadRecordModel();
        $order_num = $model::count($conditions) + 1; //加一
        if ($order_num < 10) {
            $order_num = '0' . $order_num;
        }
        return $title . $order_num . '-' . date('Ymd');
    }

    /**
     * @Desc: 获取状态名称
     * @Author: WenYaobo
     * @DateTime: 2020-04-13 15:55
     */
    public static function getStatusName($status)
    {
        switch ($status) {
            case self::STATUS_WAIT_EXPORT:
            case self::STATUS_EXPORTING:
                return '报表生成中';
            case self::STATUS_EXPORT_FAIL:
                return '报表导出失败';
            case self::STATUS_EXPORT_FINISH:
                return '报表已导出';
            default:
                return '-';
        }
    }

    /**
     * @Desc: 获取类型名称
     * @Author: WenYaobo
     * @DateTime: 2020-04-13 15:55
     */
    public static function getTypeName($type)
    {
        return ArrayHelper::getValue(self::EXPORT_TYPE, $type, '');
    }

    /**
     * @Desc: 获取当前正在运行的环境变量1
     * 主要使用:
     * 根据$_SERVER['ENVIRON1']
     * develop/test/test1/test2/preview/preview3等非线上环境,
     * 执行不同环境下的任务,
     * 避免多人同时开发下载服务中某功能时,下载服务只能满足一个环境的窘境
     * @Author: WenYaobo
     * @DateTime: 2020-06-22 15:07
     */
    public static function getEnviron1()
    {
        $environ1 = !empty($_SERVER['ENVIRON1']) ? $_SERVER['ENVIRON1'] : $_SERVER['ENVIRON'];
        return $environ1;
    }

    /**
     * @Desc: 获取临时文件夹路径
     * @Author: WenYaobo
     * @DateTime: 2020-09-24 14:01
     */
    public static function getTmpPath($path = '')
    {
        if (empty($path)) {
            $path = DownloadLogic::TMP_PATH;
        }
        if (!is_dir($path)) {
            mkdir($path, 0770);
        }
        return $path;
    }

    /**
     * @Desc: 获取文件名
     * @Author: WenYaobo
     * @DateTime: 2020-09-24 14:01
     */
    public static function getFileName($title)
    {
        return $title . "数据导出" . uniqid() . '.xls';
    }

    /**
     * @desc:获取支付方式
     * @param $v
     * @return string
     * @author: wuxy02
     * @date: 2020-05-22
     * @time: 17:27
     */
    public static function getPaymentName($v)
    {
        if (in_array($v, [101, 102, 103, 106, 107, 109, 10101, 10110, 10202, 50101, 50110, 50202])) {
            $paymentName = "微信支付";
        } elseif (in_array($v, [202, 203, 207, 16110, 16202, 56110, 56202])) {
            $paymentName = "支付宝";
        } elseif (in_array($v, [20000])) {
            $paymentName = "加油卡";
        } elseif (in_array($v, [40120, 40110])) {
            $paymentName = "建行";
        } elseif ($v >= 30000 && $v <= 40000) {
            $paymentName = "团油支付";
        } elseif (in_array($v, [10])) {
            $paymentName = "现金";
        } elseif (in_array($v, [20])) {
            $paymentName = "银联卡支付(储蓄卡)";
        } elseif (in_array($v, [21])) {
            $paymentName = "银联卡支付(信用卡)";
        } elseif ($v == 30) {
            $paymentName = '积分支付';
        } elseif ($v == 9999) {
            $paymentName = '免单';
        } elseif ($v == 40) {
            $paymentName = '其他支付';
        } elseif ($v == 50) {
            $paymentName = '微信个人码';
        } elseif ($v == 60) {
            $paymentName = '支付宝个人码';
        } else {
            $paymentName = "";
        }

        return $paymentName;
    }

    /**
     * @desc:获取油站信息
     * @param $params
     * @return bool
     * @author: wuxy02
     * @date: 2020-05-22
     * @time: 17:15
     */
    public static function getMerchantList($merchant_ids)
    {
        $url = "/MC/merchant/merchant/index/";
        $params = [
            'merchant_id' => $merchant_ids,
            'merchant_type' => Constant::MERCHANT_TYPE_STATION,
            'limit' => 1000
        ];

        $res = WPApi::call($url, $params);
        if ($res['status'] != 200) {
            return false;
        }

        if (empty($res['data']['merchant_list'])) {
            return [];
        }

        return $res['data']['merchant_list'];
    }

    /**
     * @Desc: 获取活动统计导出文件sheetName
     * @Author: WenYaobo
     * @DateTime: 2020-09-24 14:01
     */
    public static function getActivitySheetName($title = '', $index = 0)
    {
        switch ($index) {
            case 0:
                $sheetName = $title . '活动统计';
                break;
            case 1:
                $sheetName = $title . '活动订单';
                break;
            case 2:
                $sheetName = $title . '中奖明细';
                break;
            default:
                $sheetName = 'sheet';
                break;
        }
        return $sheetName;
    }

    /**
     * @desc:获取订单数据
     * @param $codes
     * @param $params
     * @return bool
     * @author: wuxy02
     * @date: 2020-05-22
     * @time: 17:15
     */
    public static function getOrderDataByOrderCodes($order_codes, $merchant_ids, $start_time = '', $end_time = '', $order_status = '')
    {
        $url = "/JY/trade/order/query/";
        $params = [
            'merchant_type' => 1001,
            'merchant_id' => $merchant_ids,
            'query_type' => 10,
            "order_code" => $order_codes,
            "offset" => 0,
            "limit" => 1000,
            "has_items" => 1
        ];

        if (!empty($start_time)) {
            $params['begin_time'] = $start_time;
        }

        if (!empty($end_time)) {
            $params['end_time'] = $end_time;
        }

        if (!empty($order_status)) {
            $params['order_status'] = $order_status;
        }

        $res = WPApi::call($url, $params);
        if ($res['status'] != 200) {
            \Logger::error("查询订单失败：" . $res['info']);
            return false;
        }

        return $res['data']['order_list'];
    }


}
