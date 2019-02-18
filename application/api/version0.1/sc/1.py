#coding=utf-8
import pymysql
import sys,os
class sql():
	host = 'localhost'
	port= 3306
	user = ''
	passwd = ''
	db = ''
	charset = 'utf8'
	cursor = None

	_table = ''

	_where = ''
	_limit = ''
	_field = ''

	lastSql = ''

	data = {}
	# 魔术方法
	def __init__(self,**arg):
		# 去除第一个值判断类型，如果是dict则按照dict赋值数据库
		if type(arg['db']) == type({}):
			for key in arg['db']:
				setattr(self,key,arg['db'][key])
		self.cursor = pymysql.Connect(
			host=self.host,
			port=self.port,
			user=self.user,
			passwd=self.passwd,
			db=self.db,
			charset='utf8'
		).cursor()

	def __setattr__(self,key,value):
		if hasattr(self,key) is False :
			if value:
				value = value.replace('"','\\"')
				value = value.replace("'","\\'")
			self.data[key] = '\"%s\"' % value
		else:
			# setattr(self,key,value) #两种都可以
			self.__dict__[key] = value

	def table(self,table):
		self._table = table
		return self
	def where(self,where):
		if where:
			self._where = ' WHERE ' + where
		return self

	def limit(self,limit):
		if limit:
			self._limit = ' LIMIT ' + limit
		return self
	def field(self,field):
		if field:
			self._field = field
		return self

	def add(self):
		add_str = 'INSERT INTO ' + self._table+ '(' + ','.join(self.data.keys()) + ') VALUES(' + ','.join(self.data.values()) + ')'
		self.query(add_str)
	def set(self):
		temp = ''
		for x in self.data.keys():
			temp += '%s = %s,' % (x,self.data[x])
		temp = temp[:-1]
		set_str = 'UPDATE ' + self._table+ ' SET ' + temp
		self.query(set_str)
	def get(self,pk=False):
		if self._field:
			field = self._field
		else:
			field = '*'
		if type(pk) == type(1):
			self.where('id = %s'% pk)
		get_str = 'SELECT '+field+' FROM ' + self._table
		self.query(get_str)
		return self.cursor.fetchall()

	def convert(self,data):
		f = self.do('SHOW COLUMNS FROM `%s`' % self._table)
		# print(data)
		# field_name_arr = []
		temp = []
		for y in range(len(data)):
			# 遍历data y：第几个
			one = data[y]
			# print(temp[y])
			# if type(temp[y]) != type([]):
				# 创建数组
			temp2 = {}
			for x in range(len(f)-1):
				# key
				key = f[x][0]
				# print(str(key))
				temp2[key] = one[x]
				# 每个data
			temp.append(temp2)
		# for i in data:
			

		return temp
	def do(self,sql):
		self.cursor.execute(sql)
		self.cursor.connection.commit()
		return self.cursor.fetchall()
	def query(self,query_str):
		query_str = query_str + self._where + self._limit
		self.lastSql = query_str
		# print(query_str)
		self.cursor.execute(query_str)
		self.cursor.connection.commit()
		self.data = {}
		self._where = ''
		self._field = ''
		return self
	def getLastSql(self):
		return self.lastSql
	def select(self):
		return self.cursor.fetchall()

db = {
	"host":'localhost',
	"port":3306,
	"user":'root',
	"passwd":'root',
	"db":'fuck_life',
	"charset":'utf8'
}
m = sql(db=db)
# m.table('school')
# print(m.convert(m.get(100)))
# sys.exit()
# m.field(' id as uid ')
# print(m.get(100))
# sys.exit()
import json
# m = sql(db=db)
# m.table('student')
# m.base_birth_month = '我是'
# m.add()
import requests

print(fuck_life)




sys.exit()
cookies = {
	'COMPANY':'Wc9I4mG8Vsh8ACpzqrAziGqqR1rfCSF5MtDXMyNaWZHtCkSU3MDspVA0oNA6xkg7ySRi7P2GK%252FXMvLRDf2ppMw%253D%253D;'
}
# # print(cookies)
# # print(res.text)
m=sql(db=db)
with open('51job.json',encoding='utf-8') as f:
	data = f.read()

i = 0
m.table('school_year')
import time
while i < 145:
	i = i+1
	m.where('id = %s' % i)
	data = m.get()
	if data is None or len(data)<1:
		print(1)
		continue
	else:
		year = data[0][1]
		school_id = data[0][2]

	m.table('major')
# 	# res = requests.get('http://www.51renc.com/api/v3/school/get_school_info?school_id='+str(i),cookies=cookies)
# 	m.table('school_year')
# 	res = requests.get('http://www.51renc.com/api/v3/school/get_all_year?school_id='+str(i),cookies=cookies)
	res = requests.get('http://www.51renc.com/api/v3/school/get_all_major?school_id=%s&year=%s' % (school_id,year))
	# print('http://www.51renc.com/api/v3/school/get_all_major?school_id=%s&year=%s' % (school_id,year))
	# continue
