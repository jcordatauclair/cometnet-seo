from selenium import webdriver


#------------------------------------------------------------------------------#
#           FIRST STEP : OPEN GOOGLE AND SEARCH FOR THREE KEYWORDS             #
#------------------------------------------------------------------------------#

def googleSearch(keyword1, keyword2, keyword3):

    # open a new window tab in order to extract information from it
    browser = webdriver.Firefox()

    # open the URL according to the given keywords
    browser.get("https://www.google.fr/search?q=" + keyword1 + "+" + keyword2 + "+" + keyword3)

    # make sure the request isn't empty
    assert "No results found." not in browser.page_source

#------------------------------------------------------------------------------#
#          SECOND STEP : GET THE FIRST URL FROM THE PAGE AND PRINT IT          #
#------------------------------------------------------------------------------#

    # find all the tags that define the presence of an URL
    results = browser.find_elements_by_class_name('r')

    # extract the first one from this list
    link = results[0].find_element_by_tag_name('a')
    href = link.get_attribute('href')

    # show the first URL
    print(href)

    # close the window tab previously generated
    browser.close()

#------------------------------------------------------------------------------#
#                                TEST SECTION                                  #
#------------------------------------------------------------------------------#

print(googleSearch("photographe", "islande", "drone"))
