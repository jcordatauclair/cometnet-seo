from getURLs import googleSearch
from scrapy.http import Request
import scrapy


class extractHTML(scrapy.Spider):

    #--------------------------------------------------------------------------#
    # set the number of URLs
    nURLs = 10

    # set the keywords
    args = ["photographe", "islande", "drone"]
    #--------------------------------------------------------------------------#

    # name of the spider and URLs to crawl (hrefs)
    name = "extractHTML"
    start_urls = googleSearch(nURLs, *args)

    # get the position of each URL according to the order on the search page
    def start_requests(self):
        index = 1
        for url in self.start_urls:
            yield Request(url, meta={'index': index})
            index = index + 1

    def parse(self, response):

        print("\n")
        print("Processing... " + response.url)
        print("\n")

        # generator
        # set the output's attributes
        yield {
        'position' : response.meta['index'],
        'url' : response.request.url,
        'link' : response.url.split('/')[-2],
        'title' : response.xpath('//title/text()').extract(),
        'description' : response.xpath('//meta[@name="description"]/@content').extract(),
        'strong' : response.xpath('//strong/text()').extract(),
        'heading1' : response.xpath('//h1/text()').extract(),
        'heading2' : response.xpath('//h2/text()').extract(),
        'heading3' : response.xpath('//h3/text()').extract(),
        'heading4' : response.xpath('//h4/text()').extract(),
        'heading5' : response.xpath('//h5/text()').extract(),
        'heading6' : response.xpath('//h6/text()').extract(),
        'keywords' : response.xpath('//meta[@name="keywords"]/@content').extract(),
        # 'text' : '\n'.join(response.xpath('//text()').extract()),
        }
