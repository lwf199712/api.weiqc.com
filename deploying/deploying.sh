#!/bin/bash
usage(){ # 使用帮助函数
    echo $"Usage: $0 [ deploy | rollback [ list | emergency | version ]"
}

writelog(){ # 写入日志的函数
    LOGINFO=$1 # 将参数作为日志输入
    echo "${CDATE} ${CTIME} : ${SEHLL_NAME} : ${LOGINFO}" >> ${SHELL_LOG}
}

# 锁函数
shell_lock(){
    touch deploy.lock
    echo "锁的建立"
}


hell_unlock(){
    rm -f deploy.lock
    echo "解锁完成"
}

composer(){ # composer代码更新依赖
     /usr/bin/php /usr/local/bin/composer install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader
     /usr/bin/php /usr/local/bin/composer install
     if [ $? -ne 0 ]
     then
        echo "更新有错误"
        hell_unlock
        exit 123
     fi
}

deploy_front(){ # 同步到远程主机前的操作

# 修改测试环境的入口文件
#pwd
#cd $local_dir/web
# cp index-test.php index.php
#cd $local_dir

ssh $leave "cd $release_dir && ls -t | head -1 | xargs -i cp -rf {} $release"

if [ $? -ne 0 ]
   then
      echo "cp原版本没有成功,请重新检查"
      ssh $leave "rm -rf $release"
      hell_unlock
      exit 123
 fi

}
deploy(){ # 同步远程主机
   echo "开始远程同步"
   rsync -azvtrP --delete ./* $leave:$release 1> /dev/null #2> /dev/null
   if [ $? -ne 0 ]
   then
      ssh $leave "rm -rf $release"
      hell_unlock
      exit 123
   fi

}