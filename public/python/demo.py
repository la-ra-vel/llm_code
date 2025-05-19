# public/test.py
import json

data = {
    "message": "Hello from Python!"
}

with open('output.json', 'w') as f:
    json.dump(data, f)
