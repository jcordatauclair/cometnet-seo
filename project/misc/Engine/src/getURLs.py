from selenium import webdriver
from selenium.webdriver.firefox.options import Options
import timeit


#------------------------------------------------------------------------------#
#               FIRST STEP : OPEN GOOGLE AND SEARCH FOR KEYWORDS               #
#------------------------------------------------------------------------------#

def googleSearch(nURLs, *args):


    #--------------------------------------------------------------------------#
    # start the timer
    start = timeit.default_timer()
    #--------------------------------------------------------------------------#


    # display the browser
    options = Options()
    options.set_headless(headless=True)

    # open a new window tab in order to extract information from it
    browser = webdriver.Firefox(firefox_options=options)

    print("\n")
    print("Headless Firefox initialized")
    print("\n")

    nKeywords = len(args)

    # ValueError messages in case there is an issue with the arguments
    if((nKeywords > 10) or (nKeywords == 0)):
        browser.close()
        raise ValueError('The number of keywords entered is not correct (max is 10, min is 1)')

    if((nURLs > 100) or (nURLs == 0)):
        browser.close()
        raise ValueError('The number of URLs requested is too high (max is 100)')

    # generate the request according to the keywords and nURLs
    request = args[0]
    k = 1

    while(k < nKeywords):
        request = request + "+" + args[k]
        k = k + 1

    # open the URL thanks to the request
    browser.get("https://www.google.fr/search?q=" + request + "&num=" + str(nURLs))

    # make sure the request isn't empty
    assert "No results found." not in browser.page_source

#------------------------------------------------------------------------------#
#             SECOND STEP : GET THE 100 FIRST URLS AND PRINT IT                #
#------------------------------------------------------------------------------#

    # find all the tags that define the presence of an URL
    results = browser.find_elements_by_class_name('r')
    lenResults = len(results)

    # lists of lenResults elements : [None, None, None, ...]
    links = [None]*lenResults
    hrefs = [None]*lenResults

    # fill the lists for each URL found on the page
    for i in range(lenResults):
        links[i] = results[i].find_element_by_tag_name('a')
        hrefs[i] = links[i].get_attribute('href')
#       print("POSITION : ", i + 1, ", URL : ", hrefs[i])

    # show the final list of URLs
#   print(hrefs)

    # check if some URLs have been deleted from Google for the current research
    delURLs = int(nURLs) - len(hrefs)

    print("\n")
    if(delURLs != 0):
        print("WARNING : Google deleted", delURLs, "URLs for this research")
    else:
        print("INFO : Google didn't delete any URL for this research")

    # close the window tab previously generated
    browser.close()


    #---------------------------------------------------------------------------#
    # stop the timer
    stop = timeit.default_timer()

    # compute the execution time
    execTime = stop - start

    # display it
    print("\n")
    print("EXECUTION TIME(getURLs.py) = ", execTime, "sec")
    print("|", str(nURLs), " URLs requested")
    print("|", nKeywords, " keywords entered")
    print("\n")
    #--------------------------------------------------------------------------#


    return hrefs

#------------------------------------------------------------------------------#
#                                TEST SECTION                                  #
#------------------------------------------------------------------------------#

# googleSearch(100, "manger", "boire", "dormir")
