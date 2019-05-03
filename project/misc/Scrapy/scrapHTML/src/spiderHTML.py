import scrapy


class spiderHTML(scrapy.Spider):

    name = "spiderHTML"
    start_urls = ['http://www.multimaths.net/',
                  'http://www.studiofly.fr/',
                  'http://www.lastrada-restaurant.fr/']

    def parse(self, response):

        yield {
            'url' : response.request.url, 
            'title' : response.xpath('//title/text()').extract(),
            'description' : response.xpath('//meta[@name="description"]/@content').extract(),
            'heading1' : response.xpath('//h1/text()').extract(),
            'heading2' : response.xpath('//h2/text()').extract(),
            'heading3' : response.xpath('//h3/text()').extract(),
            'heading4' : response.xpath('//h4/text()').extract(),
            'heading5' : response.xpath('//h5/text()').extract(),
            'heading6' : response.xpath('//h6/text()').extract(),
            'keywords' : response.xpath('//meta[@name="keywords"]/@content').extract(),
        }
