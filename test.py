import random
import time
import requests
import threading

server = 'http://iotserver.com/recordtemp.php'
MIN_TEMP = 48
MAX_TEMP = 52
INITIAL_TEMP = 50
TEMP_STEP = 0.1
ERROR_MODE = True

def toggle_error_mode():
    global ERROR_MODE
    ERROR_MODE = not ERROR_MODE
    
    if ERROR_MODE: 
        print("enabled")
    else:
         print("disabled")   

def report_to_server(temp):
    payload = {'t': temp}  # Update payload keys
    try:
        r = requests.get(server, params=payload)
        if r.status_code == 200:
            print("Report sent successfully.")
            print(r.text)
            return True
        else:
            print("Error: Server responded with status code", r.status_code)
            return False
    except requests.exceptions.RequestException as e:
        print("Error:", e)
        return False

def simulate_temperature():
    temp = INITIAL_TEMP
    increasing = True
    
    while True:
        if increasing:
            temp = temp + (TEMP_STEP + random.uniform(0, 0.05))
        else:
            temp = temp - (TEMP_STEP + random.uniform(0, 0.05))
            
        if increasing and temp > MAX_TEMP:
            print("max over")
            temp = temp + random.uniform(0.1, 0.5)
            increasing = False
        elif not increasing and temp < MIN_TEMP:
            print("min over")
            temp = temp - random.uniform(0.1, 0.5)
            increasing = True
            
        if ERROR_MODE and random.random() < 0.1:
            print("ERROR")
            temp = temp + random.uniform(-5,5)
            print("Error introduced: ", temp)
        
        print("Current Temperature:",temp)
        
        report_to_server(temp)
        
        time.sleep(1)
        
if __name__ == "__main__":
    print("Starting temperature simulation")
    print("Press 'e' to toggle error mode")
    print("Press 'q' to quit") 
    
    input_thread = threading.Thread(target=simulate_temperature)
    input_thread.daemon = True
    input_thread.start()
    
    while True:
        user_input = input()
        if user_input.lower() == 'e':
            toggle_error_mode()
        elif user_input.lower() == 'q':
            print("Quitting simulation.")
            break           
