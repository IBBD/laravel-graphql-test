<?php
namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;    
use App\Book;

class BooksQuery extends Query {

    protected $attributes = [
        'name' => 'Books query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('book'));  # 这里的结果是一个数组
    }

    public function args()
    {
        return [
            // 分页变量
            'offset' => ['name' => 'offset', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::int()],

            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        if(isset($args['id'])) {
            return Book::where('id' , $args['id'])->get();
        }
        else if(isset($args['email'])) {
            return Book::where('email', $args['email'])->get();
        }

        $limit = isset($args['limit']) ? $args['limit'] : 2;
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        return Book::offset($offset)->limit($limit)->get();
    }

}
