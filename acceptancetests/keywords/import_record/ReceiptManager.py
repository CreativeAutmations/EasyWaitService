__version__ = '0.1'
import requests
import json

class ReceiptManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def create_receipt(self, bill_details, server_message):
        url = self._hostURL +'/api/receipts'
        print url
        bill_detaials_array = bill_details.split(',')
        json_payload_list = {}
        for param_val in bill_detaials_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_")
                        value = param_val_array[1]
                        json_payload_list[key] = value

        json_payload = json.dumps(json_payload_list)

        print 'payload: ' + json_payload

        r = requests.post(url, json=json_payload)
        server_response = r.json()
        print server_response
        print 'expected message: ' + server_message

        if server_message != server_response["message"] :
                raise Exception('Expected: ' + server_message + ' Recieved: ' + server_response["message"])

