package main

import (
	"fmt"
	"time"
)

func main() {
	msg := make(chan int, 10)
	done := make(chan bool)
	defer close(msg)

	go func() {
		ticker := time.NewTicker(1 * time.Second)
		for tk := range ticker.C {
			fmt.Println(time.Now(), tk)
			select {
			case <-done:
				fmt.Println("done")
				return
			case v := <-msg:
				fmt.Println("msg ", v)
			}
		}
	}()
	for i := 0; i < 10; i++ {
		msg <- i
	}
	time.Sleep(5 * time.Second)
	close(done)
	time.Sleep(1 * time.Second)
	fmt.Println("main quit.")
}
