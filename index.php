<?php 

require './vendor/autoload.php';

use Particle\Validator\Validator;
use Particle\Validator\Rule\LengthBetween;

// $validator = new Validator;

$v = new Validator;
$v->optional('name')->required(function (array $values) {
	var_dump($values);
    return $values['forceName'] === true;
});
echo $v->validate(['forceName' => true, 'name' => 'John'])->isValid(); // true
echo "<hr/>";
echo $v->validate(['forceName' => true])->isValid(); // false
echo "<hr/>";
echo $v->validate(['forceName' => false, 'name' => 12])->isValid(); // true


// $validator->required('first_name')->lengthBetween(2, 30)->alpha();
// $validator->required('last_name', 'xxxxxx')->lengthBetween(2, 40)->alpha();

// $validator->overwriteMessages([
//     'last_name' => [
//         LengthBetween::TOO_LONG => '用户名长度不对.'
//     ]
// ]);

// $data = [
//     'first_name' => 'John',
//     'last_name' => 'Doe Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis recusandae, corrupti tenetur animi amet sit! Quia rerum rem, dicta tempora magni, quae sed cupiditate esse excepturi impedit suscipit provident cumque!',
// ];

// header('Content-Type:text/html;charset=utf-8');
// echo '<pre>';
// $result = $validator->validate($data);
// $result->isValid(); // bool(true)

// var_dump($result->getMessages());
