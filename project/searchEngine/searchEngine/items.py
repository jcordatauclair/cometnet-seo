from scrapy.item import Item, Field


class searchEngineItem(Item):
    url = Field()
    position = Field()
    relevance = Field()
    itemsURL = Field()
    title = Field()
    strong = Field()
    img = Field()
    h1 = Field()
    h2 = Field()
    h3 = Field()
    h4 = Field()
    h5 = Field()
    h6 = Field()
    text = Field()
