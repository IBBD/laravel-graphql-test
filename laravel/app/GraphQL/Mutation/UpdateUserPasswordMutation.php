<?php
namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;    
use App\User;

class UpdateUserPasswordMutation extends Mutation {

    protected $attributes = [
        'name' => 'UpdateUserPassword'

    ];

    public function type()
    {
        return GraphQL::type('user');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
            'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())]
        ];
    }

    public function resolve($root, $args)
    {
        //dd($this);
        $user = User::find($args['id']);
        if (!$user) {
            throw new \Exception('Can\'t find user where id=' . $args['id']);
        }

        $user->password = $args['password'];
        $user->save();

        return $user;
    }

}
