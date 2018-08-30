# consul-grpc服务注册与发现
## 总体架构
![consul架构图](https://raw.githubusercontent.com/huamaotang/techspace/master/images/consul.png)

## consul
### 关键特性
```
服务调用: client 直连 server 调用服务

服务注册: 服务端将服务的信息注册到 consul 里

服务发现: 客户端从 consul 里发现服务信息，主要是服务的地址

健康检查: consul 检查服务器的健康状态

Key/Value存储：应用程序可用根据自己的需要使用Consul的层级的Key/Value存储，简单的HTTP API让他更易于使用.

多数据中心: 支持开箱即用的多数据中心.这意味着用户不需要担心需要建立额外的抽象层让业务扩展到多个区域.

面向DevOps和应用开发者友好：使它适合现代的弹性的基础设施
```
### 基础架构
```
Consul是一个分布式高可用的系统.
使用基于 Serf 实现的 gossip 协议来管理从属关系，失败检测，事件广播等。
gossip 协议是一个神奇的一致性协议

```
![consul](https://raw.githubusercontent.com/huamaotang/techspace/master/images/consul2.png)

### 常用命令
```
consul
consul agent -dev -node consul.test
consul members
curl localhost:8500/v1/catalog/nodes

mkdir /usr/local/etc/consul.d
echo '{"service": {"name": "web", "tags": ["rails"], "port": 80}}'  > /usr/local/etc/consul.d/web.json
consul agent -dev -node consul.test -config-dir /usr/local/etc/consul.d
dig @127.0.0.1 -p 8600 web.service.consul
dig @127.0.0.1 -p 8600 web.service.consul SRV
curl http://localhost:8500/v1/catalog/service/web
curl 'http://localhost:8500/v1/health/service/web?passing'
curl http://localhost:8500/v1/health/state/passing

curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/key1
curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/key2\?flags\=43
curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/sub/key3
curl -v http://localhost:8500/v1/kv/\?recurse
curl -X DELETE http://localhost:8500/v1/kv/web\?recurse
curl -X PUT -d 'newval' http://localhost:8500/v1/kv/web/key1\?cas\=97
curl "http://localhost:8500/v1/kv/web/key1?index=210&wait=5s"
```

### UI界面
```
http://localhost:8500

```


## grpc
### 概念
```
gRPC  是一个高性能、开源和通用的 RPC 框架，面向移动和 HTTP/2 设计。目前提供C、
Java 和 Go 语言版本，分别是：grpc, grpc-java, grpc-go.其中 C 版本支持 C,
C++, Node.js, Python, Ruby, Objective-C, PHP 和 C# 支持.

gRPC 基于 HTTP/2 标准设计，带来诸如双向流、流控、头部压缩、单 TCP 连接上的多复
用请求等特。这些特性使得其在移动设备上表现更好，更省电和节省空间占用。
```

### Protobuf
```
Protobuf是Protocol Buffers的简称，它是Google公司开发的一种数据描述语言，并于2008年对外开源。Protobuf刚开源时的定位类似于XML、JSON等数据描述语言，通过附带工
具生成代码并实现将结构化数据序列化的功能。但是我们更关注的是Protobuf作为接口规范的描述语言，可以作为设计安全的跨语言PRC接口的基础工具。
```
### rest接口
![]()


### 参考链接
[consul操作入门](https://segmentfault.com/a/1190000005005227)

[consul指南](https://book-consul-guide.vnzmi.com/11_consul_template.html)

[Go语言高级编程](https://chai2010.gitbooks.io/advanced-go-programming-book/content/ch4-rpc/ch4-02-pb-intro.html)

[consul与etcd、istio等比较](https://www.consul.io/intro/vs/index.html)

[grpc官方中文文档](http://doc.oschina.net/grpc?t=60136)
