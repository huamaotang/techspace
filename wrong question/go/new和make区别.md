## new和make区别

- new 为类型分配内存，make()为类型分配内存，且初始化类型
- make 初始化 slice、map、chan 类型，返回引用类型，new 不能进行初始化，返回指针类型



```go
package main

import (
	"fmt"
	"reflect"
)

type Animals struct {
	Name string
	Age  int
}

func main() {
	a := new(bool)
	fmt.Printf("%#v %s\n", a, reflect.TypeOf(a))
	b := new(Animals)
	fmt.Printf("%#v %s\n", b, reflect.TypeOf(b))
	c := Animals{}
	fmt.Printf("%#v\n", c)

	d := make([]int, 0, 1)
	f := make(map[int]string)
	g := map[int]string{1: "a"}
	var h map[int]string
	var i []int
	i = append(i, 1, 2)
	j := new([]int)
	//
	// h[2] = "b"
	fmt.Printf("%#v %#v %#v %#v %#v %#v\n", d, f, g, h, i, j)
}

```



```
(*bool)(0xc0000b4002) *bool
&main.Animals{Name:"", Age:0} *main.Animals
main.Animals{Name:"", Age:0}
[]int{} map[int]string{} map[int]string{1:"a"} map[int]string(nil) []int{1, 2} &[]int(nil)
```

