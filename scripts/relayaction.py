#!/usr/bin/python3
import sys, getopt
import time
import RPi.GPIO as GPIO

PIN_1 = 31
PIN_2 = 33
PIN_3 = 35
PIN_4 = 37

MODE_ON = 'on'
MODE_OFF = 'off'

ALL_PINS = [False, PIN_1, PIN_2, PIN_3, PIN_4]

def main(argv):
  relay = 0
  wait = 0
  mode = MODE_ON

  try:
    opts, args = getopt.getopt(argv,"hr:w:m:",["relay=","wait=","mode="])
  except getopt.GetoptError:
    print ('relayaction.py -r <relay> -w <wait?> -m <mode?:"'+MODE_ON+'"|"'+MODE_OFF+'">')
    sys.exit(2)
  for opt, arg in opts:
    if opt == '-h':
      print ('relayaction.py -r <relay> -w <wait?> -m <mode?:"'+MODE_ON+'"|"'+MODE_OFF+'">')
      sys.exit()
    elif opt in ("-r", "--relay"):
      print('Relay is: ' + arg)
      relay = int(arg)
    elif opt in ("-w", "--wait"):
      print('Wait is: ' + arg)
      wait = int(arg)
    elif opt in ("-m", "--mode"):
      print('Mode is: ' + arg)
      if any(ext in arg for ext in [MODE_ON, MODE_OFF]):
        mode = arg
      else:
        print('Invalid relay mode: ' + arg)
        sys.exit()

  try:
    pin = ALL_PINS[relay]
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(pin, GPIO.OUT)
    if pin:
      if mode == MODE_ON:
        GPIO.output(pin, 1)
        print("Turned relay " + str(relay) + " ON")
        if wait:
          print("Waiting " + str(wait) + " seconds")
          time.sleep(wait)
          GPIO.output(pin, 0)
          print("Turned relay " + str(relay) + " OFF")

      elif mode == MODE_OFF:
        GPIO.output(pin, 0)
        print("Turned relay " + str(relay) + " OFF")
        if wait:
          print("Waiting " + str(wait) + " seconds")
          time.sleep(wait)
          GPIO.output(pin, 1)
          print("Turned relay " + str(relay) + " ON")

      else:
        print("Unexpected error")

    else:
        print("Invalid relay number (1-4): " + str(relay))

  except KeyboardInterrupt:
      print("Cancelled script")

  finally:
      GPIO.cleanup()

if __name__ == "__main__":
  main(sys.argv[1:])
