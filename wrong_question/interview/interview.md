
## grpc 


## golang基础知识

## mysql

## redis
### 主从复制、新增从服务器
### sentinel 模式
### 持久化
### nei





## es、kibana、Kafka

## etcd

## mq

## PHP

## nginx

## linux

## 架构、调优

## HTTP
- 超文本传输协议
- HTTP1.1 长连接，减少3次握手、4次挥手的次数

## 网络轮询器
select 多路复用
网络轮询器并不是由运行时中的某一个线程独立运行的，运行时的调度器和系统调用都会通过 runtime.netpoll 与网络轮询器交换消息，获取待执行的 Goroutine 列表，并将待执行的 Goroutine 加入运行队列等待处理。
所有的文件 I/O、网络 I/O 和计时器都是由网络轮询器管理的，它是 Go 语言运行时重要的组成部分。我们在本节中详细介绍了网络轮询器的设计与实现原理，相信各位读者对这个重要组件也有了比较深入的理解。




