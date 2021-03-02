#!/usr/bin/env python
import sys
import time
import board
import busio
import json
import adafruit_ltr390
from datetime import datetime

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)

ltr390 = adafruit_ltr390.LTR390(i2c)

now = datetime.now()

ultraviolet = ltr390.uvs

# a Python object (dict):
output = {
  "timestamp": now,
  "value": ultraviolet
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)
