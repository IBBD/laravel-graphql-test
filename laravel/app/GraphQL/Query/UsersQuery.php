<?php
namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;    
use App\User;

class UsersQuery extends Query {

    protected $attributes = [
        'name' => 'Users query'
    ];

    public function type()
    {
        #return GraphQL::type('user');    # 这里的结果是一行记录
        return Type::listOf(GraphQL::type('user'));  # 这里的结果是一个数组
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'name' => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        //var_dump($args);
        if(isset($args['id']))
        {
            #return User::find($args['id']);
            return User::where('id' , $args['id'])->get();
        }
        else if(isset($args['email']))
        {
            return User::where('email', $args['email'])->get();
        }
        else
        {
            return User::all();
        }
    }

}
