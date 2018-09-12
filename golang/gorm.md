# GORM

## 概览

```
全功能 ORM (无限接近)
关联 (Has One, Has Many, Belongs To, Many To Many, 多态)
钩子 (在创建/保存/更新/删除/查找之前或之后)
预加载
事务
复合主键
SQL 生成器
数据库自动迁移
自定义日志
可扩展性, 可基于 GORM 回调编写插件
所有功能都被测试覆盖
开发者友好
```

## 模型定义
### 定义
```
type User struct {
  gorm.Model
  Name         string
  Age          sql.NullInt64
  Birthday     *time.Time
  Email        string  `gorm:"type:varchar(100);unique_index"`
  Role         string  `gorm:"size:255"` // 设置字段大小为255
  MemberNumber *string `gorm:"unique;not null"` // 设置会员号（member number）唯一并且不为空
  Num          int     `gorm:"AUTO_INCREMENT"` // 设置 num 为自增类型
  Address      string  `gorm:"index:addr"` // 给address字段创建名为addr的索引
  IgnoreMe     int     `gorm:"-"` // 忽略本字段}
```

### chrome插件 sql2struct.crx
![](https://raw.githubusercontent.com/huamaotang/techspace/master/images/sql2struct.png)

## CRUD操作
```
package gorm

import (
	"github.com/jinzhu/gorm"
	"time"
	"log"
	_ "github.com/go-sql-driver/mysql"
	)

var GDB *gorm.DB

type User struct {
	Uid int64 `gorm:"column:uid;PRIMARY_KEY" json:"uid"`
	Name string `gorm:"column:name" json:"name"`
	Age int `gorm:"column:age" json:"age"`
	Gid int `gorm:"column:gid" json:"gid"`
	CreateTime int64 `gorm:"column:create_time" json:"create_time"`
	LastTime *time.Time `gorm:"column:last_time;type:timestamp"`
}

func init() {
	DB, err := gorm.Open("mysql", "root:root@tcp(127.0.0.1:3306)/thm?charset=utf8&parseTime=true&&loc=Asia%2FShanghai")
	if err != nil {
		log.Fatal(err)
	}
	GDB = DB
}

func GetByUid(uid int) (User) {
	var xx User
	GDB.Order("Uid desc").Where("uid=?", uid).First(&xx)
	return xx
}

func AddUser(name string, age int, gid int) bool {
	addUser := User{Name:name, Age:age, Gid:gid, CreateTime:time.Now().Unix()}
	GDB.Save(&addUser)

	return true
}

func UpdateUser(uid int) bool {
	user := GetByUid(uid)
	user.Name = "xxxxx"
	GDB.Save(&user)
	return true
}

func DelUser(uid int) bool {
	GDB.Delete(User{}, "uid=?", uid)
	return true
}



```

## 参考链接
[GORM 指南](http://gorm.io/zh_CN/docs/)

[GORM 中文文档](http://gorm.book.jasperxu.com)

[Go语言中使用gorm小结_Golang](https://yq.aliyun.com/ziliao/92405)
