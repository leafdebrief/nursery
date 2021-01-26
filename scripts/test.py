#!/usr/bin/env python
import sys
import json
from datetime import datetime

def convertdate(o):
  if isinstance(o, datetime):
    return o.__str__()

now = datetime.now()

output = {
  "timestamp": now
}

# the result is a JSON string:
print(json.dumps(output, default = convertdate))

sys.exit(0)