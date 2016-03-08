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

    /**
     * @param object $root 上一级的查询结果
     * @param array $args 查询参数
     */
    public function resolve($root, $args)
    {
        //dump($root);
        //dump(get_class($root));
        $books = new Book();
        if ($root) {
            $root_class = get_class($root);
            //dump($root instanceof App\User);
            //dump($root_class);
            // 这里硬编码，待优化 @todo
            if ('App\User' === $root_class) {
                $books = $books->leftJoin('user_book', 'books.id', '=', 'user_book.book_id')
                    ->where('user_book.user_id', $root->id);
            }
        }
        if(isset($args['id'])) {
            return $books->where('id' , $args['id'])->get();
        }
        else if(isset($args['email'])) {
            return $books->where('email', $args['email'])->get();
        }

        $limit = isset($args['limit']) ? $args['limit'] : 2;
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        return $books->offset($offset)->limit($limit)->get();
    }

}
