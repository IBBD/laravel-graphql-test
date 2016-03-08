<?php
namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Field;

class PictureField extends Field 
{

    protected $attributes = [
        'description' => 'A picture'
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'width' => [
                'type' => Type::int(),
                'description' => 'The width of the picture'
            ],
            'height' => [
                'type' => Type::int(),
                'description' => 'The height of the picture'
            ],
        ];
    }

    protected function resolve($root, $args)
    {
        //var_dump($args);
        $width = isset($args['width']) ? $args['width'] : 100;
        $height = isset($args['height']) ? $args['height'] : 100;
        return 'http://www.ibbd.net/' . $width . 'x' . $height;
    }

}
