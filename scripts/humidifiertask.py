#!/usr/bin/env python
import time
import board
import busio
import asyncio
import adafruit_si7021
from kasa import SmartStrip, Discover

# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
si7021 = adafruit_si7021.SI7021(i2c)

rh_min = 45
rh_max = 50

devices = asyncio.run(Discover.discover())

for addr, dev in devices.items():
    asyncio.run(dev.update())

    if dev.alias == "Nursery":
        print("Found humidifier")

        while True:
            humidity = si7021.relative_humidity

            print(f"Humidity: {humidity}% RH")

            if humidity < rh_min:
                for plug in dev.children:
                    if plug.alias == "Nursery humidifier" and not plug.is_on:
                        print("Turning humidifier ON")
                        asyncio.run(plug.turn_on())
                        asyncio.run(dev.update())

            if humidity > rh_max:
                for plug in dev.children:
                    if plug.alias == "Nursery humidifier" and plug.is_on:
                        print("Turning humidifier OFF")
                        asyncio.run(plug.turn_off())
                        asyncio.run(dev.update())

            time.sleep(10)
