import json
import unittest
from API.routes import app
from API.routes.app import *


test_user = {
    "employee_no": "MOH012933",
    "user_name": "dave",
    "user_email": "dave@gmail.com",
    "user_password": "algorithms"
}

db = DatabaseConnection()


class Base(unittest.TestCase):
    """Base class for tests. 
    This class defines a common `setUp` method that defines attributes which 
    are used in the various tests.
    """

    def setUp(self):
        self.app_client = app.test_client()
        db.setUp()

    # def tearDown(self):
    #     db.delete_tables()

    def sign_up(self):
        create_user = self.app_client.post("/api/v1/signup", content_type='application/json',
                                           data=json.dumps(test_user))
        self.assertEqual(create_user.status_code, 201)
        response = json.loads(create_user.data)
        self.assertEqual(response['message'], 'User dave successfully created')

    def user_login(self):
        # self.sign_up()
        login_user = self.app_client.post('/api/v1/login', content_type='application/json',
                                          data=json.dumps({"user_name": "dave", "user_password": "algorithms"}))
        self.assertEqual(login_user.status_code, 200)
        response = json.loads(login_user.data)
        self.assertEqual(response['message'],
                         'You have successfully been logged in as dave')


class Endpoints(Base):
    """
    Tests aspects of the get cases endpoint
    Tests include: retrieving cases.  
    """

    def test_get_all_cases(self):
        self.user_login()
        get_request = self.app_client.get("/api/v1/cases")
        self.assertEqual(get_request.status_code, 200)


if __name__ == ('__main__'):
    unittest.main()
