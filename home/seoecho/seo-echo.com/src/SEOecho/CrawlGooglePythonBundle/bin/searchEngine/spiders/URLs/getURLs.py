#!/usr/bin python
# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from pyvirtualdisplay import Display

#------------------------------------------------------------------------------#
#               FIRST STEP : OPEN GOOGLE AND SEARCH FOR KEYWORDS               #
#------------------------------------------------------------------------------#


def googleSearch(nURLs, *keywords):

    # initialize the driver
    display = Display(visible=0, size=(800, 600))
    display.start()

    # display the browser
    options = Options()
    options.set_headless(headless=True)

    # locate Firefox
    binary = FirefoxBinary('/usr/bin/firefox')

    # open a new window tab in order to extract information from it
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

    # ValueError messages in case there is an issue with the arguments
    if ((nKeywords > 10) or (nKeywords == 0)):
        browser.close()
        raise ValueError(
            'The number of keywords entered is not correct (max is 10, min is 1)\n'
        )

    if ((nURLs > 100) or (nURLs < 1)):
        browser.close()
        raise ValueError(
            'The number of URLs requested is not correct (max is 100, min is 1)\n'
        )

    # generate the request according to the keywords and nURLs
    request = keywords[0]
    k = 1

    while (k < nKeywords):
        request = request + '+' + keywords[k]
        k = k + 1

    # DEBUG:
    print('> GENERATING THE GOOGLE REQUEST...')
    print('\n')

    # open the URL thanks to the request
    browser.get('https://www.google.fr/search?q=' + request + '&num=' +
                str(nURLs))

    # make sure the request isn't empty
    assert 'No results found.' not in browser.page_source

    #------------------------------------------------------------------------------#
    #                     SECOND STEP : GET THE URLS AND PRINT IT                  #
    #------------------------------------------------------------------------------#

    # DEBUG:
    print('\n')
    print('> SEARCHING FOR THE URLs...')
    print('\n')

    # find all the tags that define the presence of an URL
    results = browser.find_elements_by_class_name('g')
    lenResults = len(results)

    if (lenResults == 0):
        raise ValueError('No results were found for this request\n')

    # lists of lenResults elements : [None, None, None, ...]
    links = [None] * lenResults
    hrefs = [None] * lenResults

    # list of the clean links (without any google page link)
    hrefsClean = []

    # fill the lists for each URL found on the page
    for i in range(lenResults):
        links[i] = results[i].find_element_by_tag_name('a')
        hrefs[i] = links[i].get_attribute('href')

        # case if there is a link to googe images (robots.txt issue)
        # problematic since it won't be displayed in the ouput (-1 result)
        if 'https://www.google.fr/search?' not in hrefs[i]:
            hrefsClean.append(hrefs[i])
        else:
            print('\n')
            print(
                '> WARNING : Google displays a google image link for this request (-1 result)'
            )
            print('\n')

    # check if some URLs have been deleted from Google for the current research
    delURLs = int(nURLs) - len(hrefs)

    # WARN/INFO messages concerning the suppression of some pages in the results
    print('\n')
    if (delURLs != 0):
        # WARNING:
        print('> WARNING : Google deleted some URLs for this request (' +
              str(delURLs) + ')')
    else:
        # WARNING:
        print('> INFO : Google did not delete any URL for this request')
    print('\n')

    # close the window tab previously generated
    browser.close()

    return hrefsClean


#------------------------------------------------------------------------------#
#                                TEST SECTION                                  #
#------------------------------------------------------------------------------#

# TEMP:
# googleSearch(10, 'photographer', 'iceland', 'drone')
