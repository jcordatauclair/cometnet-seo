from scrapy.crawler import CrawlerProcess
from scrapy.utils.project import get_project_settings
from searchEngine.spiders import extractHTML
import logging
import os
import sys
import string
import json

#------------------------------------------------------------------------------#
#                                    LOGFILE                                   #
#------------------------------------------------------------------------------#
pathToLogFile = 'log/logfile.log'


class Log(object):
    def __init__(self):
        self.terminal = sys.stdout
        self.log = open('log/logfile.log', 'a')

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)

    def flush(self):
        # needed for python 3 compatibility
        pass


sys.stdout = Log()
#------------------------------------------------------------------------------#

# DEBUG:
print('\n')
print(' ╔═════════════════════╗ ')
print(' ║ Process Initialized ║ ')
print(' ╚═════════════════════╝ ')
print('\n')

#------------------------------------------------------------------------------#
#                                  PARAMETERS                                  #
#------------------------------------------------------------------------------#
with open("param/parameters.txt", "r") as paramFile:
    parameters = []
    for line in paramFile:
        parameters.append(line)

if ((len(parameters) < 4) or (len(parameters) > 14)):
    raise ValueError('Invalid number of parameters')
else:
    # URL of the user
    userURL = str(parameters[0].rstrip())
    # number of URLs to analyze
    nResults = int(parameters[1])
    # limit of SEO efficiency
    efficiency = int(parameters[2])
    # keywords
    setKeywords = []
    for i in range(3, len(parameters)):
        setKeywords.append(parameters[i].rstrip())

# DEBUG:
print('▐ User URL : "' + userURL.rstrip() + '"')
print('▐ ' + str(len(setKeywords)) + ' keywords : ' +
      str(setKeywords).replace('[', '').replace(']', '').replace("'", '"'))
print('▐ ' + str(nResults) + ' results requested')
print('▐ Relevance rank : ' + str(efficiency))
print('\n')
#------------------------------------------------------------------------------#

pathToFileToSort = 'searchEngine/output/unsortedOutput.jsonl'
pathToFileSorted = 'searchEngine/output/output.json'

# clean the output file
#if os.path.isfile(pathToFileSorted):
#    previousSortedOutput = open(pathToFileSorted, 'r+')
#    previousSortedOutput.truncate()
#-    previousSortedOutput.close()

# run the process
process = CrawlerProcess(get_project_settings())

# start crawling
process.crawl(
    'extractHTML',
    nURLs=nResults,
    nRelevance=efficiency,
    keywords=setKeywords,
    URLtoOptimize=userURL)

# DEBUG:
print('\n')
print(' ╔════════════════════════╗ ')
print(' ║ Extraction Initialized ║ ')
print(' ╚════════════════════════╝ ')
print('\n')

# the script will block here until the crawling is finished
process.start()

# DEBUG:
print('\n')
print(' ╔═══════════════════════╗ ')
print(' ║ Extraction Successful ║ ')
print(' ╚═══════════════════════╝ ')
print('\n')

# sort the output file according to the position relative to the indexing order
# DEBUG:
print('▐ SORTING THE OUTPUT...')

with open(pathToFileToSort, 'r+') as notSortedJSON:
    items = []
    while True:
        item = notSortedJSON.readline()
        if not item:
            break
        item = item.strip()
        objJSON = json.loads(item)
        items.append(objJSON)

items = sorted(items, key=lambda k: k['position'])

# DEBUG:
print('\n')
print('▐ GENERATING THE FINAL OUTPUT...')

with open(pathToFileSorted, 'w') as sortedJSON:
    # replace ' with " in order to create a valid JSON file
    sortedJSON.write(str(items).replace("'", '"'))

# DEBUG:
print('\n')
print('▐ DONE')

# DEBUG:
print('\n')
print('▐ INFO : the path to the output is "' + pathToFileSorted + '"')
print('▐        a logfile can be found at "' + pathToLogFile + '"')

os.remove(pathToFileToSort)

# DEBUG:
print('\n')
print(' ╔════════════════╗ ')
print(' ║ Ending Process ║ ')
print(' ╚════════════════╝ ')
print('\n')
