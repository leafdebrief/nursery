import sys
import time
import board
import busio
import json
import adafruit_si7021
import adafruit_as7341
from datetime import datetime

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()
 
# Create library object using our Bus I2C port
i2c = busio.I2C(board.SCL, board.SDA)
si7021 = adafruit_si7021.SI7021(i2c)
as7341 = adafruit_as7341.AS7341(i2c)

now = datetime.now()

# a Python object (dict):
output = {
  "timestamp": now,
  "temperature": si7021.temperature,
  "humidity": si7021.relative_humidity,
  "spectral": [
    as7341.channel_415nm,
    as7341.channel_445nm,
    as7341.channel_480nm,
    as7341.channel_515nm,
    as7341.channel_555nm,
    as7341.channel_590nm,
    as7341.channel_630nm,
    as7341.channel_680nm
  ]
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)