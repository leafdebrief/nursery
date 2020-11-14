import sys
import time
import board
import busio
import adafruit_si7021
import adafruit_as7341
import mysql.connector as mariadb
from datetime import datetime

# Connect to MariaDB Platform
try:
    conn = mariadb.connect(
        user="root",
        password="kl99l9jk",
        host="localhost",
        port=3306,
        database="nursery"
    )
except mariadb.Error as e:
    print(f"Error connecting to MariaDB Platform: {e}")
    sys.exit(1)

# Get Cursor
cursor = conn.cursor()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
si7021 = adafruit_si7021.SI7021(i2c)
as7341 = adafruit_as7341.AS7341(i2c)

# Main loop
while True:

    # Print logging info
    print("\nTemperature: %0.1f C" % si7021.temperature)
    print("Humidity: %0.1f %%" % si7021.relative_humidity)

    print("\nF1 - 415nm/Violet  %s" % as7341.channel_415nm)
    print("F2 - 445nm//Indigo %s" % as7341.channel_445nm)
    print("F3 - 480nm//Blue   %s" % as7341.channel_480nm)
    print("F4 - 515nm//Cyan   %s" % as7341.channel_515nm)
    print("F5 - 555nm/Green   %s" % as7341.channel_555nm)
    print("F6 - 590nm/Yellow  %s" % as7341.channel_590nm)
    print("F7 - 630nm/Orange  %s" % as7341.channel_630nm)
    print("F8 - 680nm/Red     %s" % as7341.channel_680nm)

    # MariaDB inserts
    # try:
    #     now = datetime.now()
    #     cursor.execute("INSERT INTO temperature (timestamp, value) VALUES (%s, %s)", (now, si7021.temperature))
    #     cursor.execute("INSERT INTO humidity (timestamp, value) VALUES (%s, %s)", (now, si7021.relative_humidity))
    #     conn.commit()

    # except mariadb.Error as e:
    #     print(f"Error: {e}")

    time.sleep(10)