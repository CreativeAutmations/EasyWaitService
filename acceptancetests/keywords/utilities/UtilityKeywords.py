__version__ = '0.1'
import requests
import json

class UtilityKeywords(object):

    def __init__(self):
        print "Initialized Keywords"

    def verify_value_in_dictionary(self, ref_dictioary , search_key, search_value):
        if search_key in ref_dictioary.keys():
                if ( ref_dictioary[search_key] != search_value ):
                        raise Exception('Expected: ' + search_value + ' Found: ' + ref_dictioary[search_key])
        else:
                raise Exception('Key: ' + search_key +' Not Found')



