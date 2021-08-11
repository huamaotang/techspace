#GRPC
- 功能强大的web服务框架

## 原理
- Protocol Buffer接口定义。生成Go方法、接口、swagger等文件
- 自定义类型、方法，与接口绑定
- 调用net.listen监听TCP连接，accept方法阻塞接收连接
- 处理每个请求，根据map执行相应的类型方法，返回