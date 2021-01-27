#!/usr/bin/env python
import sys
import time
import board
import busio
import json
import adafruit_si7021
import adafruit_shtc3
from datetime import datetime

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)

si7021_success = True

try:
  si7021 = adafruit_si7021.SI7021(i2c)
except:
  si7021_success = False
  shtc3 = adafruit_shtc3.SHTC3(i2c)

now = datetime.now()

# a Python object (dict):
output = {
  "timestamp": now,
  "value": si7021.relative_humidity if si7021_success else shtc3.relative_humidity
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)