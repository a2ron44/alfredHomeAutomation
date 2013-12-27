import smbus, sys
import time
# for RPI version 1, use "bus = smbus.SMBus(0)"
bus = smbus.SMBus(1)
# This is the address we setup in the Arduino Program
address = 0x04

if len(sys.argv) < 3:
    print -1
    sys.exit()


cmd = sys.argv[1]
msg = sys.argv[2]

msAr = []
for x in msg:
    msAr.append(ord(x))

def writeNumber():
    #bus.write_byte(address, value)
    bus.write_i2c_block_data(address,ord(cmd), msAr)
    return -1

def readNumber():
    number = bus.read_byte(address)
    # number = bus.read_byte_data(address, 1)
    return number

writeNumber()

# sleep one second
time.sleep(1)
    
res = readNumber()
print res
