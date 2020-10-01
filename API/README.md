# School Health Surveillance API
Set of API endpoints to be consumed by the School Health Surveillance [Dashboard]()

[![Build Status]()]() [![Maintainability]()]()
<!-- [![Coverage Status]()]()  -->


## Features
The API offers the following set of endpoints:


  | REQUEST      | ROUTE             | FUNCTIONALITY                                       |
  |--------------|-------------------|-----------------------------------------------------|
  |  POST        | /signup           | Register a user                                     |
  |  POST        | /login            | Login a user                                        |
  |  GET         | /cases            | Fetch all cases stored in the database              |
  |  GET         | [school]/cases    | Fetch all cases registered under a particular school|
 
## Getting started
These instructions will get you a copy of the program on your local machine for development and testing purposes.

## Prerequisites
Packages you will need to run the application

```
Python3
    version: 3.6
```
```
Pip for python3
    $ sudo apt-get install python3-pip
```
```
Flask to build the application
    version: 1.0.2
    $ pip install flask
```
```
Virtualenv to create a virtual environment
    version: 16.0.0
```
```
Pytest to perform tests
    version: 3.9.1
    $ pip install pytest -U
```
Alternatively, run `pip install -r requirements.txt` to install all the necessary tools

## Built With
1. [Flask](http://flask.pocoo.org/) -  microframework for Python
2. [Flask-JWT-Extended](https://flask-jwt-extended.readthedocs.io/en/latest/)
3. [PostgreSQL](https://www.postgresql.org/) 

## Installing
To have a copy of the project on your machine, run the command below in your preferred directory:

``` 
git clone https://github.com/CryptoSavannah/SHS_with_endpoints.git
```
After cloning, you will have a folder named `SHS_with_endpoints`

## How to use
1. Navigate to `API`
2. Create a virtual environment by running:
``` python3 -m venv <name of virtualenvironment> ```
3. Activate the virtual environment
``` source <name of virtualenvironment>/bin/activate```
You should see the name of the virtual environment placed right before your current path/directory in brackets()
4. Connect to the postgres db
Once Postgres is installed and you can connect, you’ll need to export the DATABASE_URL environment variable for to connect to it when running locally
Run the command below to connect
```
DATABASE_URL=$(heroku config:get DATABASE_URL -a )
```
5. Run the application
```export FLASK_APP=app.py``` then
```flask run```


## Testing
1. Test with [Postman](https://www.getpostman.com/) by pasting the url []() (For the POST requests, enter the data as raw application/json)

## Sample Requests and Responses for a user

### Sign up
Endpoint `/api/v1/signup`

Input
```
    {
        "employee_no"   : "MOH/123",
        "user_name"     : "Dee",
        "user_email"    : "kxania@gmail.com",
        "user_password" : "warmups"
    }
```
Output 
```
{"message":"User Dee successfully created"}
```

### Login
Endpoint `/api/v1/login`

Input
```
    {
        "user_name"     : "Dee",
        "user_password" : "warmups"
    }
```

Output
```
    {
        "message":"You have sucessfully been logged in as Dee"
    }
```

## Deployment
Deployed on []()