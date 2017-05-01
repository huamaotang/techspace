# MongoDB学习笔记
## 概念
```
文档：MongoDB的核心
集合：一组文档，无模式
数据库：多个集合
命名：文档、集合、数据库需要规范命名（存在系统数据库、保留字等）
不需要创建DB，创建集合。数据生成时，自动生成
功能丰富（mapreduce、副本集等）
```
## 环境搭建
```
下载安装 mongodb
创建服务器目录 mkdir mongodb
创建db目录 mkdir data&&mkdir log&&mkdir conf&&mkdir bin
copy启动文件到bin cp ../mongodb-osx-x86_64-3.2.4/bin/mongod ./bin 
创建配置文件 cd conf 
vim mongod.conf
port = 12345 
dbpath = data 
logpath = log/mongod.log 
fork = true 
启动mongo服务 ./bin/mongod -f conf/mongod.conf 
连接mongo  ./bin/mongo 127.0.0.1:12345
```
## 操作
### insert
```
原理：驱动程序将数据转换成BSON格式，然后将其送入数据库。数据库解析BSON，检测是否包含“_id”且文档不超过4M，送入数据库
好处：对注入式攻击免疫
坏处：允许写入无效数据
实例：for(i=4;i<100;i++)db.fund_core.insert({a:i})
```
### remove
```
删除是永久性、不可撤销性、异步操作
实例：db.fund_test.remove({a:1})
```
### update
```
更新是原子级别的，如果两个更新操作同时发生，先到达服务器的先执行，接着执行另一个
 1）删除某个字段
 db.zd_keyword.update({},{$unset:{“firstLetter”:”"}},{multi:true})
2）增加字段并添加指定值
 db.zd_topic.update({},{$set:{create_time:1414985773}},false,true);
3）更新所有字段
 db.ask_a1.update({a:7},{a:777})
4）更新部分字段
 db.ask_a1.update({c:3},{$set:{b:22}})
5）当文档不存在时插入
 db.ask_a1.update({a:100},{b:101},true);
6）更新多条文档
 db.ask_a1.update({d:1},{$set:{d:2}},false,true);
 ```
 ### find
 ```
 1）返回所有文档
 db.zd_topic.find({})

2）条件查询
 db.fund_infomation.find({recommondPosition:2,status:1}).sort({_id:-1}).limit(10).pretty()
3）查看字段存在的记录
 db.zd_topic.find({last_update_time:{$exists:true}});
4）分页查询 
 db.fund_core.find({}).skip(3).limit(2).sort({a:1})
 db.ask_a1.find({}).skip(0).limit(2).sort({a:-1})
5）数量查询 
 db.ask_a1.find({}).count();
6）正则查询
db.fund_infomation.find({title:/测试/}).pretty()
```
## 索引
### 描述
```
与关系型数据库索引几乎完全一模一样
如果索引为{a:1,b:1,c:1},{b:1,c:1}查询是用不到该索引的
当创建{a:1,b:1}索引时，{b:1,a:1}查询时该索引有效
每个集合最大索引个数为64个
```
### 命令
```
1）创建
复合：db.zd_topic.ensureIndex({“is_accepted”:1,"status":1,"modify_time":-1},{"background":1})
单键：db.zd_tag.ensureIndex({“zd_tag_id":1},{"background":1})
唯一：db.ask_a.ensureIndex({a:1,b:1},{name:”base2_idx",unique:true})
稀疏：db.ask_a3.ensureIndex({a:1},{unique: true,sparse:true})
当a有些文档不存在时，创建会报错。sparse等于true作用是当a不存在时，不进入索引
2d：db.location.ensureIndex({“w”:"2d"})
2）查看
db.zd_topic.getIndexes()
3）删除
db.ask_a.dropIndex(“base_idx")
4）查看索引使用情况
db.fund_core.find({a:23}).explain(‘executionStats')
5）强制使用索引
db.fund_core.find({a:23}).hint(‘a:1’)
```
## 聚合
```
count    
db.fund_core.count()
distinct 
db.fund_core.distinct(‘c');
sort       
db.fund_core.find({}).sort({a:-1});
group 
```
## GridFS存储文件
### 描述
```
储存二进制文件机制
优势
1）简化需求
2）直接利用已搭建完成的复制、分片服务
3）可放置大量文件
4）不产生磁盘碎片
```
### 命令
```
使用mongofiles上传
命令：put、get、search、list
实例：./bin/mongofiles --host=127.0.0.1 --port=12345 put index.txt
查看：db.fs.files.find({filename:'2.txt'})
查看所有文件：db.fs.files.distinct('filename')
```
## 服务器端脚本
```
通过db.eval函数执行任意javascript脚本
Deprecated since version 3.0
实例：db.eval(function(a, b) { return a + b; },2,3)
执行脚本时，必须考虑安全性。比如，db.eval(function() { print(‘aa’);db.dropDatebase();})
```
## 数据库引用
```
DBref：内嵌文档
存储一些对不同集合的文档的引用时，可以使用DBref
实例：
db.fund_100.insert({_id:3,a:1,b:2})
db.fund_101.insert({c:1,’references':[{'$ref':'fund_100','$id':3}]})
db.fund_101.find()
var obj = { "_id" : ObjectId("57a837a423ecd9d8a79e8901"), "c" : 1, "references" : [ DBRef("fund_100", 3) ] }
obj.references.forEach(function(ref){printjson(ref.fetch())})
```
## 监控
```
启动mongod时，开启http服务器
配置文件增加：rest = true
默认监听端口为mongod端口号+1000
http://localhost:13345
```
## 备份和修复
```
dbpath：数据目录，配置文件可配置，默认/data/db/
数据库加锁
db.runCommand({fsync:1,lock:1})
数据库解锁
db.$cmd.sys.unlock.findOne()
db.fsyncUnlock()
数据库备份
./bin/mongodump --host 127.0.0.1 --port 12345 -d fund -o databak1
数据库恢复
./bin/mongorestore --host 127.0.0.1 --port 12345 databak1/
数据修复
db.repairDatabase()
```
## 主从复制
### 描述
```
方式非常灵活
可用于备份、故障恢复、读扩展等
从节点可通过sources集合进行配置
```
### 命令
```
创建目录：master、slave
启动服务：
master: ./bin/mongod --dbpath master --port 10000 --master
slave：./bin/mongod --dbpath slave --port 10001 --slave
注意：slave需做如下操作
rs.slaveOk() //从库默认不能写
db.sources.insert({host:’127.0.0.1:10000’,source:'main'})
```

