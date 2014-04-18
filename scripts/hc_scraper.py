import urllib2
import re
import pdb
import time

"""
this scraper is a utility to try to map old joomla urls to joomla content ids

theoretically this could help create redirects for old urls to new wordpress urls, 
if the ingestion script were modified to map the old url to new url at import by inspecting the ids 

alas, i'm lazy and not interested writing much more php for ingestion.py
plus, joomla url structures and page source contents have varied over time, making this overkill for low traffic site
with good search
"""

pg_no = 1
threshold = 415
baseurl = 'http://headlineclub.org/component/content/article/'

logger = open('logger.txt','w')
url_file = open('url_file.txt','w')

while pg_no < threshold:
    print pg_no
    fullurl = baseurl + str(pg_no) 
    try:
        #pdb.set_trace()     
        page = urllib2.urlopen(fullurl)
        time.sleep(5)
        pagetxt = page.read()
        urlgroups = re.search(r'(<a href=\"/component/content/article/3-default/)(.*)(\.pdf)',pagetxt)
        urlslug = urlgroups.group(2)
        jurl = 'http://headlineclub.org/component/content/article/' + urlslug + '.html'
        msg = 'success: ' + jurl
        url_file.write(jurl + '\n')
        print msg
        logger.write(msg)
    except:
        msg = 'fail: ' + fullurl
        print msg
        logger.write(msg)
    pg_no += 1
    logger.write('\n')

logger.close()
url_file.close()
