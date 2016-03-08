<?php
namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;    
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use Illuminate\Support\Facades\DB;

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
        //dd($info);
        //dd($root);
        $users = new User();
        if ($root) {
            $root_class = get_class($root);
            // 这里硬编码，待优化 @todo
            if ('App\Book' === $root_class) {
                $users = $users->leftJoin('user_book', 'users.id', '=', 'user_book.user_id')
                    ->where('user_book.book_id', $root->id);
            }
        }

        DB::enableQueryLog();
        if(isset($args['id'])) {
            $users = $users->where('id' , $args['id'])->get();
        }
        else if(isset($args['email'])) {
            $users = $users->where('email', $args['email'])->get();
        } else {
            $limit = isset($args['limit']) ? $args['limit'] : 2;
            $offset = isset($args['offset']) ? $args['offset'] : 0;

            $users = $users->offset($offset)->limit($limit)->get();
        }

        //$queries = DB::getQueryLog();
        //dd($queries);
        return $users;
    }

}
