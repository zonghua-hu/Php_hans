#!/bin/bash
#
#sh使用示例
#
#示例一,本地开发环境:        sh DownloadRestart.sh develop
#示例二,测试默认环境:			sh /data/wwwroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh test
#示例三,预发布默认环境:			sh /data/wwwroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh preview
#示例四,预发布preview2环境:	sh /data/wwwroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh preview preview2
#示例五,线上环境:				    sh /data/webroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh production
#示例五,线上环境Blue:				    sh /data/webroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh production_blue
#示例五,线上环境Green:				    sh /data/webroot/mp/mp-api/src/app_merchant_api/console/DownloadRestart.sh production_green

#本地开发环境需先设置,MPAPI_ROOT:mp-api项目在本地的路径
#
MPAPI_ROOT="/Users/wenyaobo/wwwroot/mp-api";

#脚本执行日志
LOG_PATH='/data/logs/shell';
LOG_FILE="${LOG_PATH}/download_sh.log";

if [ ! -d "$LOG_PATH" ]; then
	mkdir -pm 750 $LOG_PATH
fi

#if [ ! -f "$LOG_FILE" ]; then
#    touch $LOG_FILE;
#    chmod -f 750 $LOG_FILE;
#fi

############################################################

#获取下载服务的pid
function getpid (){
  pid='';
  if [[ $ENV == 'production'  ]]; then
	  pid=`ps -ef |grep -v grep |grep download |grep master | grep ${PROCESS} |awk '{print $2}'`
	elif [[ $ENV == 'production_blue' ]]; then
	  pid=`ps -ef |grep -v grep |grep download |grep master | grep -w ${PROCESS} |awk '{print $2}'`
	elif [[ $ENV == 'production_green' ]]; then
      	  pid=`ps -ef |grep -v grep |grep download |grep master | grep -w ${PROCESS} |awk '{print $2}'`
	elif [[ $ENV == 'preview' ]]; then
	  pid=`ps -ef |grep -v grep |grep download |grep master | grep -w ${PROCESS} |awk '{print $2}'`
  elif [[ $ENV == 'develop' ]]; then
	  pid=`ps -ef |grep -v grep |grep download.php| awk '$3 == 1 {print $2}'`
  fi
}

#启动脚本
function start () {
  # 根据不同的环境变量切换脚本目录
  if [[ $ENV == 'production'  ]]; then
    FILE="/data/webroot/mp/mp-api/src/app_merchant_api/console/download.php --ENVIRON=production";
    php $FILE;
  elif [[ $ENV == 'production_blue' ]]; then
    FILE="/data/wwwroot${ENV_DIR}/mp/mp-api/src/app_merchant_api/console/download.php --ENVIRON=production_blue ${ENVIRON1}";
    php $FILE;
  elif [[ $ENV == 'production_green' ]]; then
      FILE="/data/wwwroot${ENV_DIR}/mp/mp-api/src/app_merchant_api/console/download.php --ENVIRON=production_green ${ENVIRON1}";
      php $FILE;
  elif [[ $ENV == 'preview' ]]; then
    FILE="/data/wwwroot${ENV_DIR}/mp/mp-api/src/app_merchant_api/console/download.php --ENVIRON=preview ${ENVIRON1}";
    php $FILE;
  elif [[ $ENV == 'develop' ]]; then
    FILE="${MPAPI_ROOT}/src/app_merchant_api/console/download.php --ENVIRON=develop";
    php $FILE;
  fi
}

#杀死进程
function stop (){
  kill $pid;
}

#输出错误信息,并记录
function log_error() {
  echo -e $MSG;
  if [[ $ENV != 'develop'  ]]; then
      echo -e $MSG >> $LOG_FILE;
  fi
}

############################################################

ENV=$1;
ENV1=$2;

MSG="
$(date '+%Y-%m-%d %H:%M:%S') =============== 开始执行Shell脚本 ENVIRON=${ENV} ENVIRON1=${ENV1} ==========
";
log_error;

