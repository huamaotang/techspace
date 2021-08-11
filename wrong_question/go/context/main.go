package main

import (
	"context"
	"fmt"
	"reflect"
)

func main() {
	fmt.Println(context.Background())
	fmt.Println(reflect.TypeOf(false).Name())
	ctx := context.Background()
	ctx = context.WithValue(ctx, "a", 1)
	ctx = context.WithValue(ctx, "b", 2)
	ctx = context.WithValue(ctx, 1, 100)
	fmt.Println(ctx.Value("a"))
	ch := make(chan int)
	go run(ctx, ch)
	fmt.Println(<-ch)
}

func run(ctx context.Context, ch chan int) {
	fmt.Println("run", ctx.Value("b"))
	fmt.Println("run", ctx.Value(1))
	go run1(ctx, ch)
}

func run1(ctx context.Context, ch chan<- int) {
	fmt.Println("run1", ctx.Value("a"))
	ch <- 1
}
