#!/usr/bin/env python
import sys
import time
import board
import busio
import adafruit_si7021
import adafruit_as7341
import adafruit_lps2x
import mysql.connector as mariadb
from datetime import datetime
from adafruit_seesaw.seesaw import Seesaw

try:

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

    try:
        cursor.execute("SELECT library, label, seesaw, address FROM sensors")
    except mariadb.Error as e:
        print(f"Error getting sensor list: {e}")
        sys.exit(1)



    for (library, label, seesaw, address) in cursor:
        print(f"First Name: {first_name}, Last Name: {last_name}")
    
    # Create library object using our Bus I2C port
    i2c = busio.I2C(board.SCL, board.SDA)

    try:
        si7021 = adafruit_si7021.SI7021(i2c)
    except:
        print("SI7021 unavailable")
        si7021 = False

    try:
        as7341 = adafruit_as7341.AS7341(i2c)
    except:
        print("AS7341 unavailable")
        as7341 = False

    try:
        lps22 = adafruit_lps2x.LPS22(i2c)
    except:
        print("LPS22 unavailable")
        lps22 = False

    try:
        atsamd10 = Seesaw(i2c, addr=0x36)
    except:
        print("ATSAMD10 unavailable")
        atsamd10 = False

    # Main loop
    while True:

        # Print logging info
        if si7021:
            print("\nTemperature: %0.1f°C" % si7021.temperature)
            print("Humidity: %0.1f%%" % si7021.relative_humidity)

        if as7341:
            print("\nSpectral:")
            print("F1 - 415nm (Violet)  %s" % as7341.channel_415nm)
            print("F2 - 445nm (Indigo) %s" % as7341.channel_445nm)
            print("F3 - 480nm (Blue)   %s" % as7341.channel_480nm)
            print("F4 - 515nm (Cyan)   %s" % as7341.channel_515nm)
            print("F5 - 555nm (Green)   %s" % as7341.channel_555nm)
            print("F6 - 590nm (Yellow)  %s" % as7341.channel_590nm)
            print("F7 - 630nm (Orange)  %s" % as7341.channel_630nm)
            print("F8 - 680nm (Red)     %s" % as7341.channel_680nm)

        if atsamd10:
            print("\nMoisture: %s" % atsamd10.moisture_read())

        if lps22:
            print("\Pressure: %s hPa" % lps22.pressure)

        # MariaDB inserts
        try:
            now = datetime.now()
            cursor.execute("INSERT INTO temperature (timestamp, value) VALUES (%s, %s)", (now, si7021.temperature))
            cursor.execute("INSERT INTO humidity (timestamp, value) VALUES (%s, %s)", (now, si7021.relative_humidity))
            cursor.execute("INSERT INTO spectral (timestamp, f1, f2, f3, f4, f5, f6, f7, f8) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)", (
              now,
              as7341.channel_415nm,
              as7341.channel_445nm,
              as7341.channel_480nm,
              as7341.channel_515nm,
              as7341.channel_555nm,
              as7341.channel_590nm,
              as7341.channel_630nm,
              as7341.channel_680nm
            ))
            cursor.execute("INSERT INTO moisture (timestamp, value) VALUES (%s, %s)", (now, atsamd10.moisture_read()))
            cursor.execute("INSERT INTO pressure (timestamp, value) VALUES (%s, %s)", (now, lps22.pressure))
            conn.commit()

        except mariadb.Error as e:
            print(f"Error: {e}")

        time.sleep(600)

finally:
    conn.close()