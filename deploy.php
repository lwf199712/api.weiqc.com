<?php
namespace Deployer;

require 'recipe/yii2-app-basic.php';

// Project name
set('application', 'api.weiqc.com');

// Project repository
set('repository', 'git@gitlab.fandow.com:fandow/api.weiqc.com.git');

// [Optional] Allocate tty for git clone. Default value is false.
// Shared files/dirs between deploys
add('shared_files', [
    'config/db.php',
    'config/dbOA.php', // OA数据库配置文件
    'config/db_dc.php',
]);


add('shared_dirs', [
    'web/uploads',
    'web/temp',
]);

// Writable dirs by web server
add('writable_dirs', [
    'web/uploads',
    'web/uploads/temp',
]);

// Tasks

// Hosts 防真环境服务器配置
host('120.27.137.99')
    ->set('deploy_path', '/data/www/api.weiqc.com/back-end')
    ->set('branch', 'develop')
    ->stage('test')
    ->user('www');

// Hosts 正式环境服务器配置
host('114.55.90.66')
    ->set('deploy_path', '/data/www/api.weiqc.com/back-end')
    ->set('branch', 'master')
    ->stage('product')
    ->user('www');

//重新定义任务部署的顺序
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',

    //把项目入口文件，修改为生产环境
    'deploy:index',

    'deploy:vendors',
    // 防真环境安排所有依赖包
    'deploy:vendors-dev',

    //数据迁移
    'deploy:run_rbac_migrations',
    'deploy:run_migrations',
    //'deploy:run_workflow_migrations',

    //创建链接
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');


//测试环境，安装所有的composer 包
task('deploy:vendors-dev', static function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
    }
    run('cd {{release_path}} && {{bin/composer}} install');
})->onStage('test')->desc('deploy all packing for vendor');

//rbac权限数据迁移
// usr/bin/php yii migrate --migrationPath=@mdm/admin/migrations yii2-admin 权限数据表迁移
// usr/bin/php yii migrate --migrationPath=@yii/rbac/migrations  rbac 权限数据表迁移
task('deploy:run_rbac_migrations', static function () {
    run('{{bin/php}} {{release_path}}/yii migrate --migrationPath=@mdm/admin/migrations up --interactive=0');
    run('{{bin/php}} {{release_path}}/yii migrate --migrationPath=@yii/rbac/migrations up --interactive=0');
})->desc('migrations rbac');

//yii2-workflow 工作流程数据迁移
//task('deploy:run_workflow_migrations', static function () {
//    run('{{bin/php}} {{release_path}}/yii migrate --migrationPath=@cornernote/workflow/manager/migrations');
//})->desc('migrations workflow');

//修改生产环境的入口文件
task('deploy:index', static function () {
    run('cd {{release_path}}/web && cp index-prod.php index.php');
})->onStage('product')->desc('Deploy index for product');

//比较使用版本和正在发布的版本，如果vendor 包没有更新，则直接使用当前版本的 vendor包
task('deploy:vendors', static function () {
    //比较两个版本之间的 composer.lock 文件，如果不一致则去更新 vendor
    $currentPath = get('current_path');
    $releasePath = get('release_path');

    //比较两个版本之间的 vendor 包是否有改变
    $signCurrent = run('md5sum {{current_path}}' . '/composer.lock');
    $signRelease = run('md5sum {{release_path}}' . '/composer.lock');

    if (
        $currentPath !== $releasePath  //非首次发布
        && $signCurrent !== $signRelease  //版本之间没有变化
    ) {
        // 如果版本之间没有变化，直接 copy 上一个版本的vendor 包信息
        run('cp -r {{current_path}}/vendor {{release_path}}/');
    } else {
        //如果初次发布或者是有版本更新
        if (!commandExist('unzip')) {
            writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
        }
        run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
    }
})->desc('Installing vendors');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

