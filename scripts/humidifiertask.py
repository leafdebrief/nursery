#!/usr/bin/env python
import time
import board
import busio
import asyncio
import adafruit_si7021
from kasa import SmartStrip

# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
si7021 = adafruit_si7021.SI7021(i2c)
strip = SmartStrip("10.0.1.2")
asyncio.run(strip.update())

while True:
    humidity = si7021.relative_humidity

    print(f"Humidity: {humidity}% RH")

    if humidity < 40:
        for plug in strip.children:
            if plug.alias == "Nursery humidifier" and not plug.is_on:
                print("Turning humidifier ON")
                asyncio.run(plug.turn_on())
                asyncio.run(strip.update())

    if humidity > 50:
        for plug in strip.children:
            if plug.alias == "Nursery humidifier" and plug.is_on:
                print("Turning humidifier OFF")
                asyncio.run(plug.turn_off())
                asyncio.run(strip.update())

    time.sleep(10)
