from selenium import webdriver
from inspect import signature


#------------------------------------------------------------------------------#
#               FIRST STEP : OPEN GOOGLE AND SEARCH FOR KEYWORDS               #
#------------------------------------------------------------------------------#

def googleSearch(nURLs, *args):

    # open a new window tab in order to extract information from it
    browser = webdriver.Firefox()

    # generates the formal request according to the keywords
    nKeywords = len(args)
    print("NUMBER OF KEYWORDS : ", nKeywords)

    request = args[0]
    k = 1

    while(k < nKeywords):
        request = request + "+" + args[k]
        k = k + 1

    # open the URL according to the given keywords and the number of results
    browser.get("https://www.google.fr/search?q=" + request + "&num=" + nURLs)

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
        print("POSITION : ", i + 1, ", URL : ", hrefs[i])

    # show the final list of URLs
#   print(hrefs)

    # close the window tab previously generated
    browser.close()

    return hrefs

#------------------------------------------------------------------------------#
#                                TEST SECTION                                  #
#------------------------------------------------------------------------------#

googleSearch("5", "photographe", "islande", "drone", "voyage", "montagne")
