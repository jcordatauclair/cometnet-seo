################################################################################
######################################################################### IMPORT
################################################################################

import Queue
import os
import json
from random import sample
import string
import sys
import threading
import time
import urllib2
import xml.etree.cElementTree as ET

################################################################################
###################################################################### FONCTIONS
################################################################################

# fonction qui crawle une url
def read_url(key, url, queue):
  try:
    # j'ouvre l'url
    URLfile = urllib2.urlopen(url, None, 5)
    
    # si je n'ai pas d'erreur HTTP autre que 200
    if URLfile.getcode() == 200:
            
      # je MAJ mon xml general
      unResultatGG = ET.SubElement(allResultatsGG, "unResultatGG")
      ET.SubElement(unResultatGG, "url").text = url
      ET.SubElement(unResultatGG, "position").text = str(key+1)
      ET.SubElement(unResultatGG, "fichierSource").text = str(key+1)+".txt"
      
      # je cree un fichier contenant le code recupere
      with open(pathDossierListeUrl + "/" + str(key+1)+".txt", "a") as output:
        output.write(URLfile.read())
        
      print '# '+url
  except:
      print 'ERROR crawl '+url;
  
  
# fonction qui appelle la lecture d'une liste de pages de maniere parallele
def fetch_parallel():
  result = Queue.Queue()
  threads = [threading.Thread(target=read_url, args=(key, url, result)) for key, url in enumerate(listeUrl)]
  for t in threads:
    t.start()
  for t in threads:
    t.join()
  return result


# fonction qui une appelle la lecture d'une liste de pages de maniere sequentielle
#def fetch_sequencial():
#  result = Queue.Queue()
#  for key, url in enumerate(listeUrl):
#    read_url(key, url, result)
#  return result


################################################################################
###################################################################### EXECUTION
################################################################################

# je verifie si le dossier qui va contenir les txt des pages crawlees existe sinon je le cree
pathDossierListeUrl = sys.path[0] + "/../../../../var/SEOechoCrawl/" + sys.argv[1]

if not os.path.exists(pathDossierListeUrl):
  os.makedirs(pathDossierListeUrl)

nombrePagesResultatsGoogleSouhaite = 4 
start = 0
listeUrl = []

# je cree une boucle pour appel des URL Google
for i in range(0, nombrePagesResultatsGoogleSouhaite):
  if start == 0:
    url = urllib2.urlopen('https://www.googleapis.com/customsearch/v1?key=AIzaSyBiKPo1nBZqiiIS6lTzUvXk5HcCuzWt0no&q='+sys.argv[2]+'&cx=004068440184852771532:mfxmatkrisq')
  else:
    url = urllib2.urlopen('https://www.googleapis.com/customsearch/v1?key=AIzaSyBiKPo1nBZqiiIS6lTzUvXk5HcCuzWt0no&q='+sys.argv[2]+'&cx=004068440184852771532:mfxmatkrisq&start='+str(start))

  allResultatsGG = json.load(url)

  # pour chaque URL retournee par GG
  for items in allResultatsGG["items"]:
    listeUrl.append(items["link"])
  
  start = start+10
  
  
# creation d'un xml general qui contiendra tout
root = ET.Element("root")
allResultatsGG = ET.SubElement(root, "allResultatsGG")

#lancement de la fonction de crawl en multi-thread
start_time = time.time()  
fetch_parallel();
interval = time.time() - start_time  
print 'Total time in seconds:', interval  

# MAJ du xml general
tree = ET.ElementTree(root)
tree.write(pathDossierListeUrl + "/resultatsGG.xml")
