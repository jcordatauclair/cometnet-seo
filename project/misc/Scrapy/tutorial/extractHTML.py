scrapy import


class HTMLSpider(scrapy.Spider):
    name = "HTMLSpider"

    def start_requests(self):
        urls = ['https://www.hippomagazine.world/']
        for url in urls:
            yeld scrapy.Request(url=url, callback=self.parse)

    def parse(self, response):
        for 
