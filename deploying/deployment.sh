#!/bin/bash
ford(){
  ssh -tt $leave "echo "开始进行共享文件的判断" && cd $release && sudo chmod +x ./deploying/judge.sh && ./deploying/judge.sh ${sharedir}"
   if [ $? -ne 0 ]
     then
        echo "更新有错误"
        ssh $leave "rm -rf $release"
        exit 123
     fi
}
data_migration(){ # yii和yii2数据迁移
     
   #ssh $leave "/usr/bin/php $release/protected/yiic.php migrate up --interactive=0"
   ssh $leave "/usr/bin/php $release/yii migrate --migrationPath=@mdm/admin/migrations up --interactive=0"
   ssh $leave "/usr/bin/php $release/yii migrate --migrationPath=@yii/rbac/migrations up --interactive=0"
}
    
deploy_queen(){ # 同步远程主机后(保留十个版本)
   data_migration
   if [ $? -ne 0 ]
   then
     ssh $leave "rm -rf $release"
      hell_unlock
      exit 123
   fi
   ssh $leave "ln -snf $release $code_dir/current && ls -lt $code_dir"  #脚本核心
   ssh $leave "cd $release_dir && ls -t | awk 'NR==5{print}' | xargs -i rm -rf {} && ls -lt $release_dir"
   echo "恭喜部署成功"
}
rollback(){
  echo '开始回滚'
  ssh $leave "cd $release_dir && ls -t |awk 'NR==2{print}' | xargs -i ln -snf $release_dir/{} $code_dir/current"
  if [ $? -ne 0 ]
   then
    echo "回滚失败！"
    exit 123
  fi
  ssh $leave "cd $release_dir && ls -t |awk 'NR==1{print}' | xargs -i rm -rf {}"
  echo '回滚到上一个版本成功'

}