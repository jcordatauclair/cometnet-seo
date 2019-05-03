#!/usr/bin python
# -*- coding: utf-8 -*-
from .URLs import getURLs
from scrapy.http import Request
from searchEngine.items import searchEngineItem

import unidecode
import json
import string
import scrapy


# Fonction chargée de nettoyer une chaîne de caractères
def cleanHTML(strList):

    strListTmp = strList

    strListTmp = [item.lower() for item in strListTmp]

    strListTmp = [unidecode.unidecode(item) for item in strListTmp]

    # !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~ et \n, \r, \t
    charToRemove = string.punctuation + '\n' + '\r' + '\t' + string.digits
    charToRemove = charToRemove.replace("'", '')
    charToRemove = charToRemove.replace("-", '')

    charToReplaceBySpace = ['\xa0', '  ', ' - ', '-', "'"]

    # Suppression des caractères définis plus haut
    for c in charToRemove:
        strListTmp = [item.replace(c, '') for item in strListTmp]

    # Remplacement des caractères définis plus haut par un espace
    for c in charToReplaceBySpace:
        strListTmp = [item.replace(c, ' ') for item in strListTmp]

    strListCleaned = []

    # Création de la liste nettoyée en réglant les quelques problèmes qui ont pu apparaître lors du nettoyage
    for i in range(len(strListTmp)):
        # Strip pour trouver tous les champs vides ('', ' ', '  ', etc)
        if (strListTmp[i].strip() != ''):
            # Suppression des potentiels espaces apparaissant désormais au début ou à la fin de la chaîne
            strListCleaned.append(strListTmp[i].strip())

    return strListCleaned


# Fonction chargée de nettoyer une URL donnée
def cleanURL(URL):

    # Récupération des mots-clés formant l'URL de la page
    itemsCleaned = URL.split('//')[+1].split('/')

    # Suppression du nom de domaine du site
    del itemsCleaned[0]

    # Cas où l'URL se termine par '.../'
    if (itemsCleaned[len(itemsCleaned) - 1] == ''):
        del itemsCleaned[len(itemsCleaned) - 1]

    # Remplacement des caractères définis plus haut par un espace
    charToReplaceBySpace = string.punctuation
    for i in range(len(itemsCleaned)):
        for c in charToReplaceBySpace:
            itemsCleaned[i] = ''.join(
                item.replace(c, ' ') for item in itemsCleaned[i])

    return itemsCleaned


# Classe d'extraction des données HTML
class extractHTML(scrapy.Spider):

    # Nom du robot
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

        # Découpage du nombre d'URLs pour pouvoir en chercher plus de 100 (limite de Google)
        if (nURLs > 100):
            nURLsPage1 = 100
            nURLsPage2 = nURLs - 100
        else:
            nURLsPage1 = nURLs
            nURLsPage2 = 0

        # Si indPage = 1, cela signifie que les résultats sont à chercher sur la première page de la recherche
        self.start_urls = getURLs.googleSearch(nURLsPage1, 1, *keywords)

        # Changement de page : on récupère les résultats allant de 100 à n > 100 (défini dans le fichier de paramétrage)
        if (nURLsPage2 != 0):

            # DEBUG:
            print('\n')
            print('> MOVING ON TO THE NEXT PAGE...')
            print('\n')

            # Si indPage = 2, cela signifie que les résultats sont à chercher sur la deuxième page de la recherche
            self.start_urls.extend(
                getURLs.googleSearch(nURLsPage2, 2, *keywords))

        # Ajout de l'URL de l'utilisateur en position 0
        self.start_urls = [URLtoOptimize] + self.start_urls

    # Fonction chargée d'attribuer la position de chaque URL selon son référencement vis-à-vis de la requête donnée
    # ainsi que son indice de pertinence
    def start_requests(self):

        index = 0

        for url in self.start_urls:

            # Page de l'utilisateur
            if (index == 0):
                yield Request(url, meta={'index': index, 'efficiency': 0})

            # Pages pertinentes
            elif ((index != 0) and (index <= self.nRelevance)):
                yield Request(url, meta={'index': index, 'efficiency': 1})

            # Pages non pertinentes
            else:
                yield Request(url, meta={'index': index, 'efficiency': 2})

            index = index + 1

    # Fonction chargée de traiter les données HTML
    def parse(self, response):

        # DEBUG:
        print('\n')
        print('> PROCESSING... [' + response.url + ']')
        print('\n')

        item = searchEngineItem()

        # URL de la page
        item['url'] = response.url

        # Position de la page
        item['position'] = response.meta['index']

        # Pertinence de la page
        item['relevance'] = response.meta['efficiency']

        # Contenu de la balise 'title'
        item['title'] = cleanHTML(response.xpath('//title//text()').extract())

        # Contenu de l'URL de la page en terme de mots-clés
        item['iurl'] = cleanHTML(cleanURL(response.url))

        # Contenu des balises 'h1', 'h2' et 'h3'
        item['h1'] = cleanHTML(response.xpath('//h1//text()').extract())
        item['h2'] = cleanHTML(response.xpath('//h2//text()').extract())
        item['h3'] = cleanHTML(response.xpath('//h3//text()').extract())

        # Contenu des balises 'strong'
        item['strong'] = cleanHTML(
            response.xpath('//strong//text()').extract())

        # Contenu des balises 'a'
        item['a'] = cleanHTML(response.xpath('//a/text()').extract())

        # Contenu des propriétés 'alt' des balises 'img'
        item['alt'] = cleanHTML(response.xpath('//img//@alt').extract())

        # Contenu des balises 'p'
        item['text'] = cleanHTML(response.xpath('//p/text()').extract())

        yield item

        # DEBUG:
        print('\n')
        print('> DONE')
        print('\n')
