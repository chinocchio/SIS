import mysql.connector

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",      # change if remote
        user="root",           # your MySQL username
        password="",           # your MySQL password
        database="enrollment_db"   # your Laravel DB name
    )
