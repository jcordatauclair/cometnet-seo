#!/usr/bin python
# -*- coding: utf-8 -*-
from scrapy.item import Item, Field

# Classe permettant de définir les balises à récupérer
class searchEngineItem(Item):
    title = Field()
    iurl = Field()
    h1 = Field()
    h2 = Field()
    h3 = Field()
    strong = Field()
    alt = Field()
    a = Field()
    text = Field()
    url = Field()
    position = Field()
    relevance = Field()
