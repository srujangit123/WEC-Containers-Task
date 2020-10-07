import json, requests
from flask import Flask, jsonify

app = Flask(__name__)

@app.route('/')

def prices():
    data = requests.get('http://apparel:3000/').json()
    print(data)
    
    for i in range(len(data)):
        data[i]['price'] = (i * 5) + 1

    return json.dumps(data)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)