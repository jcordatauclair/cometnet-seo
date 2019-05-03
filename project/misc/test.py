import Scrapy
import jsonlines
import json
import string
import matplotlib.pyplot as plt

#------------------------------------------------------------------------------#
#                  LOWERCASE TEST : putting a text in lowercase                #
#------------------------------------------------------------------------------#
text = 'HELLOWORLD'
textLowercase = 'HELLOWORLD'.lower()

print("LOWERCASE TEST : ", textLowercase)
#------------------------------------------------------------------------------#

#------------------------------------------------------------------------------#
#                   LINK TEST : keeping the link part of an URL                #
#------------------------------------------------------------------------------#
URL = 'http://a.tv/interstellar/citations/'
tabChar = []

for i in range(len(URL)):
    if ((URL[i] == '/') and (URL[i + 1] != '/') and (URL[i - 1] != '/')):
        for j in range(i + 1, len(URL)):
            tabChar.append(URL[j])
        break
    else:
        i = i + 1

link1 = ''.join(str(k) for k in tabChar)

print("LINK TEST 1 : first slash's position is", i, "- the link is", link1)

# OR

link2 = URL.split('//')[+1].split('/')

print("LINK TEST 2 : ", link2)
#------------------------------------------------------------------------------#

#------------------------------------------------------------------------------#
#                              BENCHMARKING PLOT                               #
#------------------------------------------------------------------------------#
plt.plot([1, 10, 25, 50, 100], [7.64, 11.16, 15.02, 20.00, 25.25], 'ro')
plt.axis([0, 110, 0, 30])
plt.xlabel('number of URLs')
plt.ylabel('execution time (sec)')
# plt.show()
#------------------------------------------------------------------------------#

#------------------------------------------------------------------------------#
#                         REMOVING \t, \r, \n IN A TEXT                        #
#------------------------------------------------------------------------------#
text = 'je suis \n\r en \t stage \r\t\n \t \n \r pendant \r\n 3 \n\t mois'
text = text.split()

for i in range(len(text)):
    if (text[i] == '\n' or text[i] == '\t' or text[i] == '\r'):
        text.remove(text[i])

print('REMOVE n,r,t : ', text)
#------------------------------------------------------------------------------#

#------------------------------------------------------------------------------#
#                       REMOVING \t, \r, \n IN A STRLIST                       #
#------------------------------------------------------------------------------#
list = [
    'je suis \n\r en \t stage \r\t\n \t \n \r pendant \r\n 3 \n\t mois',
    'je suis \n\r en \t stage \r\t\n \t \n \r', 'je suis \n\r en'
]
cleanList = [] * len(list)

for j in range(len(list)):
    text = list[j]
    text = text.split()
    for i in range(len(text)):
        if (text[i] == '\n' or text[i] == '\t' or text[i] == '\r'):
            text.remove(text[i])
    cleanList.append(text)

print('REMOVE n,r,t : ', cleanList)
#------------------------------------------------------------------------------#

#------------------------------------------------------------------------------#
#                                   STRIP TEST                                 #
#------------------------------------------------------------------------------#
txt = ' strip me '
print('STRIP ME TEST :', txt.strip())
#------------------------------------------------------------------------------#

print('\n')

#------------------------------------------------------------------------------#
#                                   SORT JSON                                  #
#------------------------------------------------------------------------------#
jsonlList = [{
    'position': 2,
    'title': 'Photo'
}, {
    'position': 3,
    'title': 'Voyage'
}, {
    'position': 1,
    'title': 'Sport'
}, {
    'position': 4,
    'title': 'Site web'
}]

sortedList = sorted(jsonlList, key=lambda k: k['position'])
print(sortedList)
#------------------------------------------------------------------------------#

print('\n')

#------------------------------------------------------------------------------#
#                             GET PARAM FROM TXT                               #
#------------------------------------------------------------------------------#
with open("../searchEngine/param/parameters.txt", "r") as paramFile:
    parameters = []
    for line in paramFile:
        parameters.append(line)

URL = parameters[0]
n = parameters[1]
limit = parameters[2]
keywords = []

lenParam = len(parameters)
if ((lenParam < 4) or (lenParam > 14)):
    raise ValueError('Invalid number of parameters')
else:
    for i in range(3, lenParam):
        keywords.append(parameters[i])

print('Parameters : ' + str(parameters) + ', ' + str(lenParam) + ' parameters')
print('URL : ' + URL)
print('n : ' + n)
print('Limit : ' + limit)
print('Keywords : ' + str(keywords))
#------------------------------------------------------------------------------#

print('\n')

#------------------------------------------------------------------------------#
#                             CLEAN URL ITEMS                                  #
#------------------------------------------------------------------------------#
itemsCleaned = URL.strip().split('//')[+1].split('/')

if (itemsCleaned[len(itemsCleaned) - 1] == ''):
    del itemsCleaned[len(itemsCleaned) - 1]

charToReplaceBySpace = string.punctuation
for i in range(len(itemsCleaned)):
    for c in charToReplaceBySpace:
        itemsCleaned[i] = ''.join(
            item.replace(c, ' ') for item in itemsCleaned[i])

print(itemsCleaned)
#------------------------------------------------------------------------------#
