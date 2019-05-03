#!/usr/bin python
# -*- coding: utf-8 -*-
import hunspell
import unidecode


motsCles = ['référencement', 'webmarketing', 'digital', 'grenoble', 'verlaine']
phraseTest = 'webmarketing grenoble unmotquinestpasvalide site internet'

frDictionnary = hunspell.HunSpell('/usr/share/hunspell/fr_FR_sansaccents.dic',
                                  '/usr/share/hunspell/fr_FR_sansaccents.aff')
enDictionnary = hunspell.HunSpell('/usr/share/hunspell/en_US.dic',
                                  '/usr/share/hunspell/en_US.aff')

test = enDictionnary.spell('webmarketing')

print(test)
