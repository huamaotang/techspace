package main

import "fmt"

func main() {
	defer func() {
		if err := recover(); err != nil {
			fmt.Println("recover.")
		}
	}()
	fmt.Println("a")
	run()
}

func run() {
	panic("panic.")
}
