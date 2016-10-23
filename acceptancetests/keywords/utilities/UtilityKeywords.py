__version__ = '0.1'
import requests
import json

class UtilityKeywords(object):

    def __init__(self):

    def verify_value_in_dictionary(self, ref_dictioary , serach_key, search_value):
        if serach_key in ref_dictioary.keys():
                if ( ref_dictioary[serach_key] != search_value ):
                        raise Exception('Expected: ' + serach_key + ' Found: ' + ref_dictioary[serach_key])
        else:
                raise Exception('Key: ' + serach_key +' Not Found')



