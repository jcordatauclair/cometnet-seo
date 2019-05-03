import scrapy


class QuotesSpider(scrapy.Spider):
    name = "quotesHTML" # identifies the Spider (must be unique within a project)

    def start_requests(self): # returns an iterable of requests which the Spider will crawl from
			      # subsequents requests will be generated from these initial ones
        urls = [
            'http://quotes.toscrape.com/page/1/',
            'http://quotes.toscrape.com/page/2/',
        ]
        for url in urls:
            yield scrapy.Request(url=url, callback=self.parse)

    def parse(self, response): # handle the response downloaded for each of the requests made
        page = response.url.split("/")[-2]
        filename = 'quotes-%s.html' % page
        with open(filename, 'wb') as f:
            f.write(response.body)
        self.log('Saved file %s' % filename)
