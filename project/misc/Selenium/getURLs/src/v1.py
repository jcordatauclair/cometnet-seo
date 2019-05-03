from selenium import webdriver


#------------------------------------------------------------------------------#
#                   FIRST STEP : OPEN A GOOGLE REQUEST PAGE                    #
#------------------------------------------------------------------------------#

# open a new window tab in order to extract information from it
browser = webdriver.Firefox()

# open the URL
browser.get("https://www.google.com/search?client=ubuntu&channel=fs&q=photographe+islande+drone&ie=utf-8&oe=utf-8")

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
