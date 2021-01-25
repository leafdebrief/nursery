#!/usr/bin/env python
import sys
import time
import board
import busio
import json
from datetime import datetime
from adafruit_seesaw.seesaw import Seesaw

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
atsamd10 = Seesaw(i2c, addr=0x36)

now = datetime.now()

# a Python object (dict):
output = {
  "timestamp": now,
  "value": atsamd10.moisture_read()
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)