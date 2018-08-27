# consul-grpc服务注册与发现
## 总体架构
![consul架构图](https://raw.githubusercontent.com/huamaotang/techspace/master/images/consul.png)

```
服务调用: client 直连 server 调用服务
服务注册: 服务端将服务的信息注册到 consul 里
服务发现: 客户端从 consul 里发现服务信息，主要是服务的地址
健康检查: consul 检查服务器的健康状态
```
## consul
### 关键特性
```
Consul包含多个组件,但是作为一个整体,为你的基础设施提供服务发现和服务配置的工具.
他提供以下关键特性:

服务发现 Consul的客户端可用提供一个服务,比如 api 或者mysql ,另外一些客户端可用使
用Consul去发现一个指定服务的提供者.通过DNS或者HTTP应用程序可用很容易的找到他所依赖
的服务.

健康检查 Consul客户端可用提供任意数量的健康检查,指定一个服务(比如:webserver是否返
回了200 OK 状态码)或者使用本地节点(比如:内存使用是否大于90%). 这个信息可由
operator用来监视集群的健康.被服务发现组件用来避免将流量发送到不健康的主机.

Key/Value存储 应用程序可用根据自己的需要使用Consul的层级的Key/Value存储.比如动态配置,功能标记,协调,领袖选举等等,简单的HTTP API让他更易于使用.

多数据中心: Consul支持开箱即用的多数据中心.这意味着用户不需要担心需要建立额外的抽象层让业务扩展到多个区域.

Consul面向DevOps和应用开发者友好.是他适合现代的弹性的基础设施.
```

### 基础架构
```
Consul是一个分布式高可用的系统.
每个提供服务给Consul的节点都运行了一个Consul agent . 
发现服务或者设置和获取 key/value存储的数据不是必须运行agent.这个agent是负责对节
点自身和节点上的服务进行健康检查的.


```



### 参考链接
```
https://book-consul-guide.vnzmi.com/01_what_is_consul.html

```
