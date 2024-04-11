# 几个简单算法

                    <img title="" src="https://github.com/huamaotang/techspace/blob/master/images/algorithms-4.png?raw=true" alt="" width="467">

## 概念

```textile
- 编写一段计算机程序一般都是实现一种已有的方法来解决某个问题

- 在计算机科学领域，我们用算法这个词来描述一种有限、确定、有效的并适合用计算机程序来实现的解决问题的方法。算法是计算机科学的基础，是这个领域研究的核心

- 大多数算法都需要适当地组织数据，而为了组织数据就产生了数据结构。数据结构也是计算机科学研究的核心对象，它和算法的关系非常密切
```

## 欧几里得算法

#### 自然语言描述

```textile
计算两个非负整数p和q的最大公约数：若q是0，则最大公约数为p。否则，将p除以

q得到余数r，p和q的最大公约数即为q和r的最大公约数
```

#### 过程

<img title="" src="https://github.com/huamaotang/techspace/blob/master/images/eu-progress.png?raw=true" alt="" width="667">



#### java实现

```java
public static Integer gcd(Integer a, Integer b) {
        return b == 0 ? a : gcd(b, a%b);
}
```

# #### 性质



### 
