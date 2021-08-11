
## 并发、锁
```
数据库dead lock，采用的是serial级别；
采用etcd锁，也就是一个map，存入唯一key，存在则阻塞，否则存入，完成后释放
```