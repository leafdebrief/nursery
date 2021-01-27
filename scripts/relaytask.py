import RPi.GPIO as GPIO
import time

pin1 = 31
pin2 = 33
pin3 = 35
pin4 = 37
time.sleep(10)
try:
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(pin1, GPIO.OUT)
    GPIO.setup(pin2, GPIO.OUT)
    GPIO.setup(pin3, GPIO.OUT)
    GPIO.setup(pin4, GPIO.OUT)

    GPIO.output(pin1, 1)
    time.sleep(1)
    GPIO.output(pin2, 1)
    time.sleep(1)
    GPIO.output(pin3, 1)
    time.sleep(1)
    GPIO.output(pin4, 1)
    time.sleep(1)
    GPIO.output(pin1, 0)
    time.sleep(1)
    GPIO.output(pin2, 0)
    time.sleep(1)
    GPIO.output(pin3, 0)
    time.sleep(1)
    GPIO.output(pin4, 0)

except KeyboardInterrupt:
    print("Cancelled script")

finally:
    GPIO.cleanup()
