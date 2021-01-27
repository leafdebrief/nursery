#!/usr/bin/env python
import sys
import time
import board
import busio
import json
import adafruit_si7021
import adafruit_as7341
import adafruit_lps2x
import adafruit_shtc3
from datetime import datetime
from adafruit_seesaw.seesaw import Seesaw

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()

si7021_success = True
as7341_success = True
atsamd10_success = True
lps22_success = True
shtc3_success = True
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)

try:
  si7021 = adafruit_si7021.SI7021(i2c)
except:
  si7021_success = False

try:
  shtc3 = adafruit_shtc3.SHTC3(i2c)
except:
  shtc3_success = False

try:
  as7341 = adafruit_as7341.AS7341(i2c)
except:
  as7341_success = False

try:
  lps22 = adafruit_lps2x.LPS22(i2c)
except:
  lps22_success = False

try:
  atsamd10 = Seesaw(i2c, addr=0x36)
except:
  atsamd10_success = False

temperature = si7021.temperature if si7021_success else shtc3.temperature if shtc3_success else None
humidity = si7021.relative_humidity if si7021_success else shtc3.relative_humidity if shtc3_success else None
spectral = [
    as7341.channel_415nm,
    as7341.channel_445nm,
    as7341.channel_480nm,
    as7341.channel_515nm,
    as7341.channel_555nm,
    as7341.channel_590nm,
    as7341.channel_630nm,
    as7341.channel_680nm
] if as7341_success else None
moisture = atsamd10.moisture_read() if atsamd10_success else None
pressure = lps22.pressure if lps22_success else None

now = datetime.now()

# a Python object (dict):
output = {
  "timestamp": now,
  "temperature": temperature,
  "humidity": humidity,
  "spectral": spectral,
  "moisture": moisture,
  "pressure": pressure
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)