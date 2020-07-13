# TCP（Transmission Control Protocol）
## 知识点
- 一种面向连接的、可靠的、基于字节流的传输层通信协议
- TCP在OSI的七层模型中的第四层--Transport层，IP（Internet Protocol）在第三层--Network层，ARP（Address Resolution Protocol）在第二层--Data Link层。第二层上的数据，叫Frame。第三层的数据，叫Packet。第四层的数据叫Segment
- 程序数据首先会打到TCP的Segment中，然后TCP的Segment会打到IP的Packet中，再打到以太网Ethernet的Frame中，传到对端后，各个层解析自己的协议，然后把数据交给更高层的协议处理

## TCP头格式

![](https://raw.githubusercontent.com/huamaotang/my-images/master/TCP-Header-01.jpg)

- TCP的包是没有IP地址的，那是IP层的事。但是有源端口和目标端口
- 一个TCP连接需要五个元组来表示同一个连接（src_ip, src_port, dst_ip, dst_port, Protocol）
- Sequence Number：包的序号，用来解决网络包乱序问题（reordering）
- Acknowledge Number：用于确认收到，用来解决不丢包的问题
- Window：Advertised-Window，滑动窗口（sliding window），用于解决流控（Flow Control）
- TCP Flag：包的类型，主要用于操控TCP的状态机

### 其它：
![](https://raw.githubusercontent.com/huamaotang/my-images/master/TCP-Header-02.jpg)

## TCP状态机
- 事实上，网络上的传输是没有连接的，包括TCP也是一样的。而TCP所谓的连接，只不过是在通讯的双方维护一个“连接状态”，让它看上去好像有连接一样

### TCP协议的状态机

<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-state-machine-1.png" width="600"/>
<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-state-machine2.png" width="500"/>

### TCP建连接、 TCP断连接、 传输数据
<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp_open_close.jpg" width="500"/>

- 3次握手
	- 功能：初始化Sequence Number
	- 通信的双方要互相通知对手自己的初始化Sequence Number（Initial Sequence Number）
	- SYN，Synchronize Sequence Number，这个号要作为以后的数据通信的序号，以保证应用层接收到的数据不会因为网络上传输的问题而乱序
- 4次挥手
	- 其实是2次，因为TCP是全双工的，所以发送方和接收方都需要Fin和Ack，只不过其中一方是被动的，所以看上去是4次
- 建连接时，SYN超时（Server已发送SYN、ACK给Client，Client断了，不发ACK）
	- 连接处于一个中间状态，即沒成功，也没失败。
	- Server端如果在一定时间内没有收到TCP会重发SYN、ACK
	- 在Linux下，默认次数为5次，重试的间隔时间从1s开始每次都翻翻，5次的时间间隔为1s、2s、4s、8s、16s，总共31s，第5次发出后还需等32s才知道是否超时，总共需要63s，TCP才会断开这个连接
- SYN Flood攻击（给服务器发了一个SYN后，下线，Server需要默认等63s才会断开连接，攻击者可以把Server的syn连接的队列耗尽，正常请求进不能处理）
	- tcp_syncookies，当队列满了，TCP会打造一个特殊的Sequence Number返回给Client，攻击者不会有响应，正常请求会返回这个SYN Cookie
	- 请不要用tcp_syncookies处理正常大负载的连接，因为tcp_syncookies是妥协版的TCP协议，并不严谨
	- 正常请求选择：tcp_synack_retries，可以减少重试的次数；tcp_max_syn_backlog，增大SYN连接数；tcp_abort_on_overflow，直接拒绝连接
- Initialization Sequence Number初始化
	- 不能hard code，计算公式：`ISN = M + F(localhost, localport, remotehost, remoteport)`
	- M是一个计时器，计时器每隔4毫秒加1
	- F是一个Hash算法，根据源IP、目的IP、源端口、目的端口生成一个随机数。要保证hash算法不能被外部轻易推算得出，用MD5算法是一个好的选择
- MSL和TIME_WAIT
	- MSL：Maximum Segment Lifetime，一个连接在网络上存活的最长时间；[RFC793](http://tools.ietf.org/html/rfc793)定义了MSL为2分钟，Linux设置为30s
	- 从CLOSED到WAIT_TIME，有一个超时设置，为2*MSL
	- TIME_WAIT确保有足够的时间让对端收到ACK，有足够的时间让这个连接不会跟后面的连接混在一起
	- TIME_WAIT数量太多时，可以使用tcp_max_tw_buckets来控制WAIT_TIME的数量默认180000，如果超限，系统会把多的连接destroy，tcp_max_tw_buckets用来对抗DDos

- TCP重传机制
	- TCP要保证所有的数据包都可以到达，必须有重传机制
	- 例子：Client（1，2，3，4，5份数据）发送给Server，Server收到1和2，3超时，收到4，TCP怎么处理？
	- 快速重传机制（Fast Retransmit算法）
		- 不以时间驱动，以数据驱动重传。
		- 如果包没有连续到达，就act最后那个可能被丢了的包，如果发送方连续收到3次相同的ack，就重传
		<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/fast-retransmit.png"/>
		- 缺点：只是解决了超时问题。
		- 关于是重传被丢了的包一个，还是重传被丢的包以后所有的数据，未知。大概率会是后者
	- SACK（Selective Acknowledgment）
		- 需要在TCP头加一个SACK，ACK仍旧是ACK，SACK则是汇报收到的数据碎版
		<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-sack.jpg" width="600"/>
		- 发送端能知道那些数据到了，哪些没到
		- 在Linux下，通过tcp_sack参数打开这个功能
		
- Duplicate SACK-重复收到数据
	- 又称：D-SACK
	- 如果SACK的第一个段的范围被ACK所覆盖，那么就是D-SACK
	- 如果第一个段的范围被SACK的第二个段覆盖，那么就是D-SACK	
## TCP相关算法

### RTT（Round Trip Time）算法
- RTT：一个数据包从发出去到回来的时间
- 重传机制的超时设置，需要一个合理的值，RTO（Retransmition TImeOut）。这个值需要动态变化

#### 经典算法
- 采样RTT，记录最近几次的RTT值
- 做平滑计算SRTT（Smooth RTT）：SRTT = ( α * SRTT ) + ((1- α) * RTT)
- 计算RTO：RTO = min [ UBOUND,  max [ LBOUND,   (β * SRTT) ]  ]
- UBOUND 最大timeout时间 LROUND最小timeout时间
- 缺点1：在采用第一次发数据的时间和ack回来的时间做RTT样本值，还是用重传的时间和ack回来的时间做RTT样本值时，难以选择
- 缺点2：采用“加权移动平均”方法，如果RTT有一个大的波动，很难被发现，被平滑了

#### Jacobson/Karels算法
- 引入了最新的RTT的采样和平滑过的SRTT的差距做因子来计算
- 计算平滑RTT：SRTT = SRTT + α (RTT – SRTT) 
- 计算平滑RTT和真实的差距（加权移动平均）：DevRTT = (1-β)\*DevRTT + β\*(|RTT-SRTT|)
- 计算重传超时值：
- RTO= µ * SRTT + ∂ *DevRTT
- 在Linux下，α = 0.125，β = 0.25， μ = 1，∂ = 4

## TCP滑动窗口
- TCP必须解决可靠传输以及包乱序的问题，因此，必需知道网络实际的数据处理带宽或是数据处理速度，才不会引起网络拥塞，导致丢包
- Sliding Window，网络流控的一种技术
- TCP头中的Window，又叫Advertised-Window，这个字段是接收端告诉发送端自己还有多少缓冲区可以接收数据
- 发送端可以根据这个接收端的处理能力来发送数据，而不会导致接收端处理不过来
- 缓冲区的部分数据结构：

<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-sliding-window-2.jpg"/>

- 发送方的滑动窗口示意图：

<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-sliding-window-1.png"/>

- 接收端控制发送端：

<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-sliding-window-3.png" width=600/>

## Zero Window
- 当Window变成0时，发送端停止发送数据，但是会发ZWP（Zero Window Probe）的包给接收端（查看接收端Window是不是大于0，能不能用）
- 一般会发3次，如果3次之后还是0，有的TCP实现会发RST（Reset）断开连接
- 只要有等待的地方都有可能出现DDos攻击（Distributed Denial of Service），Zero Window也不例外

## TCP的拥塞处理（Congestion Handing）
- TCP通过Sliding Window做流控（Flow Control），但是Sliding Control依赖于发送端和接收端，其并不知道网络中间发生什么
- TCP设计者认为，仅仅做到流控还不够，因为流控是四层以上的事，TCP还应该知道整个网络的事
- TCP不能忽略网络上发生的事情，仅仅重发数据
- TCP设计理念：TCP不是一个自私的协议，当拥塞发生时，要做自我牺牲。就像交通阻塞一样，每隔车都应该把路让出来，而不是去抢路

## 拥塞控制四大算法
### 慢启动算法（Slow Start）
- 定义：刚刚加入网络的连接，一点点的提速
- 算法（cwnd-Congestion Window）
	- 连接建好的开始先初始化cwnd=1（Linux3.0初始值为10MSS），表明可以传一个MSS大小的数据（网络上有个MTU-Max Transmission Unit，以太网MTU等于1500字节，除去TCP+IP头的40个字节，可用1464字节，这就是MSS-Max Segment Size）
	- 每当收到一个ACK，cwnd++；呈线性上升
	- 每当过了一个RTT，cwnd=cwnd*2；呈指数上升
	- ssthresh（slow start threshold），当cwnd >= ssthresh时，就会进入“拥塞避免算法”
	<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-slow-shart-1.jpg"/>
	
### 拥塞避免算法-Congestion Avoidance
- ssthreshold，是一个上限。一般ssthresh的值为65535字节，当cwnd达到这个值后
- 收到一个ACK时，cwnd=cwnd+1/cwnd
- 收到一个RTT时，cwnd=cwnd+1
- 这样就可以避免增长过快导致网络拥塞，慢慢的增加调整到网络的最佳值；这是一个线性上升算法

### 拥塞状态时的算法（Congested）
#### 当丢包时，会有两种情况：
- 等到RTO超时，重传数据包。TCP认为这种情况太糟糕，反应很强烈
	- ssthresh=cwnd/2
	- cwnd重置为1
	- 进入慢启动
- Fast Retransmition算法，收到3个duplicate ACK时开启重传，不用等到RTO超时
	- cwnd=cwnd/2
	- ssthresh=cwnd
	- 进入快速恢复算法--Fast Recovery

### 快速恢复算法-Fast Recovery
#### TCP New Reno
- 在没有SACK的支持下改进Fast Recovery算法
<img src="https://raw.githubusercontent.com/huamaotang/my-images/master/tcp-fast-recovery.jpg"/>

#### FACK

#### TCP BIC算法
- 2004年发表，全称：Binary Increase Congestion Control
- Linux2.6.8默认拥塞控制算法
- 各类拥塞算法，目的都是寻找一个合适cwnd，这是一个搜索的过程
- BIC使用二分查找实现BIC算法