#==================================TUTORIAL====================================#
# a Firefox page is opened with the URL "http://www.python.org". When the page #
# is fully loaded, a verification is made to check that the title contains     #
# the word "Python", then an element of the page named "q" (for query) is      #
# found and the word "pycon" is written in it. A simulation of the key RETURN  #
# is made just like a user would do for a basic research in a website. Finally #
# it is ensured that a result is found and the window tab is closed.           #
#==============================================================================#

from selenium import webdriver # Firefox, Chrome, IE and Remote
from selenium.webdriver.common.keys import Keys # keyboard keys like F1, ALT...


driver = webdriver.Firefox() # an instance of Firefox webdriver is created

driver.get("http://www.python.org") # navigates to the given page, waits until
                                    # the page has fully loaded before returning
                                    # control to the test/script

assert "Python" in driver.title # confirms that the title contains "Python"

elem = driver.find_element_by_name("q") # finding an element

elem.clear()
elem.send_keys("pycon")
elem.send_keys(Keys.RETURN) # sending keys

assert "No results found." not in driver.page_source # ensures a result is found

driver.close() # the browser window is closed
