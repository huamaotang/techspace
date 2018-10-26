# grpcurl
## 常用命令
```
grpcurl -plaintext -d '{"name":"GkvHi"}' localhost:5000 helloworld.Greeter/SayHello

grpcurl -plaintext localhost:5000 list  helloworld.Greeter


```