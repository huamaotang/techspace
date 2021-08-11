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
