#!/bin/bash
# shell env 脚本位置等变量
SHELL_NAME="apitest.sh"    # 脚本名称
SHELL_DIR="/test"  # 脚本路径
SHELL_LOG="${SHELL_DIR}/${SHELL_NAME}.log" # 脚本执行日志文件路径
# 日志日期和时间变量
LOG_DATE=`date "+%Y-%m-%d"` # 如果执行的话后面执行的时间，此时间是不固定的，这是记录日志使用的时间
LOG_TIME=`date "+%H-%M-%S"`

# 代码打包时间变量
CDATE=$(date "+%Y-%m-%d") # 脚本一旦执行就会取一个固定时间赋值给变量，此时间是固定的
CTIME=$(date +"%H-%M-%S")

#项目及本地目录
deploy_name=api.weiqc.com
fix_name=apifixbug.weiqc.com
local_dir=/var/lib/jenkins/workspace/api-weiqc

#需要部署的远程主句
leave="www@120.27.137.99"

#远程主机的目录
code_dir=/data/www/$deploy_name/back-end
release_dir=/data/www/$deploy_name/back-end/releases
release=/data/www/$deploy_name/back-end/releases/$deploy_name-$LOG_DATE-$LOG_TIME

#远程主机的共享文件夹
sharedir=${code_dir}/shared/

sourcefunction(){
    source ./deploying/deploying.sh
    source ./deploying/deployment.sh
}
source200(){
    leave="www@192.168.1.200"
    sourcefunction
}
fixsource(){
    #fix环境声明
    code_dir=/data/www/$fix_name/back-end
    release_dir=/data/www/$fix_name/back-end/releases
    release=/data/www/$fix_name/back-end/releases/$fix_name-$LOG_DATE-$LOG_TIME
    sharedir=${code_dir}/shared/
    #生效fix环境方法
    sourcefunction
}
main(){
    DEPLOY_METHOD=$1 # 避免出错误将脚本的第一个参数作为变量
    ROLLBACK_VER=$2
    case $DEPLOY_METHOD in
        deploy) # 如果第一个参数是deploy就执行以下操作
             sourcefunction;
             shell_lock;# 建立锁
             composer; # 获取代码
             deploy_front; # 同步主机之前的操作
             deploy;   # 同步到服务器
             ford;
             deploy_queen; # 部署远程主机后保留十个版本
             hell_unlock; # 删除锁
            ;;
        fixdeploy) 
             fixsource;
             shell_lock;# 建立锁
             composer; # 获取代码
             deploy_front; # 同步主机之前的操作
             deploy;   # 同步到服务器
             ford;
             deploy_queen; # 部署远程主机后保留十个版本
             hell_unlock; # 删除锁
            ;;
        betadeploy) 
             source200;
             shell_lock;# 建立锁
             composer; # 获取代码
             deploy_front; # 同步主机之前的操作
             deploy;   # 同步到服务器
             ford;
             deploy_queen; # 部署远程主机后保留十个版本
             hell_unlock; # 删除锁
            ;;
        rollback) # 如果第一个参数是rollback就执行以下操作
            sourcefunction;
            rollback;
            ;;
        betarollback) 
            source200;
            rollback;
            ;;
        fixrollback)
            fixsource;
            rollback;
            ;;
        *)
            sourcefunction;
            usage;
            ;;
    esac
}
main $1 $2