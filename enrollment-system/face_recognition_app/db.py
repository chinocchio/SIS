import mysql.connector

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",      # change if remote
        user="sis_user",           # your MySQL username
        password="P@$$word_Ch1no",           # your MySQL password
        database="enrollment_db"   # your Laravel DB name
    )
