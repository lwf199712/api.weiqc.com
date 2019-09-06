### 接口说明
广点通后台登录接口

### 一、请求参数

请求地址环境	|HTTP请求地址|请求方式
---|---|---
正式环境	|http://apigdt.weiqc.so| POST
测试环境    |https://gdttest.wqc.so | POST

### 二、广点通后台登录接口
- 请求方式 : POST
- 请求地址 : xxx/login
- 请求参数 : 

名称 | 类型 | 是否必须 | 示例值 | 描述
---|---|---|--- |---
LoginForm[username]|string|是|开发中心|账号
LoginForm[password]|string|是|123456|密码
- 请求的参数如下：
```json
{
  "LoginForm[username]" : "开发中心",
  "LoginForm[password]": "123456"
}
```

- 响应的参数如下：

```json
{
    "messgae" : "登录成功",
    "code" : 200,
    "data" : "pp3ug1NX_Dlc0QL-2wrE9EHyehc2AjYNkWGw7dXy" 
}
```

```json
{
    "message": "登录失败",  
     "code" : 401,
    "data" : ""
}
```

