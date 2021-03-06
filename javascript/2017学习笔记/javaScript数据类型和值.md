# 数据类型和值
## 字符串
```
字符串直接量中的转义序列
\0   NULL字符串
\b   退格符
\t   水平制表符
\n   换行符
\v   垂直制表符
\f   换页符
\r   回车符
\"   双引号
\'   单引号或撇号
\\   反斜线符

字符串的使用
1、javascript的特性之一就是能够连接字符串
2、
    1) 字符串长度：s.length
    2) 字符串最后一个字符：s.charAt(s.length-1);
    3)抽出2、3、4个字符：s.substring(1,4);
    4)查找第一个字母“a”的位置：s.indexOf('a');

数字转化为字符串
1、添加一个空字符串：var str = n + "";
2、str = String(n);
3、str ＝ n.toString();
4、小数：
var a = 12234332.1111;
a.toFixed(0); //12234332
a.toFixed(2); //12234332.11

字符串转化为数字
1、'134234' - 0;（注意，不能加0，否则会字符串连接）
2、parseInt();

JavaScript的字符串就是用''或""括起来的字符表示。

转义字符\可以转义很多字符，比如\n表示换行，\t表示制表符，字符\本身也要转义，所以\\表示的字符就是\。

<script type="text/javascript">
	'use strict';
	var str = 'I\'m \"OK\"!';;
	var s_1 = `
	my 
	\tname
	is\n
	tanghuamao
	`;
	console.log(str);//I'm "OK"!
	console.log(s_1);
	/*
	my 
		name
	is

	tanghuamao*/
</script>

```
## 布尔值及类型转换
```
1、true false
2、Boolean();
```
## 函数
```
1、函数是一个可执行的Javascript代码段
2、是真正的数值
3、和数字、字符串一样的数据类型
```
## 对象
```
1、对象是已命名的数据集合。
如，image.heigth、image.width、document.write('aa')、image['heigth']等;
2、创建对象，
var o = new Object();
var now = new Date();
var obj = new object();
obj.x = 123;
obj.y = 'aa';
3、对象直接量
var obj = {x:123,y:'1'}
4、对象转换
toString()、valueOf()
```
## 数组
```
1、与对象一样，是数值的集合。
2、区别于对象，对象每个数值都是有名字的，数组每个数值有下标。
如，document.image[1].
       arr[1] = 11;
       arr[2] = {x:1,y:2}
    2) var arr = new Array(10);//创建指定量的数组
    3）var arr = new Array(1,2,3);
```
## NULL
```
1、特殊值，表示‘无值’。
2、布尔：false 数字：0 字符串：null
```
## undefined
```
1、使用未声明变量、使用已声明但没有赋值变量、使用不存在的对象属性，返回的就是这个值 
2、不同于null，通过===或者typeof区分 a.b == null 返回 true a.b === null 返回 false 
3、不是保留字
```
## Date对象
```
```
## Error对象
```
```

## 新特性
### strict：强制使用var声明变量
```
<script type="text/javascript">
	'use strict';
	i = 1;
	console.log(i);//ReferenceError: Can't find variable: i
</script>
```
## 实践操作
### 创建对象
```
<script type="text/javascript">
	var obj = new Object;
	var objExt = new Object();

	obj.name = 'tanghuamao';
	obj.age = 28;
	objExt.englishName = 'Tom';
	console.log(obj);//{name: "tanghuamao", age: 28}
	console.log(objExt);//{englishName: "Tom"}
</script>
```
