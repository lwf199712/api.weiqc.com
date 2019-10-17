###RBAC迁移
```
php yii migrate --migrationPath=@mdm/admin/migrations
php yii migrate --migrationPath=@yii/rbac/migrations
```

###新建用户及权限原则
用户：姓名

角色：职位

权限：接口或接口集合


### Unit Testing

php vendor/bin/codecept run unit 

