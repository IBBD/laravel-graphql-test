# GraphQL的语句

## 查询

所有查询操作的语句，都以`query`开始。

### 查询一个表的所有数据

```
http://laravel.test.com/graphql?query=query+FetchUsers{users{id,email}}
```

对query参数格式化：

```
query FetchUsers{   # query是查询的标识，FetchUsers是自定义名字
    users{   # users是对应查询的名字，对应代码里的
        id,     # 字段
        email   # 字段
    }
}
```

返回的结果如下：

```json
{
    "data": {
        "users": [
            {"id":1,"email":"name1@ibbd.net"},
            {"id":2,"email":"name2@ibbd.net"},
            {"id":3,"email":"name3@ibbd.net"},
            {"id":4,"email":"test@ibbd.net"},
            {"id":5,"email":"test2@ibbd.net"}
        ]
    }
}
```

### 根据一个ID来查询数据

```
http://laravel.test.com/graphql?query=query+FetchUsersById{users(id:1){id,email}}
```

```json
{
    "data": {
        "users": [
            {"id":1,"email":"name1@ibbd.net"}
        ]
    }
}
```

注意这里返回的依然是一个数组。因为对应的UsersQuery的type定义如下：

```php
return Type::listOf(GraphQL::type('user'));
```

如果改成单一记录，可以修改如下：

```php
return GraphQL::type('user');
```

能否根据参数进行修改，这个待看。

### 分页查询数据

我们使用offset和limit这两个参数来对数据进行分页

```
http://laravel.test.com/graphql?query=query+FetchUsers{users(offset:1,limit:3){id,email}}
```

格式化如下：

```
query FetchUsers {
    # offset和limit的值会传入到resolve方法中，只要在args中定义好
    users(offset:1, limit:3) {
        id,
        email
    }
}
```

对应服务器的关键代码如下：


```php
class UsersQuery extends Query {

    public function args()
    {
        return [
            // 分页变量
            'offset' => ['name' => 'offset', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            ...
        ];
    }

    public function resolve($root, $args)
    {
        $limit = isset($args['limit']) ? $args['limit'] : 2;
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        return User::offset($offset)->limit($limit)->get();
    }
}
```

### 分页传参数

在分页查询时，传参数是这样`users(offset:1, limit:3)`，可以实现，不过不够灵活，这里可以引入变量：

```
http://laravel.test.com/graphql?query=query+FetchUsers($offset:Int){users(offset:$offset){id,email}}&params={%22offset%22:1}
```

通过一个`params`变量把offset参数传给查询语句，注意`params`这个参数必须写成标准的json格式，因为在php解释时的代码是：

```php
$params = json_decode($params, true);
```

如果不是标准的，将会得不到这个值。

### 关联多表查询


## 更改: mutation

### 增加

### 删除

### 修改

```
http://laravel.test.com/graphql?query=mutation{updateUserPassword(id:1,password:%22newpassword%22){id}}
```

上面的url是根据文档来的，但是这样请求会报错：

```json
{
    data: null,
    errors: [
        {
            message: "Syntax Error GraphQL request (1:9) Expected Name, found { 1: mutation{updateUserPassword(id:1,password:"newpassword"){id}} ^ ",
            locations: [
                {
                    line: 1,
                    column: 9
                }
            ]
        }
    ]
}
```

需要增加一个name：

```
http://laravel.test.com/graphql?query=mutation+update{updateUserPassword(id:1,password:%22newpassword%22){id}}
```

注意：
update执行的时候，会自动设置`updated_at`这个字段, 当然这个是框架的问题。








