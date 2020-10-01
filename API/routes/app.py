import os

import psycopg2
from flask import Flask, jsonify, request
from flask_cors import CORS

app = Flask(__name__)
CORS(app, supports_credentials=True, resources={r"/api/*": {"origins": "*"}})


class DatabaseConnection:
    def __init__(self):
        db = 'shs'
        conn = psycopg2.connect(
            host="localhost", database=db, user="postgres", password="psql", port=5433)
        conn.autocommit = True
        self.cursor = conn.cursor()
        print(self.cursor)
        print(db)

    def insert_user(self, employee_no, user_name, user_email, user_password):
        insert_user = "INSERT INTO users (employee_no, user_name, user_email, user_password) values ('{}', '{}', '{}', '{}')".format(
            employee_no, user_name, user_email, user_password)
        self.cursor.execute(insert_user)

    def login_user(self, user_name, user_password):
        select_user = "SELECT user_name, user_password FROM users WHERE user_name = '{}' and user_password = '{}'".format(
            user_name, user_password)
        self.cursor.execute(select_user)
        return [user_name, user_password]

    def get_user(self, user_name):
        get_user = "SELECT * FROM users WHERE user_name = '{}'".format(
            user_name)
        self.cursor.execute(get_user)
        result = self.cursor.fetchone()
        return result

    def get_users(self):
        get_users = "SELECT * FROM users"
        self.cursor.execute(get_users)
        result = self.cursor.fetchall()
        return result

    def get_cases(self):
        get_cases = "SELECT * FROM cases ORDER BY case_no ASC"
        self.cursor.execute(get_cases)
        result = self.cursor.fetchall()
        return result

    def get_total_no_of_cases_per_school(self, school):
        get_total = "SELECT * FROM cases WHERE school = '{}'".format(school)
        self.cursor.execute(get_total)
        result = self.cursor.fetchall()
        return result

    def get_total_no_of_schools(self):
        get_total = "SELECT COUNT(DISTINCT school) FROM cases"
        self.cursor.execute(get_total)
        result = self.cursor.fetchone()
        return result


db = DatabaseConnection()


class User():
    """Users class defining the user model"""

    def __init__(self, employee_no, user_name, user_email, user_password):
        self.employee_no = employee_no
        self.user_name = user_name
        self.user_email = user_email
        self.user_password = user_password


class Case():
    """Case class defining the case model"""

    def __init__(self, disease, patient_id, school, parish, sub_county, district, region):
        self.disease = disease
        self.patient_id = patient_id
        self.school = school
        self.parish = parish
        self.sub_county = sub_county
        self.district = district
        self.region = region
# def get_cases(self):
#     get_cases = "SELECT * FROM cases ORDER BY case_no ASC"
#     self.cursor.execute(get_cases)
#     result = self.cursor.fetchall()
#     return result


class User_Controller:

    @staticmethod
    def login():
        """Login a user"""
        user_input = request.get_json(force=True)
        username = user_input.get("user_name")
        password = user_input.get("user_password")
        user = db.get_user(username)
        if user:
            return jsonify({
                'message': f"You have successfully been logged in as {username}"
            }), 200
        return jsonify({'message': f"{username} does not exist"}), 400

    @staticmethod
    def sign_up():
        """Register a user"""
        user_input = request.get_json(force=True)
        employee_no = user_input.get("employee_no")
        user_name = user_input.get("user_name")
        user_email = user_input.get("user_email")
        user_password = user_input.get("user_password")
        users = db.get_users()
        print(users)
        for user in users:
            if user_name == user[1]:
                return jsonify({'message': f"User {user_name} already exists"}), 400
            if user_email == user[2]:
                return jsonify({'message': "Email belongs to another account"}), 400
        new_user = User(employee_no, user_name, user_email, user_password)
        db.insert_user(new_user.employee_no, new_user.user_name, new_user.user_email,
                       new_user.user_password)
        return jsonify({"message": f"User {user_name} successfully created"}), 201


class Case_Controller:
    @staticmethod
    def get_all_cases():
        cases_list = []
        school_list = []
        cases = db.get_cases()
        for case in cases:
            case_dict = {
                "case_no": case[0],
                "disease": case[1],
                "patient_id": case[2],
                "school": case[3],
                "parish": case[4],
                "sub_county": case[5],
                "district": case[6]
            }
            cases_list.append(case_dict)
            if case[3] not in school_list:
                school_list.append(case[3])
        return jsonify({'cases': cases_list, 'length': len(cases_list), 'schools': school_list, 'no_of_schs': len(school_list)}), 200

    @staticmethod
    def get_cases_per_sch(school_name):
        the_list = []
        cases = db.get_total_no_of_cases_per_school(school_name)
        for case in cases:
            case_dict = {
                "case_no": case[0],
                "disease": case[1],
                "patient_id": case[2],
                "parish": case[4],
                "sub_county": case[5],
                "district": case[6]
            }
            the_list.append(case_dict)
        return jsonify({'cases_per_sch': the_list, 'length': len(the_list)}), 200


@app.route('/api/v1/login', methods=['POST'])
def login():
    return User_Controller.login()


@app.route('/api/v1/signup', methods=['POST'])
def user_signup():
    return User_Controller.sign_up()


@app.route('/api/v1/cases', methods=['GET'])
def get_all_cases():
    return Case_Controller.get_all_cases()


@app.route('/api/v1/<school>/cases', methods=['GET'])
def get_cases_per_school(school):
    return Case_Controller.get_cases_per_sch(school)


if __name__ == '__main__':
    app.run(debug=True)
