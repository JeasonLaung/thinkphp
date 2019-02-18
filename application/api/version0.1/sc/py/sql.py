# locals()['a']=1
# print(a)
# def b():
# 	locals()['a']=1
# 	print(a)
# b()
# a = {'a':1}
# print(len(a))
# print('a'*10)
import hashlib
# print(hashlib.hash_md5('123'))
m = hashlib.sha256()
m.update(('123456jeason').encode('utf-8'))
print(m.hexdigest())
# #coding=utf-8
# import pymysql
# class sql():

# 	host = 'localhost'
# 	port = 3306
# 	user = 'root'
# 	password = 'root'
# 	db = 'jobweb'
# 	charset = 'utf8'
# 	cursor = None

# 	trim = False
# 	fieldArray = {}
# 	where = ''

# 	def __init__(self):
# 		self.cursor = pymysql.Connect(
# 			host=self.host,
# 			port=self.port,
# 			user=self.user,
# 			password=self.password,
# 			db=self.db,
# 			charset=self.charset
# 		).cursor()
# 		pass
# 	def table(self,table):
# 		self.table = table
# 		return self
# 	def handleSql(self):
# 		pass
# 	def trim():
# 		self.trim = True
# 		return False
# 		pass
# 	def add(self):
# 		s = 'insert into name(name) values (\'%s\')' % '你好'
# 		return self.__exec(s)
# 		pass
# 	def get(self,limit='',choose='',sort=False):
# 		if limit:
# 			limit_str = 'LIMIT '+str(limit)
# 		else:
# 			limit_str = ''

# 		if sort == 1:
# 			sort_str = 'ASC'
# 		elif sort == -1:
# 			sort_str = 'DESC'
# 		else:
# 			sort_str = ''

# 		where = self.where(self.fieldArray)

# 		if self.trim and type(choose) == type([]):
# 			s = 'AND '
# 			for x in choose:
# 				s += x + '!=\'\' AND '
# 			trim_str =s[0:-5]
# 			if bool(where) is False:
# 				trim_str = 'WHERE '+trim_str[3:]
# 		else:
# 			trim_str = ''

# 		if type(choose) is type([]):
# 			choose_str = ','.join(choose)
# 			s = 'SELECT %s FROM %s %s %s ORDER BY %s %s %s' % (choose_str,self.table,where,trim_str,choose[0],sort_str,limit_str)
# 		elif choose is '':
# 			s = 'SELECT * FROM %s %s %s %s ' % (self.table,where,trim_str,limit_str)
# 		else:
# 			s = 'SELECT * FROM %s %s %s ORDER BY %s %s %s' % (self.table,where,trim_str,choose,sort_str,limit_str)

		
			
# 		print(s)
# 		self.__exec(s)
# 		return self.cursor.fetchall()

# 	def where(self,json):
# 		temp = ''
# 		if json:
# 			temp = 'WHERE '
# 			for x in json:
# 				temp += str(x)+'=\''+str(json[x])+'\' AND '
# 			temp = temp[0:-4]
# 		return temp
# 		pass
# 	def count(self):
# 		where = self.where(self.fieldArray)
# 		s = 'SELECT COUNT(*) FROM %s %s' % (self.table,where)
# 		print(s)
# 		self.__exec(s)
# 		return self.cursor.fetchall()[0][0]
# 	def __exec(self,query):
# 		self.__reset()
# 		return self.cursor.execute(query)
# 		pass
# 	def __reset(self):
# 		self.fieldArray = {}
# 		pass
# 	def __setattr__(self,key,value):
# 		if hasattr(self,key) is False:
# 			self.fieldArray[key] = value
# 		else:
# 			self.__dict__[key] = value

# a = sql()
# # print(a.get(5,'id',1))
# a.table('student')
# # a.b=  1
# # a.base_gender = 'female'
# # print(a.count())
# a.base_gender = 'female'

# num = a.count()
# host = 'https://m.v2.51renc.com'
# url_data_arr = a.get('',['base_logo'])
# print(url_data_arr)
# import requests,time
# 	# for x in data:
# active = 0
# while(True):
# 	url = host+url_data_arr[active][0]
	
# 	data = requests.get(url).content
	
# 	# print(data)
# 	try:
# 		with open(url.split('/')[-1],'wb') as f:
# 			f.write(data)
# 	# 	# data = requests.get(url).content
		
# 		print('成功爬取：'+url.split('/')[-1])
# 	except ValueError as e:
# 		print('爬取失败：'+url.split('/')[-1] + '\n错误原因：'+e)

# 	if active>=num:
# 		break
# 	active = active+1
# 	time.sleep(0.5)

# # print(a.fieldArray)


# # def fnc(*arg):
# # 	print(arg);

# # fnc(1)		