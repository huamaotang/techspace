## 平滑重启
- signal.Notify 将输入信号转发到chan通道，接收chan os.signal阻塞
- 处理信号：将父进程传递给子进程，net.FileListener(os.NewFile(3, ''))，exec.Command()执行可执行文件，获取文件描述符，并赋值给cmd.ExtraFiles成员变量，执行cmd.Start；此时子进程和父进程同时启动，新的请求走子进程
- 关闭父进程server.Shutdown()，优雅关闭