#if [[ $ENV == '' ]]; then
#    ERR_MSG="请输入环境变量（develop、test、preview、production）!!!";
#    echo "";
#    echo $ERR_MSG;
#    echo "示例一(默认端口):sh download_restart.sh preview";
#	  echo "示例二(preview2环境):sh download_restart.sh preview preview2";
#	  echo "";
#    echo $(date '+%Y-%m-%d %H:%M:%S') "==== 执行失败: ${ERR_MSG}" >> $LOG_FILE;
#    exit 0;
#fi

# 根据不同的环境变量切换脚本目录
if [[ $ENV == '' || ( $ENV != 'develop' && $ENV != 'test' && $ENV != 'preview' && $ENV != 'production' && $ENV != 'production_blue'  && $ENV != 'production_green' ) ]]; then
  MSG="
  环境变量有误（develop/test/preview/production)\n
  示例一(默认端口):sh download_restart.sh preview\n
  示例二(preview2环境):sh download_restart.sh preview preview2\n
  $(date '+%Y-%m-%d %H:%M:%S') ==== 执行失败:环境变量有误
  ";
  log_error;
  exit 0;
fi

#if [[ $ENV == "test" ]]; then
#	echo "";
#	ERR_MSG="test(Docker)环境请使用其他方式!!!";
# 	echo $ERR_MSG;
# 	echo $ERR_MSG;
# 	echo $ERR_MSG;
# 	echo "";
#  echo $(date '+%Y-%m-%d %H:%M:%S') "==== 执行失败:${ERR_MSG}" >> $LOG_FILE;
#  exit 0;
#fi

#非默认端口--目录
ENV1_DIR='';
#非默认端口--环境变量1
ENVIRON1='';
#进程名
PROCESS=$ENV;

if [[ $ENV1 != '' ]]; then
    ENV_DIR="/${ENV1}";
    ENVIRON1=" --ENVIRON1=${ENV1}";
    PROCESS=$ENV1;
fi

#如果是test环境
if [[ $ENV == 'test' ]]; then
    #执行脚本
    sudo docker exec php /bin/bash -c "sh /data/wwwroot/restart/download/docker_download_restart.sh '$PROCESS' ";
    MSG="$(date '+%Y-%m-%d %H:%M:%S') 执行结束 \n"
    exit 0;
fi
#结束

pid='';
getpid;

#启动或重启服务
if [ -n "$pid" ];then
	MSG="
	$(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 正在运行,PID(${pid})\n
	$(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 正在停止...
	";
	log_error;
  stop;

  #判断是否已完全停止
  RETRY_TIMES=5;
  i=0;
  while ((i < RETRY_TIMES))
  do
      MSG="
      $(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 检查进程状态...
      ";
      log_error;

      sleep 1;
      getpid;
      if [ ! -n "$pid" ];then
          break;
      fi
      ((i++))
  done

	if [ -n "$pid" ];then
		MSG="
		$(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 停止服务失败,请重试!!!\n
    $(date '+%Y-%m-%d %H:%M:%S') =============== 执行失败101 ====================
    ";
    log_error;
		exit 0;
	else
	    MSG="
	    $(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 已停止
	    ";
	    log_error;
	fi

else
    MSG="
    $(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 当前未运行
    ";
    log_error;
fi

#启动服务
start;
MSG="
  $(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 启动中...\n
  $(date '+%Y-%m-%d %H:%M:%S') Download服务:${FILE}
";
log_error;
sleep 1;

#获取目前服务信息
getpid
#判断是否已启动
if [ -n "$pid" ];then
	MSG="
	$(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 已启动,PID(${pid})\n
  $(date '+%Y-%m-%d %H:%M:%S') =============== 成功启动服务 ====================\n
  ";
	log_error;

	#输出当前进程信息
	ps -ef |grep -v grep |grep download.php;

else
	  MSG="
	  $(date '+%Y-%m-%d %H:%M:%S') Download服务:${PROCESS} 启动失败,请重试!!!\n
    $(date '+%Y-%m-%d %H:%M:%S') =============== 执行失败102 ====================
    ";
    log_error;
fi

exit 0;


