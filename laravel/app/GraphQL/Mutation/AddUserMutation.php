<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;    
use App\User;


class AddUserMutation extends Mutation {

    protected $attributes = [
        'name' => 'AddUser'
    ];

    public function type()
    {
        return GraphQL::type('user');
    }

    public function args()
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'password' => ['name' => 'password', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = new User();
        if ($user->where('email', $args['email'])->count()) {
            throw new \Exception('user where email=' . $args['email'] . ' is existed!');
        }

        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = $args['password'];
        $user->save();

        return $user;
    }

}

