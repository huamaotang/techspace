## graceful restart

## 通过部署系统配合nginx
### 操作
- 前提：大部分业务系统是挂在nginx之后，通过nginx进行反向代理
- 需要重启某台机器的进程A时，先把该机器M1的IP先从nginx的upstream中摘除掉，等一分钟，进程A差不多处理完了所有请求
- 在进程A进入空闲时，kill掉进程A并重启，成功之后，将机器M1的IP重新加入到nginx对应的upstream中
### 缺陷
- 复杂、等待时间的未知性、其它机器的压力突增（可能导致整个服务不可用）

## FD继承（file descriptor）
### 知识点
- fd就是文件描述符，是Unix*系统上最常见的概念，everything is file
- 进程T fork出一个子进程时，子进程会继承父进程T打开的fd
```go
// 进程T处理流程
// bind、listen都是准备工作，父进程把这些工作已经做了
int sock_fd = createSocketBindTo(":80");
// 可以从环境变量或者args中判断是应该先listen还是直接用继承来的socket进行accept
int ok = listen(sock_fd, backlog);
do {
	// 想要accept到连接，我们只需要socket就够了；子进程可以直接从继承过来的socket上读取数据
  int connect_sock = accept(sock_fd, &SockStruct, &Addr);
  process(connect_sock);
}
// 问题：子进程和父进程同时在该socket上进行accept，并发安全吗？glibc保证了并发安全

```


```
· signal.Notify 将输入信号转发到chan通道，接收chan os.signal阻塞
· 处理信号：将父进程传递给子进程，net.FileListener(os.NewFile(3, ''))，exec.Command()执行可执行文件，
  获取文件描述符，并赋值给cmd.ExtraFiles成员变量，执行cmd.Start；此时子进程和父进程同时启动，新的请求走子进程
· 关闭父进程server.Shutdown()，优雅关闭
```