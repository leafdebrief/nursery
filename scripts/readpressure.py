#!/usr/bin/env python
import sys
import time
import board
import busio
import json
import adafruit_lps2x
from datetime import datetime

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
lps22 = adafruit_lps2x.LPS22(i2c)

now = datetime.now()

# a Python object (dict):
output = {
  "timestamp": now,
  "value": lps22.pressure
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)