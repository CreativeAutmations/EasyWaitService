import requests
import os

hostIP = os.environ['currentIP']
serverPort = 8000
hostURL = 'http://' + hostIP + ':' + `serverPort`

testUser = 'Gauri1'
testUserMail = testUser + '@gmail.com'

payload = {'name': testUser , 'email':testUserMail, 'password':'Test@123'}
url = hostURL +'/api/signup'
r = requests.post(url, json=payload)
signupresponse = r.json()

payload = {'email':testUserMail, 'password':'Test@123'}
url = hostURL + '/api/signin'
r = requests.post(url, json=payload)
signinresponse = r.json()

headers = {'Authorization': 'Bearer '+ signinresponse['token']}
url = hostURL + '/api/restricted'
r = requests.get(url, headers=headers)

print signinresponse['token']

