# demo
```
# !/usr/bin/env python
# coding=utf-8
import MySQLdb

class demo:
	
	def __init__(self):
		self.connect = MySQLdb.connect(host = 'localhost', user = 'root', passwd = '',db = 'noah', port = 3306)
		self.cursor = self.connect.cursor()

	def writeBySql(self, sql):
		res = self.cursor.execute(sql)	
		self.connect.commit()

		return res

	def findBySql(self, sql):	
		self.cursor.execute(sql)

		return self.cursor.fetchall()

	def __del__(self):
		self.cursor.close()
		self.connect.close()

db = demo()	

sql = "insert into user (name, age, sex) values ('汤华茂', 27, 1)";
res = db.writeBySql(sql)
print res

sql =  "select * from user"
datas = db.findBySql(sql)
for i in range(len(datas)):
	#for j in range(len(datas[i])):
		print datas[i]