# 	# print(res.status_code)
# 	# break
	# print(res)
	if res.status_code == 200:
		res = res.json()

	else:
		continue
	
	for x in res['data']:
		m.school_id = str(school_id)
		for y in x:
			m.__setattr__(y,str(x[y]))
		m.add()
		print(m.getLastSql())

	m.table('school_year')

		# m.table('major')
		# m.name = str()
		# m.year = str(x)
		# m.add()
		# print(m.getLastSql())


	# if res['status'] == 1:
	# 	continue
	# m.__setattr__('id',str(i))
	# for x in res['data']:
	# 	y = res['data'][x]
	# 	m.__setattr__(x,y)
	# m.add()
	# print(m.getLastSql())
	# pass

# print(data)






# m.table('student')
# i=0
# # print([] == [])
# # print(type({'a':1})==type({}))
# while 1:
# 	i = i+1
# 	res = requests.get('http://m.v2.51renc.com/api/v2/company/resume_search?page='+str(i),cookies=cookies).json()
# 	if res['data'] == []:
# 		break
# 	for x in res['data']:
# 		for y in x:
# 			if type(x[y])==type({}) or type(x[y])==type([]):
# 				x[y] = json.dumps(x[y])
# 			m.__setattr__(y,str(x[y]))
# 		m.add()
# 	print('success INSERT'+str(i))
	


# filename='51company.json'
# with open(filename,encoding='utf-8') as f:
	# data = json.loads(f.read())['data']['data']






# m = sql(db=db)
# m.table('user')
# # m.where('degree = \'3.5\'')
# change_json = {
# 	# 'vip_modified_time':'vip_modified_time',
# 	'created':'created_time',
# 	# 'modified':'modified_time'
# }
# for x in data:
# 	m.where('username = ' + '\''+str(x['username'])+'\'')
# 	for y in x:
# 		if y in change_json:
# 			m.__setattr__(change_json[y],x[y])
# 	m.set()
# 	print(str(x['id'])+'成功')
	# province_name_arr = []
	# for one in x:
	# 	m.table('province')
	# 	province_name = one['mergername'].split(',')[1]
	# 	m.field('id')
	# 	m.where(' name = \"'+province_name+'\"')
	# 	province_id = m.get()[0][0]
	# 	m.table('city')
	# 	m.province_id = province_id
	# 	for key in one:
	# 		m.__setattr__(key,one[key])
	# 	m.add()
		# province_name_arr.append('\''+province_name+'\' OR ')
	# sql = 'name = '+'name = '.join(province_name_arr)[:-3]
	# m.where(sql)
	# m.field('id')
	# m.get()
	# 	m.__setattr__(key,x[key])
	# m.add()
	# print(x['name']+'添加成功')



# import json
# db = {
# 	"host":'localhost',
# 	"port":3306,
# 	"user":'root',
# 	"passwd":'root',
# 	"db":'51ren',
# 	"charset":'utf8'
# }
# filename = '51job.json'

# with open(filename,encoding='utf-8') as f:
# 	data = json.loads(f.read())['data']
# print(data[0])
# mapField = {
# 	'is_vip':'is_vip',
# 	'vip_modified_time':'vip_modified_time',
# 	'is_available':'is_available',
# 	'id':'id',
# 	'name':'name',
# 	'created':'created_time',
# 	'modified':'modified_time',
# 	'brief':'description',
# 	'province':'province',
# 	'city':'city',
# 	'logo':'logo',
# 	'license':'license_pic',
# 	'credit_code':'credit_code',
# 	'has_fix':'has_fixed',
# 	'expired_time':'expired_time',
# 	'brief_name':'brief_name'
# }

# print(eval(data))



# mapField = {
# 	'id':'id',
# 	'company_id':'company_id',
# 	'title':'title',
# 	'location':'location',
# 	'degree':'degree',
# 	'experience':'experience',
# 	'phone':'phone',
# 	'description':'description',
# 	'status':'status',
# 	'created':'created_time',
# 	'modified':'modified_time',
# 	'welfare':'welfare',
# 	'province':'province',
# 	'city':'city',
# 	'max_salary':'max_salary',
# 	'min_salary':'min_salary',
# 	'has_fix':'has_fixed'
# }
# import hashlib,time
# def gettime(s):
# 	timeArray = time.strptime(s, "%Y-%m-%d %H:%M:%S")
# 	return int(time.mktime(timeArray))
# s = hashlib.sha256()
# s.update(('123456'+'shengsheng').encode('utf-8'))
# mima = str(s.hexdigest())
# m = sql(db=db)
# m.table('job')
# n = 0
# for x in data:
# 	for y in x:
# 		if y in mapField:
# 			m.__setattr__(mapField[y],x[y])
# 	m.created_time = gettime(x['created'])
# 	m.modified_time = gettime(x['modified'])
# 	m.add()
# 	print('成功添加'+ str(x['id']))

# for x in data:
# 	# for y in x:
# 		# if y in mapField:
# 		# 	m.__setattr__(mapField[y],x[y])
# 	n = n+1
# 	m.table('user')
# 	m.username = x['username']
# 	m.password = '%s' % mima
# 	timeArray = time.strptime(x['created'], "%Y-%m-%d %H:%M:%S")
# 	m.created_time = int(time.mktime(timeArray))
# 	m.add()
# 	m.table('company')
# 	for y in x:
# 		if y in mapField:
# 			m.__setattr__(mapField[y],x[y])
# 	m.created_time = gettime(x['created'])
# 	m.vip_modified_time = gettime(x['vip_modified_time'])
# 	m.modified_time = gettime(x['modified'])
# 	m.user_id = n
# 	m.add()
	
# 	print('成功添加'+ str(n))



