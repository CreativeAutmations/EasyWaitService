__version__ = '0.1'
import requests
import json

class ReceiptManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def create_receipt(self, bill_details, server_message, access_token):
        url = self._hostURL +'/api/receipts'
        print url
        bill_detaials_array = bill_details.split(',')
        json_payload_list = {}
        for param_val in bill_detaials_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_").strip()
                        value = param_val_array[1]
                        json_payload_list[key] = value.strip()


        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        print 'expected message: ' + server_message

        if server_message != server_response["message"] :
                raise Exception('Expected: ' + server_message + ' Recieved: ' + server_response["message"])

