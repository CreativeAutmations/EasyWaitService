__version__ = '0.1'
import requests
import json
import ast

class UtilityKeywords(object):

    def __init__(self):
        print "Initialized Keywords"

    def verify_value_in_dictionary(self, ref_dictioary , search_key, search_value):
        if search_key in ref_dictioary.keys():
                if ( ref_dictioary[search_key] != search_value ):
                        raise Exception('Expected: ' + search_value + ' Found: ' + ref_dictioary[search_key])
        else:
                raise Exception('Key: ' + search_key +' Not Found')



    def extract_value_from_dictionary(self, ref_dictioary, search_key):
        ref_dictionary_list = {}

        ref_dictioary_array = ref_dictioary.split(',')
        for param_val in ref_dictioary_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_").strip()
                        value = param_val_array[1]
                        ref_dictionary_list[key] = value.strip()

        if search_key in ref_dictionary_list.keys():
                return ref_dictionary_list[search_key] 
        else:
                raise Exception('Key: ' + search_key +' Not Found')

    def verify_subset_in_dictionary(self, ref_dictioary, search_dictionary):
        search_dictionary_array = search_dictionary.split(',')
        for param_val in search_dictionary_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_").strip()
                        value = param_val_array[1].strip()
                        if key in ref_dictioary.keys():
                                if value != ref_dictioary[key]: 
                                    raise Exception('Key: ' + key +' => Expected: ' + value + ' ,Found: ' + ref_dictioary[key]) 
                        else:
                                raise Exception('Key: ' + key +' Not Found')

    def get_record_count_from_dictionary(self, string_recordset):
        n = json.dumps(string_recordset)  
        o = json.loads(n)
        dictionary_recordset = json.loads(n)
        return len(dictionary_recordset)

