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
package main

import (
	"github.com/jinzhu/gorm"
	"fmt"
	_ "github.com/go-sql-driver/mysql"
	"time"
	"math/rand"
)

type User struct {
	Uid int64 `gorm:"column:uid" json:"uid"`
	Name string `gorm:"column:name" json:"name"`
	Age int `gorm:"column:age" json:"age"`
	Gid int `gorm:"column:gid" json:"gid"`
	CreateTime int64 `gorm:"column:create_time" json:"create_time"`
	LastTime *time.Time `gorm:"column:last_time" json:"last_time"`
}

func init() {
	rand.Seed(time.Now().UnixNano())
}

func main() {
	db, err := gorm.Open("mysql", "root:root@tcp(127.0.0.1:3306)/thm?charset=utf8&parseTime=true&&loc=Asia%2FShanghai")
	if err != nil {
		fmt.Printf("%s", err.Error())
		return
	}
	fmt.Println("connect success")

	var xx User
	db.Order("Uid desc").First(&xx)
	fmt.Println(xx)

	for i := 0; i < 1; i++ {
		addUser := User{Name:RandStr(5), Age:rand.Intn(100), Gid:rand.Intn(100000) + 100000, CreateTime:time.Now().Unix()}
		db.Save(&addUser)
	}

	var yy User
	db.Where("name = ?", "igAlb").Find(&yy)
	fmt.Println(yy)

	var zz User
	db.Not("name", []string{"igAlb", "HtjZT"}).Find(&zz)
	fmt.Println(zz)

	yy.Name = "tt"
	yy.Age = 28
	db.Save(&yy)
	fmt.Println(yy)

	db.Delete(User{}, "name = ?", "tt")

	defer db.Close()
}

func RandStr(strlen int) string {
	data := make([]byte, strlen)
	var num int
	for i := 0; i < strlen; i++ {
		num = rand.Intn(57) + 65
		for {
			if num>90 && num<97 {
				num = rand.Intn(57) + 65
			} else {
				break
			}
		}
		data[i] = byte(num)
	}
	return string(data)
}


```

## 参考链接
[GORM 指南](http://gorm.io/zh_CN/docs/)

[Go语言中使用gorm小结_Golang](https://yq.aliyun.com/ziliao/92405)
