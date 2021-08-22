# Laravel8

### [统一格式化返回 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Http/Controllers/WX/WXController.php#L30 "统一格式化返回")

### [异常的统一处理 :arrow_right:](https://github.com/liao123-git/Laravel8/tree/main/laravel/app/Exceptions "异常的统一处理")

- Handler.php
- `$dontReport`数组为不上报给错误日志的类名
- 在自己封装的错误处理器的构造函数中添加以下代码，就可以在`render`函数中使用`getMessage`或者`getCode`获取到对应的数据了
   ```php
       parent::__construct($message, $code);
   ```

### 服务层单例模式

不要频繁的实例化一个类，可能会影响到性能。所以需要用到[单例模式](https://github.com/liao123-git/Design_Pattern/tree/main/%E5%8D%95%E4%BE%8B%E6%A8%A1%E5%BC%8F "单例模式")
。

- [样例 :arrow_right:](https://github.com/liao123-git/Laravel8/tree/main/laravel/app/Servers "样例")
- [使用 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Http/Controllers/WX/AuthController.php#L34 "使用")

### 解决跨域问题

- 跨域访问
    - 默认情况下只有相同域名才能允许访问

### 登录接口

- [封装好的JWT :arrow_right:](https://github.com/tymondesigns/jwt-auth "JWT")
- [样例 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Http/Controllers/WX/AuthController.php#L47 "登录接口")

### JWT原理

- [大佬教程 :arrow_right:](https://www.ruanyifeng.com/blog/2018/07/json_web_token-tutorial.html "JSON Web Token 入门教程")
- 我们用的[`jwt-auth`](https://github.com/tymondesigns/jwt-auth "JWT")组件，在`JWT`的`Payload`处，跟官方规定相比多了一个字段`prv`
  ，是用来判断当前用户属于哪个模型
- [`jwt-auth`的配置](https://github.com/liao123-git/Laravel8/blob/main/laravel/config/jwt.php "jwt-auth的配置")
- `ttl`: 原始`token`的过期时间，单位为分钟
- `lock_subject`: 是否区分用户模型，是否生成`prv`
- `decrypt_cookies`: 是否加密。`laravel`默认加密，所以如果使用`cookies`传递，需要改成`true`

### [统一鉴权认证 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Http/Controllers/WX/WXController.php#L17 "统一鉴权认证")

### [模型数据格式转换 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Models/BaseModel.php#L17 "返回值全部改成驼峰写法")

### [参数验证 :arrow_right:](https://github.com/liao123-git/Laravel8/tree/main/laravel/app/Inputs "参数验证")

- [可用的验证规则 :arrow_right:](https://learnku.com/docs/laravel/8.x/validation/9374#189a36 "可用的验证规则")

### [参数过长问题 :arrow_right:](https://github.com/liao123-git/Laravel8/tree/main/laravel/app/Inputs "参数过长问题")

### IDE代码提示优化

- 如何让我们的`model`自动提示它的字段
    - 安装[`laravel-ide-helper`](https://github.com/barryvdh/laravel-ide-helper "laravel-ide-helper")
    - `php artisan ide-helper:models`
- 对于一些并不是直接调用`model`的变量，比如
  ```php
      $user = Auth::guard('wx')->user();
  ```
    - 我们可以在上面代码上面加上`/**`然后空格补全注释，在变量前面添加`model`类型
      ```php
          /** @var User $user */
          $user = Auth::guard('wx')->user();
      ```
- [样例 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Http/Controllers/WX/AuthController.php#L26 "样例")

### 软删除

- 先在模型中使用laravel自带的软删除
  ```php
      use SoftDeletes; 
  ```
- 找到使用的`SoftDeletes.php`和`SoftDeletingScope.php`复制到公共目录下，在这俩文件名字前加上`Boolean`,修改命名空间，再把上面那句改成随后改成`use BooleanSoftDeletes`
- 在`BooleanSoftDeletes.php`中找到`boot`方法，改成`bootBooleanSoftDeletes`，再把下面的new的文件改成`BooleanSoftDeletingScope`
- 去除`initializeSoftDeletes`方法
- 在`runSoftDelete`方法中找到
  ```php
    $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];
  ```
  改成
  ```php
    $columns = [$this->getDeletedAtColumn() => 1];
  ```
  我的做法是删除为1，未删除是0，你也可以改成其他的。再把下面那行也赋值成1
- 在`restore`方法中找到
  ```php
    $this->{$this->getDeletedAtColumn()} = null;
  ```
  改成
  ```php
    $this->{$this->getDeletedAtColumn()} = 0;
  ```
- 把`trashed`方法中的返回值改成
  ```php
    return $this->{$this->getDeletedAtColumn()} == 1;
  ```
- 把`getDeletedAtColumn`(获取软删除字段名)方法中的字段名改成你数据库中预留的字段名
- 把`BooleanSoftDeletingScope.php`中`apply`函数的内容改成
  ```php
    $builder->where($model->getQualifiedDeletedAtColumn(), '0');
  ```
- 把`extend`方法中更新的内容改成1
- 把`addRestore`方法中的返回值改成
  ```php
    return $builder->update([$builder->getModel()->getDeletedAtColumn() => 0]);
  ```
- 把`addWithoutTrashed`方法中的`whereNull`方法改成`where`方法，再往后面添加第二个参数`"0"`
- 把`addOnlyTrashed`方法进行相同步骤，参数改为`"1"`
- 这里要注意如果你数据里软删除字段存的是`enum`类型，那就把所有的`1`和`0`都加上引号，不然的话改不了
- 现在使用`delete`方法就是软删除了，如果想要硬删除就使用`forceDelete`

### 任务队列
- [官方文档 :arrow_right:](https://learnku.com/docs/laravel/8.x/queues/9398 "官方文档")
- [样例 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Jobs/OrderUnpaidTime.php "样例")

### 乐观锁
- [什么是乐观锁 :arrow_right:](https://www.jianshu.com/p/d2ac26ca6525 "什么是乐观锁")
- `getDirty`可以获取到该模型对象上还未被`save()`的值
- `getOriginal`可以获取到该模型对象上还未被`save()`的被修改之前的值
- [样例 :arrow_right:](https://github.com/liao123-git/Laravel8/blob/main/laravel/app/Models/BaseModel.php#L32 "样例")
  