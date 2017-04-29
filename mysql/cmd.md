# mysql命令
 
## db
```
create database db_common;
```
```
show databases;
```
```
use db_common;
```
```
drop database db_common;
```

##table
```
CREATE TABLE `t_sys_config` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `configName` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `configCode` char(4) NOT NULL DEFAULT '' COMMENT '编号',
  `value` varchar(256) NOT NULL DEFAULT '' COMMENT '配置值',
  `dataType` varchar(8) NOT NULL DEFAULT '' COMMENT '数据类型 0布尔型 1-数值 2-字符串',
  `remark` varchar(2000) NOT NULL DEFAULT '' COMMENT '备注',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '数据创建时间',
  `lastModTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  PRIMARY KEY (`autoId`),
  UNIQUE KEY `configCode` (`configCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';
```
```
show create table t_sys_config;
```
```
desc qeeka.person;
```
```
drop table if exists t_sys_config;
```
表数据占用大小

```
use information_schema;
select concat(round(sum(data_length/1024/1024),2),'MB') as data from tables where table_schema='zxq';

select concat(round(sum(data_length/1024/1024),2),'MB') as data from tables where table_schema='zxq' and table_name='plot_image';
```
##insert

```
insert into `person` (`Name`,`Status`,`Created`) values ('汤华茂2',1,unix_timestamp( now() ) );
```
##delete
```
delete from t_sys_config where autoId=1 limit 1;
```

##update
```
update t_bank set bankHSId='' where bankHSId is null;
```

##select
###like
```
SELECT * FROM ajk_membersother WHERE username LIKE '%人%' AND truename REGEXP '^田' AND usermobile REGEXP '2{3}';
```

###join

左联接：左表内容全部显示，右表不满足的全部为null

```
 select * from person p left join age a on p.Id = a.PersonId;
```
右连接：右表内容全部显示，左表不满足的全部为null

```
select * from person p right join age a on p.Id = a.PersonId;
```
inner join

```
select * from person p inner join age a on p.Id = a.PersonId;
```
等价于

```
select * from person,age where person.Id = age.PersonId;
```
###union
```
 select Id,Updated from person where Name = 'tanghuamao132' union select PersonId,Updated from age;
```


##columns
```
show columns from t_sys_config\G
```
```
alter table t_fund_manager add column `workAddress` varchar(60) NOT NULL DEFAULT '' COMMENT '办公地址' after regaddress;
```
```
alter table `t_ta_info` drop column `interfaceType`;
```
```
alter table t_ta_info change column `taCode` `taCode` char(4) NOT NULL DEFAULT '' COMMENT 'ta代码';
```
##index
```
show index from t_sys_config;
```
```
alter table plot add index idx_city_id (`city_id`);
```
```
ALTER TABLE t_ta_info ADD UNIQUE taCode (taCode);
```
```
alter table plot_image drop index fang_url;
```
```
alter table t_ta_info drop index taCode;
```
强制使用(忽略)索引:

```
select * from person force index (Primary) limit 100000;
```
```
select * from person ignore index (Name) where Name='tanghuamao2344';
```
##function
unix_timestamp、now

```
insert into `person` (`Name`,`Status`,`Created`) values ('汤华茂2',1,unix_timestamp( now() ) );
##prompt
设置提示符

```
mysql -uroot --prompt="\d>"
```
* \v  服务器版本  
* \d  当前的数据库  
* \h  服务器主机  
* \p  当前的TCP/IP端口或套接字文件  
* \u  你的用户名 
```
##system
```
SET SQL_SAFE_UPDATES = 0;
```
```
select sql_no_cache * from person force index (Name) limit 100000;
```
    