![主从复制](https://github.com/huamaotang/techspace/blob/master/images/masterSlave.png?raw=true)

## 副本集
### 描述
```
有自动恢复功能的主从集群
没有固定的主节点
自动化
对开发者友好
```
### 命令
```
创建数据目录
mkdir -p ./dbs/node1 ./dbs/node2 ./dbs/node3
启动
./bin/mongod --dbpath ./dbs/node1 --port 20001 --replSet noah/mac.local:20002
./bin/mongod --dbpath ./dbs/node2 --port 20002 --replSet noah/mac.local:20001
./bin/mongod --dbpath ./dbs/node3 --port 20003 --replSet noah/mac.local:20001,mac.local:20002
初始化
config={'_id':'noah','members':[{'_id':1,'host':'mac.local:20001'},{'_id':2,'host':'mac.local:20002'},{'_id':3,'host':'mac.local:20003'}]}
rs.initiate(config)
设置副节点可读 
rs.slaveOk()
查看状态
rs.status()
```

![副本集](https://github.com/huamaotang/techspace/blob/master/images/relpset.png?raw=true)

## oplog
```
主节点的操作记录，operation log简称
存在于local数据库，oplog.$main集合中
每一个文档代表一个操作
查看oplog
db.oplog.rs.find({}).pretty()
从节点定期轮询主节点获得这些操作，然后对从节点执行这些操作，从节点就能保持与主节点数据同步
```
## map reduce
### PHP示例
```
public function getCntsByCurrentDay($userId,$operationType){
		$startTime = strtotime(date('Y-m-d',time()));
		$where = array(
			'userid' => intval($userId),
			'operation_type' => intval($operationType),
			'status' => Const_Experience::EXPERIENCE_STATUS_ONLINE,
		   'create_time' => array(
			   '$gt' => intval($startTime),
		   ),
		);
		$data = array(
			'mapreduce' => $this->table,
		   'map' => 'function (){emit(this.userid,this.experience);}',
		   'reduce' => 'function (key,value){return Array.sum(value);}',
		   'query' => $where,
		   'out' => 'experience_detail_cnts',
		);
		$this->mongo->command($data);
		$info = array_shift( $this->mongo->get('experience_detail_cnts') );
		return $info['value'];
	}
```
### 计算指定字段总和
```
db.fund_test.insert({a:1,b:2,c:3})
map=function (){emit('total',this.a + this.b + this.c)}
reduce=function (key,value){return Array.sum(value);}
db.runCommand({'mapreduce':'fund_test','map':map,'reduce':reduce,'out':'tmp'})
```	
## 其他
```
wiredTrige存储引擎
mongos
```

