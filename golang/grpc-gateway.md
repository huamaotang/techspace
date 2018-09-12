# grpc-gateway + gorose 实现

## 概念
```
GRPC服务一般用于集群内部通信，如果需要对外暴露服务一般会提供等价的REST接口。通过
REST接口比较方便前端JavaScript和后端交互。开源社区中的grpc-gateway项目就实现
了将GRPC服务转为REST服务的能力。

通过在Protobuf文件中添加路由相关的元信息，通过自定义的代码插件生成路由相关的处理
代码，最终将REST请求转给更后端的GRPC服务处理。

```

## 安装
```
安装包
go get -u github.com/grpc-ecosystem/grpc-gateway/

grpc-gateway代码生成器
protoc-gen-grpc-gateway


```

## 定义路由扩展元信息

```

syntax = "proto3";

package helloworld;

import "google/api/annotations.proto";

// The request message containing the user's name.
message HelloRequest {
  string name = 1;
}

// The response message containing the greetings
message HelloReply {
  string message = 1;
}

message SetAgeRequest {
    int32 uid = 1;
}

message SetAgeReply {
    string message = 1;
}


// The greeting service definition.
service Greeter {
  // Sends a greeting
  rpc SayHello (HelloRequest) returns (HelloReply) {
    option (google.api.http) = {
            get: "/get/{name}"
          };
  }

  rpc SetAge (SetAgeRequest) returns (SetAgeReply) {
    option (google.api.http) = {
                post: "/post"
                body: "*"
              };
  }
}

```

## 执行生成器

```
设置目录
export GOPATH=/Users/huamaotang/go

生成路由元信息
protoc -I/usr/local/include -I. -I$GOPATH/src -I$GOPATH/src/
github.com/grpc-ecosystem/grpc-gateway/third_party/googleapis --grpc-
gateway_out=. helloworld.proto

生成grpc代码
protoc -I/usr/local/include -I. -I$GOPATH/src -I$GOPATH/src/
github.com/grpc-ecosystem/grpc-gateway/third_party/googleapis --
go_out=plugins=grpc:. helloworld.proto
```

## 测试代码
### gateway
```
package main

import (
	"github.com/grpc-ecosystem/grpc-gateway/runtime"
	"google.golang.org/grpc"
	"log"
	"net/http"
	"context"
	"lib/helloworld"
)

func main() {
	ctx := context.Background()
	ctx, cancel := context.WithCancel(ctx)
	defer cancel()

	mux := runtime.NewServeMux()

	opts := []grpc.DialOption{grpc.WithInsecure()}
	err := helloworld.RegisterGreeterHandlerFromEndpoint(
		ctx, mux, "localhost:5000",
		opts,
	)
	if err != nil {
		log.Fatal(err)
	}

	err = helloworld.RegisterGreeterExtHandlerFromEndpoint(
		ctx, mux, "localhost:5001",
		opts,
	)
	if err != nil {
		log.Fatal(err)
	}

	http.ListenAndServe(":8080", mux)
}
```

### server

```
package main

import (
	"log"
	"net"

	"golang.org/x/net/context"
	"google.golang.org/grpc"
	pb "lib/helloworld"
	"google.golang.org/grpc/reflection"
	_ "github.com/go-sql-driver/mysql"
	"fmt"
	_ "database/sql/driver"
	"lib/models/gorose"
)

const (
	port = ":5000"
)

// server is used to implement helloworld.GreeterServer.
type server struct{}

// SayHello implements helloworld.GreeterServer
func (s *server) SayHello(ctx context.Context, in *pb.HelloRequest) (*pb.HelloReply, error) {
	db, err := gorose.ConnecctDB();
	if err != nil {
		log.Fatal("error")
	}

	User := db.Table("users")

	name := in.Name
	xx, err := User.Where("name", name).Limit(1).Get()
	yy := db.JsonEncode(xx)
	fmt.Println(yy)
	return &pb.HelloReply{Message: yy}, nil
}

func (s *server) SetAge(ctx context.Context, in *pb.SetAgeRequest) (*pb.SetAgeReply, error) {
	db, err := gorose.ConnecctDB();
	if err != nil {
		log.Fatal("error")
	}

	uid := in.Uid
	name := in.Name
	db.Execute("update users set name=? where uid=? limit 1", name, uid)
	return &pb.SetAgeReply{Message:"success"}, nil
}

func main() {
	lis, err := net.Listen("tcp", port)
	if err != nil {
		log.Fatalf("failed to listen: %v", err)
	}
	s := grpc.NewServer()
	pb.RegisterGreeterServer(s, &server{})
	// Register reflection service on gRPC server.
	reflection.Register(s)
	if err := s.Serve(lis); err != nil {
		log.Fatalf("failed to serve: %v", err)
	}
}

```

## 启动服务
```
启动rpc服务
go run greeter_server/main.go

启动gateway服务:
go run gatewayTest.go

调用http：
curl -d '{"uid": 589642,"name":"tanaa"}' http://localhost:8081/post
```

## 工作流程
![ Grpc-Gateway工作流程](https://raw.githubusercontent.com/huamaotang/techspace/master/images/ch4.6-1-grpc-gateway.png)
 						 						
## 问题

```
gateway与server 端口之间的关系，一对一？一对多？
``` 						
## 参考链接
[grpc-gateway git地址](https://github.com/grpc-ecosystem/grpc-gateway/tree/58f78b988bc393694cef62b92c5cde77e4742ff5)

[gorose git地址](https://github.com/gohouse/gorose)

[gRPC-gateway 源码阅读](https://jiajunhuang.com/articles/2018_08_08-grpc_gateway_source_code.md.html)