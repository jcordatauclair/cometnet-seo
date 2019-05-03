### <p align="center"> **RESSOURCES** </p>

# <p align="center"> Internship at **Com&Net** </p>

<p align="center">
  <img src="/ressources/logocometnet.png">
</p>

<br/>

## **Table of Contents**

-   [Members](#team-members)
-   Project
    -   [the search engine](#searchengine)
        -   [files overview](#searchengine_filesoverview)
            -   [getURLs.py](#getURLs)
                -   [description of the file](#getURLs_description)
                -   [process](#getURLs_process)
            -   [extractHTML.py](#extractHTML)
                -   [description of the file](#extractHTML_description)
                -   [extracted data](#extractHTML_data)
                -   [data cleaning](#extractHTML_cleaning)
                -   [benchmarking](#extractHTML_benchmarking)
            -   [scrape.py](#scrape)
                -   [description of the file](#scrape_description)
                -   [program run](#scrape_run)
            -   [other files](#searchengine_otherfiles)
                -   [items.py](#otherfiles_items)
                -   [settings.py](#otherfiles_settings)
                -   [scrapy.cfg](#otherfiles_config)
        -   [sample test](#searchengine_sampletest)
        -   [diagram](#searchengine_diagram)
    -   [integration with the learning machine](#machinelearning)
    -   [website creation](#website)

## **<a name="team-members"></a>Members**

-   Julien Cordat-Auclair : student at Polytech Grenoble, RICM4
-   Christian Pomot : tutor manager
-   Sébastien Pittion : referent teacher

* * *

# **FIRST PART** : <a name="searchengine"></a>the search engine

<br/>

## **1.** <a name="searchengine_filesoverview"></a>Files overview

<p align="center">
  <img src="/ressources/architecture.png">
</p>

</br>

-   #### **<a name="getURLs"></a>getURLs.py** :

    _take a look at the code for a better understanding of the program_

    -   **<a name="getURLs_description"></a>description of the file** : this program allows to extract URLs corresponding to a keyword search on google. It is possible to extract a specific number of URLs, but since only the URLs from the first search page are retrieved, a limit of 100 results is set.

    -   **<a name="getURLs_process"></a>process** : first, thanks to Selenium, a google query is generated with the keywords and the number of URLs specified. Then, the program automatically searches and finds the URLs present on this search page corresponding to the links to websites and returns them. To be more precise, here is an example of source code of the google search page on which we can see the code corresponding to a simple link. The idea is to identify the `href` field present in tag `a`. This field is itself included in class `g`.

    ```HTML
    <div class="g">
      <!--m-->
      <div data-hveid="48" data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQFQgwKAEwAQ">
        <div class="rc">
          <h3 class="r">
            <a href="https://www.snorrigunnarsson.com/" onmousedown="return rwt(this,'','','','2','AOvVaw2TlGXAgaB6-WeTVsqH6rSg','','0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQFggxMAE','','',event)">
              SNORRI GUNNARSSON « Icelandic Photographer
            </a>
          </h3>
          <div class="s">
            <div>
              <div class="f hJND5c TbwUpd" style="white-space:nowrap">
                <cite class="iUh30">
                  https://www.snorrigunnarsson.com/
                </cite>
                <div class="action-menu ab_ctl">
                  <a class="GHDvEf ab_button" href="#" id="am-b1" aria-label="Result details" aria-expanded="false" aria-haspopup="true" role="button" jsaction="m.tdd;keydown:m.hbke;keypress:m.mskpe" data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQ7B0IMjAB">
                    <span class="mn-dwn-arw">
                    </span>
                  </a>
                  <div class="action-menu-panel ab_dropdown" role="menu" tabindex="-1" jsaction="keydown:m.hdke;mouseover:m.hdhne;mouseout:m.hdhue" data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQqR8IMzAB">
                    <ol>
                      <li class="action-menu-item ab_dropdownitem" role="menuitem">
                        <a class="fl" href="https://webcache.googleusercontent.com/search?q=cache:FTucX61DdpAJ:https://www.snorrigunnarsson.com/+&amp;cd=2&amp;hl=en&amp;ct=clnk&amp;gl=fr&amp;client=ubuntu" onmousedown="return rwt(this,'','','','2','AOvVaw08nuaEGR-hm3VxQUBel5JF','','0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQIAg0MAE','','',event)">
                          Cached
                        </a>
                      </li>
                    </ol>
                  </div>
                </div>
              </div>
              <span class="st">
                <em>
                  PORTFOLIO
                </em>
                 ... I am a professional
                 <em>
                   photographer
                 </em>
                  based out of Reykjavik
                  <em>Iceland
                  </em>
                  . I specialise in commercial work and editorial
                  <em>photography
                  </em>
                   – alongside&nbsp;...
                 </span>
               </div>
             </div>
             <div jsl="$t t--ddbPTeIsNI;$x 0;" class="r-iVMsmoxYLZ2s" data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQ2Z0BCDUwAQ">
               <div class="AUiS2 iVMsmoxYLZ2s-7_jVsFT_9Io" id="eobm_1" data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQx40DCDYwAQ">
                 <div id="eobd_1" class="iVMsmoxYLZ2s-uhagcrfPmuU" style="display:none">
                   <div data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQsKwBCDcoADAB">
                     iceland landscape photographer
                   </div>
                   <div data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQsKwBCDgoATAB">
                     freelance photographer iceland
                   </div>
                   <div data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQsKwBCDkoAjAB">
                     ragnar axelsson
                   </div>
                   <div data-ved="0ahUKEwjO5r_3orLbAhXKbxQKHd6xCmcQsKwBCDooAzAB">
                     wedding photographer iceland
                   </div>
                 </div>
                 <span class="XCKyNd" id="eobs_1" aria-label="Dismiss suggested follow ups" role="button" tabindex="0" jsaction="r.pz0qjfJrMDo" data-rtid="iVMsmoxYLZ2s" jsl="$x 2;">
                 </span>
                 <div>
                   <div class="d8lLoc iVMsmoxYLZ2s-eEjGhTK0s34" id="eobc_1">
                     <h4 class="eJ7tvc iVMsmoxYLZ2s-ZgH0LU9o8RU" id="eobp_1">
                       People also search for
                     </h4>
                     <div class="hYkSRb iVMsmoxYLZ2s-ICxnu-SGsqE" id="eobr_1">
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>
         <!--n-->
       </div>
    ```

    </br>

-   #### **<a name="extractHTML"></a>extractHTML.py** :

    _take a look at the code for a better understanding of the program_

    -   **<a name="extractHTML_description"></a>description of the file** : this script describes a program that can extract accurate data from any given URL. In this project, it is linked to [getURLs.py](#getURLs.py) which provides a list of specific URLs that appear when searching for any combination of keywords on Google (for more information, consult the dedicated section).

    -   **<a name="extractHTML_data"></a>extracted data** : given the purpose of this project, it is very important to extract data related to SEO. Here is the list of the different data extracted by the program. Note that a position is also associated to each URL because Scrapy works asynchronously and therefore does not deliver its results in the referencing order as desired.
        -   link : these are all the keywords appearing in the URL of the page from which the data is extracted
        -   title : makes sense on its own
        -   description : makes sense on its own
        -   strong : it defines important text in the page
        -   em : same idea as the "strong" tag
        -   img : these are the titles of the images on the page
        -   headings : makes sense on its own
        -   text : this is the text that appears in the page  
            </br>
    -   **<a name="extractHTML_cleaning"></a>data cleaning** : to make the data readable by the learning machine algorithm in the next step, you must make sure that no special characters are found there. This is the purpose of the `cleanHTML` function, which also allows to delete empty fields and eliminate certain spaces.

    -   **<a name="extractHTML_benchmarking"></a>benchmarking** : the execution time of this program is equal to the execution time of [getURLs.py](#getURLs.py) plus the data extraction time of each URL and the write time of the output file. However, the execution time of [getURLs.py](#getURLs.py) is equal to the time to generate the search, i.e. the bit rate of the network (constant), plus the extraction time of the URLs. So, the total execution time to be able to extract data from all URLs is equal to the bit rate of the network plus the execution time of the URLs, the extraction time of the data from the URLs and the writing time of the JSON Lines file. Here is a representation of the execution time according to the number of URLs requested. The number of keywords is set to 3 here and it has no impact on the execution time.

        ![benchmarking](/ressources/benchmarking.png)

        </br>

-   #### **<a name="scrape"></a>scrape.py** :

    _take a look at the code for a better understanding of the program_

    -   **<a name="scrape_description"></a>description of the file** : thanks to this script, it is possible to execute the whole program previously presented. In this one, you can change the keywords on which the data extraction will be performed. The number of URLs can't be chosen here because it is actually set in [extractHTML.py](#extractHTML.py). Indeed, this number will later be set to the maximum (100) so that the machine learning program can obtain a maximum of data and thus be more efficient.

    -   **<a name="scrape_run"></a>program run** : to run this program, simply type the following command in the Linux command prompt. An output file "output.jsonl" will then be created and it will be in the folder named "output".
        `python scrape.py`

</br>

-   #### **<a name="searchengine_otherfiles"></a>other files** :

    -   **<a name="otherfiles_items"></a>items.py** : this file contains a class that defines the different items (i.e. the different types of data) that our program will extract. These are in fact the same attributes presented in the [extracted data](#extractHTML_data) section.

    -   **<a name="otherfiles_settings"></a>settings.py** : few parameters are defined in this file but the main one is the path management of the output file.

    -   **<a name="otherfiles_config"></a>scrapy.cfg** : this is a file generated automatically when creating a Scrapy project. It is used to define the name of the project.

</br>

## **2.** <a name="sampletest"></a>Sample test

<p align="center">
  <img src="/ressources/scrapeExample.png">
</p>

As we can see, the items (position, url, title ...) are not ordered as they were specified in the code. It's actually because Scrapy doesn't take it into account when generating the output file. In addition, the HTML cleaning function has eliminated all punctuation characters as well as unnecessary spaces and empty fields in order to obtain a JSON file that contains only words and numbers.

</br>

## **3.** <a name="diagram"></a>Diagram

<p align="center">
  <img src="/ressources/diagram.png">
</p>

</br>
</br>

# **SECOND PART** : <a name="machinelearning"></a>integration with the learning machine

The second step consists in linking the scrapping engine with the learning machine engine. The learning machine algorithms have already been coded and it is therefore necessary to be able to link these algorithms with the previously extracted results. During this step, I must modify existing code written under the Symfony framework - this code allowed to perform both the scrapping task and generating the output given the results returned by the learning machine, but it was much slower than with Scrapy and Selenium.

</br>
</br>

# **THIRD PART** : <a name="website"></a>website creation

**TODO**

</br>
</br>
