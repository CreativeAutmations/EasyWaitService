__version__ = '0.1'
import requests

class AuthenticationManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def verify_host_and_port(self):
        print "Testing with HOST: {} and PORT: {} ".format(self._host, self._port)

    def register_user(self, username, email, password):
        url = self._hostURL +'/api/signup'
        payload = {'name': username , 'email':email, 'password':password}
        r = requests.post(url, json=payload)
        signupresponse = r.json()
        if 'token' in signupresponse.keys():
                print signupresponse["token"]
        else:
                print signupresponse["errorInfo"]


