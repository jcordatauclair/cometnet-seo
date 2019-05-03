#!/usr/bin python
# -*- coding: utf-8 -*-
from .URLs import getURLs
from scrapy.http import Request
from searchEngine.items import searchEngineItem
import unidecode
import ast
import json
import string
import scrapy


def cleanHTML(strList):

    strListTmp = strList

    # lowercase
    strListTmp = [itemElem.lower() for itemElem in strListTmp]
    # remove accents
    strListTmp = [unidecode.unidecode(itemElem) for itemElem in strListTmp]

    # !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~ and \n, \r, \ŧ
    charToRemove = string.punctuation + '\n' + '\r' + '\t'
    charToRemove = charToRemove.replace("'", '')

    # corresponds to a no-break space in latin-1 unicode
    charToReplaceBySpace = ['\xa0', '  ', "'"]

    # remove the characters defined above
    for c in charToRemove:
        strListTmp = [itemElem.replace(c, '') for itemElem in strListTmp]

    # replace the characters defined above by a space
    for c in charToReplaceBySpace:
        strListTmp = [itemElem.replace(c, ' ') for itemElem in strListTmp]

    strListCleaned = []
    # create the cleaned list of results by removing the empty fields
    for i in range(len(strList)):
        # strip to find all the fields without any text inside it ('', ' ', '  ' ...)
        if (strListTmp[i].strip() != ''):
            # create the clean list and strip again to remove useless spaces @nail/tail
            strListCleaned.append(strListTmp[i].strip())

    return strListCleaned


def cleanURL(URL):

    itemsCleaned = URL.split('//')[+1].split('/')

    if (itemsCleaned[len(itemsCleaned) - 1] == ''):
        del itemsCleaned[len(itemsCleaned) - 1]

    charToReplaceBySpace = string.punctuation
    for i in range(len(itemsCleaned)):
        for c in charToReplaceBySpace:
            itemsCleaned[i] = ''.join(
                item.replace(c, ' ') for item in itemsCleaned[i])

    return itemsCleaned


class extractHTML(scrapy.Spider):

    # name of the spider
    name = 'extractHTML'

    def __init__(self,
                 nURLs=None,
                 nRelevance=None,
                 keywords=None,
                 URLtoOptimize=None):

        self.keywords = keywords
        self.URLtoOptimize = URLtoOptimize
        self.nURLs = nURLs
        self.nRelevance = nRelevance

        self.start_urls = getURLs.googleSearch(nURLs, *keywords)
        self.start_urls = [URLtoOptimize] + self.start_urls

    # attribute the position of each URL in the search page (indexing order)
    #           the efficiency score of each URL in the search page
    def start_requests(self):

        index = 0

        for url in self.start_urls:

            if ((index != 0) and (index <= self.nRelevance)):
                yield Request(url, meta={'index': index, 'efficiency': 1})
            else:
                yield Request(url, meta={'index': index, 'efficiency': 2})

            index = index + 1

    # HTML parser
    def parse(self, response):

        # DEBUG:
        print('\n')
        print('> PROCESSING... [' + response.url + ']')
        print('\n')

        item = searchEngineItem()

        # url of the page
        item['url'] = response.url

        # indexing position of the page in the google result relative to the request
        item['position'] = response.meta['index']

        # relevance of the result (is the SEO efficient or not for this page)
        item['relevance'] = response.meta['efficiency']

        # link of the page + remove the '' generated if the URL ends by '/'
        item['itemsURL'] = cleanURL(response.url)

        # title of the page
        item['title'] = cleanHTML(response.xpath('//title//text()').extract())

        # strong tags
        item['strong'] = cleanHTML(
            response.xpath('//strong//text()').extract())

        # alt of the images
        item['img'] = cleanHTML(response.xpath('//img//@alt').extract())

        # headings
        item['h1'] = cleanHTML(response.xpath('//h1//text()').extract())
        item['h2'] = cleanHTML(response.xpath('//h2//text()').extract())
        item['h3'] = cleanHTML(response.xpath('//h3//text()').extract())
        item['h4'] = cleanHTML(response.xpath('//h4//text()').extract())
        item['h5'] = cleanHTML(response.xpath('//h5//text()').extract())
        item['h6'] = cleanHTML(response.xpath('//h6//text()').extract())

        # text of the page which can be found in p tags
        item['text'] = cleanHTML(response.xpath('//p/text()').extract())

        yield item

        # DEBUG:
        print('\n')
        print('> DONE')
        print('\n')
