#!/usr/bin python
# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from selenium.common.exceptions import StaleElementReferenceException
from pyvirtualdisplay import Display

# Fonction chargée de renvoyer les URLs des pages affichées lors d'une requête Google donnée
def googleSearch(nURLs, indPage, *keywords):

    # Initialisation du navigateur
    display = Display(visible=0, size=(800, 600))
    display.start()

    # Masquage du navigateur
    options = Options()
    options.set_headless(headless=True)

    # Firefox
    binary = FirefoxBinary('/usr/bin/firefox')

    # Initialisation de Firefox
    browser = webdriver.Firefox(
        firefox_binary=binary,
        firefox_options=options,
        executable_path='/usr/local/bin/geckodriver')

    # DEBUG:
    print('\n')
    print(' -------------------------------- ')
    print('   Headless Firefox Initialized   ')
    print(' -------------------------------- ')
    print('\n')

    nKeywords = len(keywords)

    # Gestion d'erreur : ValueError en cas de problème avec le nombre de mots-clés
    if ((nKeywords > 10) or (nKeywords == 0)):
        browser.close()
        raise ValueError(
            'The number of keywords entered is not correct (max is 10, min is 1)\n'
        )

    if ((nURLs < 1) or (nURLs > 200)):
        browser.close()
        raise ValueError(
            'The number of URLs requested is not correct (min is 1, max is 200)\n'
        )

    # Génération de la requête selon le nombre de résultats et les mots-clés
    request = keywords[0]
    k = 1
    while (k < nKeywords):
        request = request + '+' + keywords[k]
        k = k + 1

    # DEBUG:
    print('> GENERATING THE GOOGLE REQUEST...')
    print('\n')

    if (indPage == 1):
        browser.get('https://www.google.fr/search?q=' + request + '&num=' + str(nURLs))
    elif (indPage == 2):
        browser.get('https://www.google.fr/search?q=' + request + '&num=' + str(nURLs) + '&start=100&sa=N')

    # Vérification que la page n'est pas vide
    assert 'No results found.' not in browser.page_source

    # DEBUG:
    print('\n')
    print('> SEARCHING FOR THE URLs...')
    print('\n')

    # Recherche de toutes les balises définissant la présence d'une URL
    results = browser.find_elements_by_class_name('g')

    # DEBUG: pour voir s'il y a un code captcha sur la page
    # print('\n')
    # print(browser.page_source)
    # print('\n')

    lenResults = len(results)

    # Si la page n'est pas vide mais qu'il n'y a pas d'URL, cela signifie que Google les protègent
    if (lenResults == 0):
        raise ValueError('> ERROR : Google protects the results with a Captcha code\n')

    links = []
    hrefs = []
    hrefsClean = []

    # Remplissage des listes pour chaque URL trouvée sur la page
    for i in range(lenResults):
        links.append(results[i].find_element_by_tag_name('a'))
        hrefs.append(links[i].get_attribute('href'))

        # Cas où un lien vers Google image est présent : suppression de celui-ci car il n'a pas de lien avec le référencement
        if 'https://www.google.fr/search?' not in hrefs[i]:
            hrefsClean.append(hrefs[i])
        else:
            print('\n')
            print('> WARNING : Google displays a google image link for this request (-1 result)')
            print('\n')

    # Calcul du nombre d'URL supprimées par Google pour la requête donnée (droits d'auteur par exemple)
    delURLs = int(nURLs) - len(hrefs)

    # Messages indiquant à l'utilisateur que des URLs ont été supprimées ou non
    print('\n')
    if (delURLs != 0):
        # WARNING:
        print('> WARNING : Google deleted some URLs for this request')
    else:
        # WARNING:
        print('> INFO : Google did not delete any URL for this request')
    print('\n')

    # Fermeture de la fenêtre de naviguation
    browser.close()

    # DEBUG: pour afficher le tableau final comprenant les URLs 
    print('\n')
    print('hrefsClean : ' + str(len(hrefsClean)) + ' items')
    print('\n')

    return hrefsClean


# TEMP: POUR TESTER, DECOMMENTER LA LIGNE CI-DESSOUS ET EXECUTER LE SCRIPT
# googleSearch(100, 1, 'photographer', 'iceland', 'drone')
