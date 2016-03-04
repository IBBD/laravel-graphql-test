<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class BookType extends GraphQLType {

    protected $attributes = [
        'name' => 'Book',
        'description' => 'A book'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the book' 
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of book'
            ],
        ];
    }
}

