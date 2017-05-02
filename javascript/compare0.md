# 0、'0'、false、null、''、' '、undefined在JavaScript中的比较

```
1、0
== '0'、false、’’、’ ’
!= null、undefined

2、’0’
==  0、false
!= ‘’、’ ‘、null、undefined

3、false
== 0、’’、’0’、' '
!= null、undefined

4、null
== undefined
!= false、0、‘ ‘、’0’、''

5、''
== 0、false
!= ' '、’0’、null、undefined

6、’ ’
== 0
!= ‘0’、false、null、’’

