<?php
namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;    
use App\User;

class DelUserMutation extends Mutation {

    protected $attributes = [
        'name' => 'DelUser'
    ];

    public function type()
    {
        return GraphQL::type('user');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::find($args['id']);
        if (!$user) {
            throw new \Exception('Can\'t find user where id=' . $args['id']);
        }

        $user->delete();

        return $user;
    }

}
