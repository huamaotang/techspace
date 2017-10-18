# PHP命名空间
## 概念
```
1、英文namespace，对应命名空间。类似于Linux的文件命名，如：/usr/local/php/xx.conf
2、目的：
	1）用户编写的代码与PHP内部的类/函数/常量或第三方类/函数/常量之间的名字冲突。
	2）为很长的标识符名称创建一个别名（或简短）的名称，提高源代码的可读性。
3、PHP 5 >= 5.3.0,PHP 7
4、广义：一种封装事物的方法，属抽象概念
5、必须写在PHP文件的最开头，前面不能存在任何语句
6、只有类（包括抽象类、trait）、接口、常量、函数受命名空间影响
7、允许层次化的命名空间的名称
```

## class name 冲突
### file1.php 
```
<?php
class user {
    public function test(){
        echo 'test';
    }
}
```
### file2.php 
```
<?php
class user {
    public function test(){
        echo 'test';
    }
}
```
### run.php
```
<?php
require_once './file1.php';
require_once './file2.php';

$obj = new user();
//PHP Fatal error:  Cannot declare class user, because the name is already in use in /Users/huamaotang/workspace/www/code/php/demo/namespace/file2.php on line 2
```
## 解决重名
### file3.php 
```
<?php
namespace Family;
class user {
    public function test(){
        echo 'Family test' . PHP_EOL;
    }
}
```
### file4.php 
```
<?php
namespace School;
class user {
    public function test(){
        echo 'School test';
    }
}
```
### run.php
```
?php
require_once './file3.php';
require_once './file4.php';

$obj = new Family\user();
$obj->test();
$obj = new School\user();
$obj->test();
//Family test
//School test
```

## 声明单个命名空间

```
<?php
namespace School;
class user {
    public function test(){
        echo 'School test';
    }
}
```
## 声明分层次的单个命名空间
### file5.php
```
<?php
namespace China\Jiangxi\School;

const JIANGXI = '江西';

class User {

    const TEST = 'test';

    public function test(){
        echo 'School test';
    }
}
```
### run5.php
```
<?php
require './file5.php';

use China\Jiangxi\School\User;
echo China\Jiangxi\School\JIANGXI;
$obj = new User();
$obj->test();
echo $obj::TEST;
//江西School testtest
```
## 在同一个文件中定义多个命名空间
### file6.php
```
<?php
namespace China\Jiangxi\School;

const JIANGXI = '江西';

class User {

    const TEST = 'test';

    public function test(){
        echo 'School test';
    }
}

namespace China\Jiangxi\Family;

class User {

    const TEST = 'test';

    public function test(){
        echo 'Family test';
    }
}

function test() {
    echo 'namespace func testing';
}
```
### run6.php
```
<?php
require 'file6.php';

use China\Jiangxi\School\User;

$obj = new User();
$obj->test();

use China\Jiangxi\Family\User as FUser;

$obj1 = new FUser();
$obj1->test();
China\Jiangxi\Family\test();
//School testFamily testnamespace func testing
```
### 备注
```
由于User类名已经被使用，这里使用别名

use China\Jiangxi\Family\User as FUser;

PHP Fatal error:  Cannot use China\Jiangxi\Family\User as User because the name is already in use in /Users/huamaotang/workspace/www/code/php/demo/namespace/run6.php on line 9
```

## 命名空间内部元素的使用
### file7.php
```
<?php
namespace China\Jiangxi\School;
const DESC = '学校' . PHP_EOL;
class User {
    public static function test()
    {
        echo 'School Test' . PHP_EOL;
    }
}
function test()
{
    echo 'School func test' . PHP_EOL;
}
```
### file8.php
```
<?php
namespace China\Jiangxi;
include './file7.php';
const DESC = '家庭' . PHP_EOL;
function test()
{
    echo 'Family func test' . PHP_EOL;
}
class User {
    public static function test(){
        echo 'Family Test' . PHP_EOL;
    }
}

/* 非限定名称 */
test();
echo DESC;
User::test();

/* 限定名称 */
School\test();
echo School\DESC;
School\User::test();

/* 完全限定名称 */
\China\Jiangxi\test();
echo \China\Jiangxi\DESC;
\China\Jiangxi\User::test();

/* 在命名空间内部调用全局类、方法、常量 */
echo \strlen('tanghuamao');
echo \INI_ALL;
$obj = new \Exception('xxxxx');
var_dump($obj);
```

## 命名空间和动态语言特征
### 动态访问元素 file10.php
```
<?php
class User {
    public static function test()
    {
        echo __METHOD__ . PHP_EOL;
    }
}
function test()
{
    echo __FUNCTION__ . PHP_EOL;
}
const NAME = '汤华茂';

$a = 'User';
$obj = new $a;
$obj->test();
$b = 'test';
$b();
$c = 'NAME';
echo constant($c);
```
### 动态访问命名空间元素 file11.php
```
<?php
namespace China;
class User {
    public static function test()
    {
        echo __METHOD__ . PHP_EOL;
    }
}
function test()
{
    echo __FUNCTION__ . PHP_EOL;
}
const NAME = '汤华茂ns';

include './file10.php';

$a = 'User';
$obj = new $a;
$obj->test();
$b = 'test';
$b();
$c = 'NAME';
echo constant($c);

$x = '\China\User';
$obj = new $x;
$obj->test();
$y = '\China\test';
$y();
echo constant('\China\NAME');
```

## namespace和\_\_NAMESPACE__关键字
### 使用__NAMESPACE\_\_动态创建名称
```
<?php
namespace China;
function get($className)
{
    $a = __NAMESPACE__ . '\\' . $className;
    return new $a;
}
```











