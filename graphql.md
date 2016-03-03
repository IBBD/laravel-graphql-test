# GraphQL的语句

## 查询

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







