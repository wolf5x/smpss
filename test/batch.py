#!/usr/bin/python
# -*- coding: utf-8 -*-

import HTMLParser
import urlparse
import urllib
import urllib2
import cookielib
import string
import re
import json
import random

hosturl = 'http://localhost'
loginurl = hosturl + '/smpss/index.php/c/main/index'
addgoodsurl = hosturl + '/smpss/index.php/c/stockout/stockout'
outurl = addgoodsurl
outidx = hosturl + '/smpss/index.php/c/stockout/index'

def dopost(url, data, header):
    data = urllib.urlencode(data)
    request = urllib2.Request(url, data, header)
    response = urllib2.urlopen(request)
    rsptext = response.read()
    return rsptext

def login():
    cj = cookielib.LWPCookieJar()
    cookie_support = urllib2.HTTPCookieProcessor(cj)
    opener = urllib2.build_opener(cookie_support, urllib2.HTTPHandler)
    urllib2.install_opener(opener)
    h = urllib2.urlopen(hosturl)
    postData = {
            'username' : 'admin',
            'pwd' : 'admin123'
            }
    dopost(loginurl, postData, {})

def addgoods():
    idb = 12;
    for i in xrange(1,11):
        d = {
                'goods_id': "%d" %(idb+i),
                'goods_sn': "%d" %(10000+i),
                'goods_name_chn' : "a%d" %(i),
                'goods_name_tha' : "b%d" %(i),
                'goods_pack_num' : 10,
                'goods_pack_size' : 1,
                'goods_unitprice' : 1,
                'goods_totalprice' : 10,
                'ac' : 'ajaxaddyes'
                }
        dopost(addgoodsurl, d, {})

def dostockout():
    addgoods()
    d = {
            'ac' : 'ajaxout',
            'customer_name' : 'aaa'
            }
    dopost(outurl, d, {})

def fetchsid():
    d = {
            'ac' : 'sidlist'
            }
    raw = dopost(outurl, d, {})
    print raw
    lst = json.loads(raw)
    if type(lst) is list and len(lst) > 0:
        return lst[random.randint(0, len(lst)-1)]
    else:
        return None

def undostockout():
    sid = fetchsid()
    if sid != None:
        d = {
                'ac' : 'del',
                'stockout_sn' : sid
                }
        dopost(outidx, d, {})

if __name__ == '__main__':
    """"""
    login()
    while True:
        if random.randint(0,1) == 0:
            dostockout()
        else:
            undostockout()

