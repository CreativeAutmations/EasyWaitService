__version__ = '0.1'
import requests

class ReceiptManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def create_receipt(self, bill_details, server_message):
        url = self._hostURL +'/api/receipts'
		bill_detaials_array = bill_details.splitlines()
		json_payload_list = []
		for param_val in bill_detaials_array:
			param_val_array = param_val.split(':')
			if len(param_val_array) == 2:
				json_payload_list.append("'" + param_val_array[0] + "'" + ":" + "'" + param_val_array[1] + "'" )
				
		json_payload = ","
		json_payload.join(json_payload_list)
		json_payload = json_payload.join(json_payload_list)
		json_payload = '{' + json_payload + '}'
        r = requests.post(url, json=json_payload)
        server_response = r.json()
        if 'message' in server_response.keys():
			print server_response["message"]
			if server_message != server_response["message"] :
				raise Exception('Expected: ' + server_message + ' Recieved: ' server_response["message"])
        else:
			raise Exception('Connection with server failed!')

