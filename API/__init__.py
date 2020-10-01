from API.routes import app
from flask import Flask
from flask_cors import CORS
from flask_cors import CORS

app = Flask(__name__)
CORS(app, supports_credentials=True, resources={r"/api/*": {"origins": "*"}})
