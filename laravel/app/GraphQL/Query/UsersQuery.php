<?php
namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;    
use GraphQL\Type\Definition\ResolveInfo;
use App\User;

class UsersQuery extends Query {

    protected $attributes = [
        'name' => 'Users query'
    ];

    public function type()
    {
        #return GraphQL::type('user');    # 这里的结果是一行记录
        //dd(Type::listOf(GraphQL::type('user')));  # 这里的结果是一个数组
        return Type::listOf(GraphQL::type('user'));  # 这里的结果是一个数组
    }

    public function args()
    {
        return [
            // 分页变量
            'offset' => [ 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            //'id_list' => ['name' => 'id_list', 'type' => Type::listOf[Type::int()]],

            'id' => ['name' => 'id', 'type' => Type::int()],
            //'email' => ['name' => 'email', 'type' => Type::string()],
            //'name' => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, ResolveInfo $info)
    {
        $fields = $info->getFieldSelection();
        //dd($fields);
        //dd($info);
        //dd($root);
        if(isset($args['id'])) {
            $users = User::where('id' , $args['id'])->get();
        }
        else if(isset($args['email'])) {
            $users = User::where('email', $args['email'])->get();
        } else {
            $limit = isset($args['limit']) ? $args['limit'] : 2;
            $offset = isset($args['offset']) ? $args['offset'] : 0;

            $users = new User();
            $users = $users->offset($offset)->limit($limit);
            if (isset($fields['books'])) {
                $users->with('books');
            }
            $users = $users->get();

            //echo "<pre>";
            //foreach ($users as $u) {
                //var_dump($u->books);
            //}
            //dd($users->books);
        }

        return $users;
    }

}
