#!/usr/bin python
# -*- coding: utf-8 -*-
from scrapy.crawler import CrawlerProcess
from scrapy.utils.project import get_project_settings
from searchEngine.spiders import extractHTML

import hunspell
import os
import sys
import codecs
import string
import json

#------------------------------------------------------------------------------#
#                                FICHIER DE LOG                                #
#------------------------------------------------------------------------------#
pathToLogFile = '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/log/logfile.log'


class Logger(object):
    def __init__(self, filename=pathToLogFile):
        self.terminal = sys.stderr
        self.log = open(filename, "w")

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)


sys.stderr = Logger(pathToLogFile)
#------------------------------------------------------------------------------#

# DEBUG:
print('\n')
print(' ----------------------- ')
print('   Process Initialized   ')
print(' ----------------------- ')
print('\n')

#------------------------------------------------------------------------------#
#                                  PARAMÈTRES                                  #
#------------------------------------------------------------------------------#
with codecs.open(
        '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/param/parameters.txt',
        mode='r',
        encoding='utf-8') as paramFile:
    parameters = []
    for line in paramFile:
        parameters.append(line)

if ((len(parameters) < 4) or (len(parameters) > 14)):
    raise ValueError('Invalid number of parameters')
else:
    # URL de l'utilisateur
    userURL = str(parameters[0].rstrip())
    # Nombre d'URLs à étudier
    nResults = int(parameters[1])
    # Degré de pertinence
    efficiency = int(parameters[2])
    # Mots-clés
    setKeywords = []
    for i in range(3, len(parameters)):
        setKeywords.append(parameters[i].rstrip())

# DEBUG:
print('> User URL : "' + userURL.rstrip() + '"')
print('> ' + str(len(setKeywords)) + ' keywords : ' +
      str(setKeywords).replace('[', '').replace(']', '').replace("'", '"'))
print('> ' + str(nResults) + ' results requested')
print('> Relevance rank : ' + str(efficiency))
print('\n')
#------------------------------------------------------------------------------#

pathToFileSorted = '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/searchEngine/output/output.json'
pathToFileToSort = '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/searchEngine/output/unsortedOutput.jsonl'

# On vide le fichier contenant les résultats du scraping précédent
open(pathToFileToSort, 'w+').close()

# On excécute le processus
process = CrawlerProcess(get_project_settings())

# Initialisation du crawling (appel à extractHTML.py)
process.crawl(
    'extractHTML',
    nURLs=nResults,
    nRelevance=efficiency,
    keywords=setKeywords,
    URLtoOptimize=userURL)

# DEBUG:
print('\n')
print(' -------------------------- ')
print('   Extraction Initialized   ')
print(' -------------------------- ')
print('\n')

# Lancement du processus
process.start()

# DEBUG:
print('\n')
print(' ------------------------- ')
print('   Extraction Successful   ')
print(' ------------------------- ')
print('\n')

# DEBUG:
print('> SORTING THE OUTPUT...')

# Tri du fichier de sortie selon l'ordre donné lors par le référencement
with open(pathToFileToSort, mode='r+') as notSortedJSON:
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
print('> GENERATING THE FINAL OUTPUT...')

# Génération d'un nouveau fichier de sortie trié et valide qui peut être lu par PHP
with open(pathToFileSorted, mode='w') as sortedJSON:
    # replace ' with " in order to create a valid JSON file
    sortedJSON.write(str(json.dumps(items)).replace("'", '"'))

# DEBUG:
print('\n')
print('> DONE')

# DEBUG:
print('\n')
print('> INFO : the path to the output is "' + pathToFileSorted + '"')
print('>        a logfile can be found at "' + pathToLogFile + '"')

# DEBUG:
print('\n')
print(' ------------------ ')
print('   Ending Process   ')
print(' ------------------ ')
print('\n')
