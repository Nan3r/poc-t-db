# -*-coding:utf-8-*-
# Author:Nan3r
# Mysql Insert

import MySQLdb,os

#在插入之前判断是否存在,没有就创建，最后都是返回一个POC_ID
def get_poc_id(poc_name):
	poc_id = 0
	# 打开数据库连接
	db = MySQLdb.connect("localhost","root","root","display" )

	# 使用cursor()方法获取操作游标 
	cursor = db.cursor()

	# SQL 查询语句
	sql = "SELECT * FROM poc_kind WHERE poc_name = '{}'".format(poc_name)
	try:
		# 执行SQL语句
		cursor.execute(sql)
		# 获取所有记录列表
		result = cursor.fetchone()
		if result:
			poc_id = result[0]
		else:
			try:
				cursor.execute("INSERT INTO poc_kind(poc_name) VALUES ('%s')" % (poc_name))
				poc_id = int(db.insert_id())
				db.commit()
			except Exception, e:
				print e
	except:
		print "Error: unable to fecth data"
	# 关闭数据库连接
	db.close()
	return poc_id

#将txt内容读取进来
def get_txt(txt_name):
	if os.path.exists(txt_name):
		with open(txt_name, 'r') as f:	
			return f.readlines()
	return False

#插入数据
def insert(poc_name):
	poc_type = 'type_test'
	txt_name = 'db_tmp.txt'
	if not get_txt(txt_name):
		exit(0);
	result = get_txt(txt_name)

	#print result
	if get_poc_id(poc_name) != -1:
		poc_id = get_poc_id(poc_name)
		# 打开数据库连接
		db = MySQLdb.connect("localhost","root","root","display" )
		# 使用cursor()方法获取操作游标 
		cursor = db.cursor()
		# SQL 插入语句
		sql = "INSERT INTO poc_result(poc_id,result) VALUES"
		for r in result[:-1]:
			sql += ' ('+str(poc_id)+', "'+r.strip()+'"),'
		sql += ' ('+str(poc_id)+', "'+result[-1].strip()+'")'
		#print sql
		try:
			# 执行sql语句
			cursor.execute(sql)
			# 提交到数据库执行
			db.commit()
			os.system("del %s" % txt_name)
		except:
			# Rollback in case there is any error
			db.rollback()
		# 关闭数据库连接
		db.close()

