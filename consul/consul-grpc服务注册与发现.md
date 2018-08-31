# consul-grpc服务注册与发现实现

## consul

### 为什么要用服务注册与发现
```
假设我们写的代码会调用 REST API 或者 Thrift API 的服务。为了完成一次请求，代码
需要知道服务实例的网络位置（IP 地址和端口）。运行在物理硬件上的传统应用中，服务实
例的网络位置是相对固定的；代码能从一个偶尔更新的配置文件中读取网络位置。

对于基于云端的、现代化的微服务应用而言，这却是一大难题

服务实例的网络位置都是动态分配的。由于扩展、失败和升级，服务实例会经常动态改变，因
此，客户端代码需要使用更加复杂的服务发现机制。
```

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

### 术语
```
Agent / 代理
agent是在consul集群中的每个成员上长时间运行的后台进程。通过执行"consul 
agent"启动。agent可以运行在客户端或者服务端模式。由于所有节点必须运行agent，和
节点关联就更容易了，不管节点是客户端还是服务器(不过还有agent的其他实例)。所有
agent可以运行 DNS 或者 HTTP 接口，并负责运行检查和保持服务同步。

Client / 客户端
client是转发所有RPC请求到服务器的agent。client相对而言是无状态的。client进行
的唯一活动是参与LAN gossip 池。这只需要极轻微的资源并只消耗少量的网络带宽。

Server / 服务器
server是一个职责扩展的agent，包括参与Raft团队，维持集群状态，响应RPC查询，和其
他数据中心交互WAN gossip和转发请求到leader或者远程数据中心。

Datacenter / 数据中心
虽然数据中心的定义看上去很明显，依然还是有些细节必须考虑。例如，在EC2中，多个可到
达的zone是否考虑组成一个单一的数据中心？我们定义数据中心为这样的网络环境：私有，低
延迟，高带宽。这排除了跨越公共英特网的通讯，但是，在我们看来，在单个EC2 区域中的多
个可到达zone可以考虑为单个数据中心的一部分。

Consensus / 一致性
在我们的文档中，使用 consensus /一致性 来表示不仅有对被选举的leader的认可，而且
有对事务顺序的认可。由于这些事务被应用到 有限状态机，我们的consensus定义暗示被复
制状态机的一致性。在 Wikipedia 上有Consensus的更多详细内容，而我们的实现在 这里 描述。

Gossip
Consul构建在Serf之上，Serf提供完整的用于多个用途的 gossip 协议. Serf 提供成员
关系，失败检测，还有事件广播。我们对这些的使用在 gossip文档 中有更多描述。这足以
了解到gossip包含了随机节点到节点(node-to-node)通讯, 首选UDP。

LAN Gossip / 局域网 Gossip
和局域网gossip池相关，包含在同一个局域网或者数据中心中的所有节点。

WAN Gossip / 广域网 Gossip
和广域网gossip池相关，仅仅包含服务器。这些服务器部署在不同的数据中心并且通常在英特网或者广域网上通讯。

RPC / 远程程序调用
远程程序调用(Remote Procedure Call). 这是一个请求/应答机制，容许客户端产生服务器请求.

```


### 总体架构
![consul架构图](https://raw.githubusercontent.com/huamaotang/techspace/master/images/consul.png)


### 内部架构
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

DNS API
dig @127.0.0.1 -p 8600 web.service.consul
dig @127.0.0.1 -p 8600 web.service.consul SRV

HTTP API
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
GRPC是Google公司基于Protobuf开发的跨语言的开源RPC框架。GRPC基于HTTP/2协议设计，可以基于一个HTTP/2链接提供多个服务。带来诸如双向流、流控、头部压缩、单 TCP 连接上的多复用请求等特。这些特性使得其在移动设备上表现更好，更省电和节省空间占用。

gRPC是一个高性能、开源和通用的 RPC 框架，面向移动和 HTTP/2 设计。目前提供C、
Java 和 Go 语言版本，分别是：grpc, grpc-java, grpc-go.其中 C 版本支持 C,
C++, Node.js, Python, Ruby, Objective-C, PHP 和 C# 支持.

```

### Protobuf
```
Protobuf是Protocol Buffers的简称，它是Google公司开发的一种数据描述语言，并于2008年对外开源。Protobuf刚开源时的定位类似于XML、JSON等数据描述语言，通过附带工
具生成代码并实现将结构化数据序列化的功能。但是我们更关注的是Protobuf作为接口规范的描述语言，可以作为设计安全的跨语言PRC接口的基础工具。
```

### 执行命令

```
protoc --go_out=plugins=grpc:. hello.proto
```

### rest接口
![](https://raw.githubusercontent.com/huamaotang/techspace/master/images/ch4.6-1-grpc-gateway.png)




### 参考链接
[consul操作入门](https://segmentfault.com/a/1190000005005227)

[consul指南](https://book-consul-guide.vnzmi.com/11_consul_template.html)

[consul架构](https://skyao.gitbooks.io/learning-consul/content/docs/internals/architecture.html)

[Go语言高级编程](https://chai2010.gitbooks.io/advanced-go-programming-book/content/ch4-rpc/ch4-02-pb-intro.html)

[consul与etcd、istio等比较](https://www.consul.io/intro/vs/index.html)

[grpc官方中文文档](http://doc.oschina.net/grpc?t=60136)

##待跟进
```
1、consul 集群搭建
2、
```

