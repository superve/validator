# Particle\Validator( 粒子 验证器)
> Particle\Validator 是一个小巧优雅的验证类库，提供了一个非常简洁的API

[官网](http://validator.particle-php.com/en/latest/)

# 快速入门使用 
```php
use Particle\Validator\Validator;

$validator = new Validator;
$validator->required('first_name')->lengthBetween(2, 30)->alpha();
$validator->required('last_name')->lengthBetween(2, 40)->alpha();

$validator->overwriteMessages([
    'last_name' => [
        LengthBetween::TOO_LONG => 'Your name is too long.'
    ]
]);

$data = [
    'first_name' => 'John',
    'last_name' => 'Doe',
];

$result = $validator->validate($data);
$result->isValid(); // bool(true)
```

# 为什么要使用 Particle\Validator？
> 当你合理使用的时候，使得项目开发更加的容易，主要特点如下：

1. 简洁的API
2. IDE的提示支持
3. 友好的文档
4. 利于扩展
5. 无依赖性

#  安装

## composer安装
```php
> php composer.phar require particle/validator
```



## 使用
> 注意：在使用之前请确保在项目中引入了 vendor/autoload.php 文件

```php
use Particle\Validator\Validator;

$validator = new Validator;
$validator->required('first_name')->lengthBetween(0, 20);
```
$validator->optional('age')->integer();


#  Required and optional
> 这个方法是内置的，主要用于检测某个key的值，如果希望检测的某个值可能为空，而希望验证通过，则我们可以设置该方法的第三个参数为true。（默认值为false 代表不能为空值）。其中 required 和 optional 的区别主要是如下 required 是验证的值必须存在；而 optional 是可选的，如果key存在，则验证，不存在，则不用验证。

## 常见的验证规则
```php
//第一个参数代表是验证的key
$v->required('foo')->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // false, because allowEmpty is false by default.


//第一个参数代表是验证的key 第二个参数代表是验证提示信息的key值，第三个参数代表是否允许空值
$v->required('foo', 'foo', true); // third parameter is "allowEmpty".
$v->validate(['foo' => ''])->isValid(); // true, because allowEmpty is true.


// option是可选的，现在存在，则必须验证，同时 allowEmpty 默认是false则代表不能为空，则验证不能通过
$v->optional('foo')->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // false, because allowEmpty is false and the key exists.


// option是可选的，现在foo不存在，则不是必须验证
$v->optional('foo')->lengthBetween(20, 100);
$v->validate([])->isValid(); // true, because the optional key is not present.

 option是可选的，现在foo存在，则必须验证，同时 allowEmpty可以为空
$v->optional('foo', 'foo', true)->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // true, because allowEmpty is true.

```


# 数组方式验证

```php
// 基本验证
$values = [
    'user' => [
        'username' => 'bob', 
    ]
];

$v = new Validator;
$v->required('user.username')->alpha();

$result = $v->validate($values);
$result->getValues() === $values; // bool(true)

```

```php
// 遍历验证
$values = [
    'invoices' => [
        [
            'id' => 1, 
            'date' => '2015-10-28',
            'amount' => 2500
            'lines' => [
                [
                    'amount' => 500,
                    'description' => 'First line',
                ],
                [
                    'amount' => 2000,
                    'description' => 'Second line',
                ],
            ],
        ],
        [
            'id' => 2, 
            'date' => '2015-11-28',
            'amount' => 2000
            'lines' => [
                [
                    'amount' => 2000,
                    'description' => 'Second line of second invoice',
                ],
            ],
        ],
    ],
];

$v = new Validator();

$v->required('invoices')->each(function (Validator $validator) {
    $validator->required('id')->integer();
    $validator->required('amount')->integer();
    $validator->required('date')->datetime('Y-m-d');

    $validator->each('lines', function (Validator $validator) {
         $validator->required('amount')->integer();
         $validator->required('description')->lengthBetween(0, 100);
    });
});

$result = $v->validate($values);
$result->isValid(); // bool(true)
$result->getValues() === $values; // bool(true)

```

# 内置验证规则

+ allowEmpty(callable $callback) 是否可以为空值，true则通过 反之亦然
```php
$v = new Validator;
// 如果用户名存在，则验证通过
$v->required('name')->allowEmpty(function (array $values) {
    return $values['namePresent'] === true;
});
$v->validate(['namePresent' => true, 'name' => 'John'])->isValid(); // true
$v->validate(['namePresent' => true])->isValid(); // true
$v->validate(['namePresent' => false])->isValid(); // false

```


+ alnum($allowWhitespace = false)  (a-z, A-Z, 0-9)

+ alpha($allowWhitespace = false) 验证的字符包含  (a-z, A-Z)

+ between($min, $max) 验证必须在一个范围 (12, 34)

+ bool() Boolean值验证

+ callback(callable $callable) 回调验证

+ creditCard()  验证之前必须先安装 composer require byrokrat/checkdigit

+ datetime($format = null) 日期验证

+ digits() 一串数字字符串验证，不包含小数

+ each(callable $callable) 遍历验证

+ email() 邮箱

+ equals($value) 相等

+ float() 浮点数

+ greaterThan($value) 大于某个值

+ hash($hashAlgorithm, $allowUppercase = false) md5 sha1等验证


+ inArray(array $array, $strict = true) 数组范围验证

+ integer($strict = false) 整数验证

+ isArray() 数组验证

+ json() json格式字符串验证

+ length($length) 长度验证

+ lengthBetween($min, $max) 长度范围

+ lessThan($value) 小于

+ numeric() 浮点数和整数

+ phone($countryCode) 手机号 使用之前先安装 composer require giggsey/libphonenumber-for-php

+ regex($regex) 正则验证

+ required(callable $callback) 必须存在
```php
$v = new Validator;
$v->optional('name')->required(function (array $values) {
	// 根据返回值来判断是否是必须验证
    return $values['forceName'] === true;
});
$v->validate(['forceName' => true, 'name' => 'John'])->isValid(); // true 返回值为true，必须验证 同时 name存在

$v->validate(['forceName' => true])->isValid(); // false 返回值为 true 但是name不存在
 

$v->validate(['forceName' => false])->isValid(); // true

```

+ string() 字符串

+ url($schemes = []) URL

+ uuid($version = Uuid::VALID_FORMAT) 


# 提示信息覆盖

```php
$v = new Validator;
$v->required('first_name')->lengthBetween(0, 5);
$v->required('last_name')->lengthBetween(0, 5);

$v->overwriteDefaultMessages([
    LengthBetween::TOO_LONG => 'It\'s too long, that value'
]);

$v->overwriteMessages([
    'first_name' => [
        LengthBetween::TOO_LONG => 'First name is too long, mate'
    ]
]);

$result = $v->validate([
    'first_name' => 'this is too long',
    'last_name' => 'this is also too long',
]);


/**
 * Output:
 *
 *  [
 *     'first_name' => [
 *         LengthBetween::TOO_LONG => 'First name is too long, mate'
 *     ],
 *     'last_name' => [
 *         LengthBetween::TOO_LONG => 'It\'s too long, that value'
 *     ]
 * ];
 */
var_dump($result->getMessages());

```

# 验证场景

```php
$v = new Validator;
// 定义一个插入时候的验证规则
$v->context('insert', function(Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
});
// 定义一个更新时候的验证规则
$v->context('update', function(Validator $context) {
    $context->optional('first_name')->lengthBetween(2, 30);
});

$v->validate([], 'update')->isValid(); // bool(true)
$v->validate([], 'insert')->isValid(); // bool(false), because first_name is required.

```

# 验证器扩展
> 有的时候，当系统提供的验证不够使用的时候，则我们可以进行验证方法的扩展，需要按照如下的步骤进行：

1. 编写自己的验证器，继承系统的 Validator
```php
use Particle\Validator\Validator;

/**
 * @method MyChain required($key, $name = null, $allowEmpty = false)
 * @method MyChain optional($key, $name = null, $allowEmpty = true)
 */
class MyValidator extends Validator
{
    /**
     * {@inheritdoc}
     * @return MyChain
     */
    public function buildChain($key, $name, $required, $allowEmpty)
    {
        return new MyChain($key, $name, $required, $allowEmpty);
    }
}

```

2. 编写自己的 MyChain 继承系统的 Chain 

```php
use Particle\Validator\Chain;

class MyChain extends Chain
{
    /**
     * @return $this
     */
    public function grumpy($who = 'Grumpy Smurf')
    {
        return $this->addRule(new GrumpyRule($who));
    }
}

```

3. 编写自己的验证规则的类库

```php
use Particle\Validator\Rule;

class GrumpyRule extends Rule
{
    const WRONG = 'GrumpyRule::WRONG';

    protected $messageTemplates = [
        self::WRONG => '{{ who }} hates the value of "{{ name }}"',
    ];

    protected $who;

    public function __construct($who)
    {
        $this->who = $who;
    }

    public function validate($value)
    {
        if ($value !== null || $value === null) { // always true, so always grumpy!
            return $this->error(self::WRONG);
        }
        return true;
    }

    // these variables can be used in error messages.
    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters(), [
            'who' => $this->who,
        ]);
    }
}

```

4. 使用

```php
v = new MyValidator;
$v->required('foo')->grumpy('Silly sally');
$result = $v->validate(['foo' => true]);

// output: 'Silly Sally hates the value of "foo"'
echo $result->getMessages()['foo'][Grumpy::WRONG]; 


```

# 标准使用
> 很多时候，我们的项目都是进行分层开发的，例如常见的MVC，则我们可以在分层项目中调用该验证类，示例如下：
```php
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;

class MyEntity 
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function validate() {
        $v = new Validator;
        $v->required('id')->integer();

        return new $v->validate($this->values());
    }

    protected function values()
    {
        return [
            'id' => $this->id,
        ];
    }
}

// in a controller:
$entity = new Entity();
$entity->setId($this->getParam('id'));

$result = $entity->validate();

if (!$result->isValid()) {
    return $this->renderTemplate([
        'messages' => $result->getMessages() // or maybe even just pass in $result.
    ]);
}

```