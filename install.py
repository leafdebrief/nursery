try:
    from adafruit_shell import Shell
except ImportError:
    raise RuntimeError("The library 'adafruit_shell' was not found. To install, try typing: sudo pip3 install adafruit-python-shell")
import os
import getpass
import argparse
import nginx

shell = Shell()
shell.group="Nursery"
default_python = 3

def default_python_version(numeric=True):
    version = shell.run_command("python -c 'import platform; print(platform.python_version())'", suppress_message=True, return_output=True)
    if numeric:
        return float(version[0:version.rfind(".")])
    return version

def sys_update():
    print("Updating System Packages")
    if not shell.run_command("sudo apt-get update"):
        shell.bail("Apt failed to update indexes!")
    print("Upgrading packages...")
    if not shell.run_command("sudo apt-get -y upgrade"):
        shell.bail("Apt failed to install software!")

def set_raspiconfig():
    """
    Enable various Raspberry Pi interfaces
    """
    print("Enabling I2C")
    shell.run_command("sudo raspi-config nonint do_i2c 0")
    print("Enabling SPI")
    shell.run_command("sudo raspi-config nonint do_spi 0")
    print("Enabling Serial")
    shell.run_command("sudo raspi-config nonint do_serial 0")
    print("Enabling SSH")
    shell.run_command("sudo raspi-config nonint do_ssh 0")
    print("Enabling Camera")
    shell.run_command("sudo raspi-config nonint do_camera 0")
    print("Disable raspi-config at Boot")
    shell.run_command("sudo raspi-config nonint disable_raspi_config_at_boot 0")

def update_python():
    print("Making sure Python 3 is the default")
    if default_python < 3:
        shell.run_command("sudo apt-get install -y python3 git python3-pip")
        shell.run_command("sudo update-alternatives --install /usr/bin/python python $(which python2) 1")
        shell.run_command("sudo update-alternatives --install /usr/bin/python python $(which python3) 2")
        shell.run_command("sudo update-alternatives --skip-auto --config python")

def update_pip():
    print("Ensuring Python Pip3 is installed")
    shell.run_command("sudo apt-get install -y python3-pip")
    shell.run_command("sudo pip3 install --upgrade setuptools")

def install_blinka():
    print("Installing latest version of Adafruit Blinka locally")
    shell.run_command("sudo apt-get install -y i2c-tools")
    shell.run_command("pip3 install --upgrade RPi.GPIO")
    shell.run_command("pip3 install --upgrade adafruit-blinka")

def install_nursery(home_dir="/home/pi"):
    print(f"Setting up Jasper's Nursery in {home_dir}")

    print("Installing Nursery APT dependencies")
    shell.run_command("sudo apt-get install -y git nginx certbot python-certbot-nginx php-fpm php-xml php-cli php-zip unzip php7.3-mysql php7.3-mbstring mariadb-server python3 python3-pip libgpiod2 python3-picamera")
    print("Installing Nursery PHP dependencies")
    shell.run_command(f"git clone https://github.com/leafdebrief/nursery.git {home_dir}/nursery")
    shell.run_command(f"php -r \"copy('https://getcomposer.org/installer', '{home_dir}/composer-setup.php');\"")
    shell.run_command(f"COMPOSER_HASH=\"$(wget -q -O - https://composer.github.io/installer.sig)\" && php -r \"if (hash_file('SHA384', '{home_dir}/composer-setup.php') === '$COMPOSER_HASH') {{ echo 'Installer verified'; }} else {{ echo 'Installer corrupt'; unlink('{home_dir}/composer-setup.php'); }} echo PHP_EOL;\"")
    shell.run_command("sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer")
    shell.run_command(f"composer install -d {home_dir}/nursery -n", False, True)
    print("Installing Nursery Python dependencies")
    shell.run_command("sudo pip3 install python-nginx mysql-connector-python adafruit-circuitpython-ads1x15 adafruit-circuitpython-as7341 adafruit-circuitpython-si7021 adafruit-circuitpython-lps35hw adafruit-circuitpython-seesaw adafruit-circuitpython-dht adafruit-circuitpython-ahtx0")
    print("Ensuring Nursery is writable")
    shell.run_command(f"touch {home_dir}/nursery/logs/app.log")
    shell.run_command(f"sudo chown -R www-data:www-data {home_dir}/nursery/logs")
    shell.run_command("sudo echo \"www-data ALL=(ALL) NOPASSWD: ALL\" >> /etc/sudoers")
    print("Enabling Nursery daemon scripts")
    shell.copy(f"{home_dir}/nursery/scripts/daemons/nursery-{{cam,db}}.service", "/lib/systemd/system/")
    shell.run_command(f"sudo cp {home_dir}/nursery/scripts/daemons/nursery-{{cam,db}}.service /lib/systemd/system/")
    shell.run_command("sudo chmod 644 /lib/systemd/system/nursery-{cam,db}.service")
    shell.run_command("sudo systemctl daemon-reload")
    shell.run_command("sudo systemctl enable nursery-db nursery-cam")
    
def install_database(home_dir="/home/pi"):
    shell.print_colored("Configuring your MySQL database for Jasper's Nursery", "cyan")
    
    password = getpass.getpass(prompt='Please enter a new MySQL root password: ')
    verify = getpass.getpass(prompt='Type again to verify: ')
    
    if password != verify:
        shell.bail("Passwords do not match, please try again by running the installer script again with the '--db-only' (-d) option")

    shell.run_command(f"mysqladmin password '{password}'")
    print("Deleting anonymous users")
    shell.run_command(f"mysql -u root -p {password} -e \"DELETE FROM mysql.user WHERE User='';\"")
    print("Disabling remote root login")
    shell.run_command(f"mysql -u root -p {password} -e \"DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');\"")
    print("Removing test databases")
    shell.run_command(f"mysql -u root -p {password} -e \"DROP DATABASE test;\"")
    print("Removing test database users")
    shell.run_command(f"mysql -u root -p {password} -e \"DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%';")
    print("Flushing table privileges")
    shell.run_command(f"mysql -u root -p {password} -e \"FLUSH PRIVILEGES;\"")
    print("Setting up tables")
    shell.run_command(f"mysql -u username -p {password} < {home_dir}/nursery/scripts/sql/nursery.sql")

def install_server(home_dir="/home/pi"):
    print("Loading template server")
    c = nginx.loadf(f"{home_dir}/nursery/nursery.leafdebrief.com")
    print("Configuring server blocks")
    c.server.add(
        nginx.Location(
            '/home',
            nginx.Key('alias', f"{home_dir}/nursery/public/home"),
            nginx.Key('try_files', '$uri $uri/ /home/index.html')
        )
    )
    c.server.add(nginx.Key('root', f"{home_dir}/nursery/public"))
    
    src = '/etc/nginx/sites-available/nursery'
    dest = '/etc/nginx/sites-enabled/nursery'
    
    print("Copying server config to sites-available")
    nginx.dumpf(c, src)
    print("Symlinking server config into sites-enabled")
    os.symlink(src, dest)
    
    print("Reloading server")
    shell.run_command("sudo service nginx reload")
    shell.print_colored("""
Server has been reloaded!

Please run \"sudo certbot --nginx\" after this installation if you
have a domain name and intend to configure HTTPS (recommended).
""", 'orange')

def main():
    global default_python
    
    shell.clear()
    
    # Check Raspberry Pi and Bail
    pi_model = shell.get_board_model()
    
    # Get user home directory
    home_dir = os.path.expanduser(shell.run_command("echo ~${SUDO_USER}", False, True))
    
    if shell.exists(f"{home_dir}/nursery"):
        if not shell.prompt("It looks like Jasper's Nursery is already installed. Would you like to reinstall it?"):
            shell.bail("Cancelling installation")
        shell.remove(f"{home_dir}/nursery")
    
    # Get command line args
    parser = argparse.ArgumentParser(description="Install Jasper's Nursery")
    parser.add_argument('-d', '--db-only', action='store_true', help='only setup database')
    args = parser.parse_args()
    
    shell.print_colored("""
  ___                        
 (   >                  o    
  __/___.  _   _   _  __' _  
 / / (_/|_/_)_/_)_</_/ (_/_)_
<_/          /               
            '                                                    
 o     :mNM/-MMMo  ``.`:mmmmmmdy/     /dMMMMd+`.hhhddddo+dmmmmmds: +yyy.   :sss-
 My    hMMd yMMN`  mMMh.MMMMhdMMMh   oMMMMmMMMs-MMMMMMMy:MMMNhmMMMs`dMMm` :MMMy 
`MMy  .MMM-.MMMo  `MMMo NMMN  yMMM   hMMMs `os -MMM:...``MMMd  hMMm `dMMm:NMMo  
.MMMy oMMy oMMN`  :MMM/ yMMMohMMM+   .dMMMm+`  :MMM.     dMMNodMMM/  `dMMMMM+   
-MMMMymMM. mMMy   oMMM. +MMMMMMM/      /dMMMN+ :MMMMNMs  sMMMMMMM:    `dMMM/    
/MMhMMMMs `MMMo   dMMm  .MMModMMN-  `s+` -hMMM+/MMN///-  :MMM+mMMm.    hMMM     
+MM:yMMN` `MMMh  +MMM/   NMM/`dMMN:.mMMNy//MMMh/MMd      `MMM-.mMMN.   NMMN     
oMM-`mM+   yMMMMMMMMo    yMMo `hMMM:+mMMMMMMMm.+MMNhhhy`  dMM/ `mMMN. .MMMd     
sMM- .d     omMMMNy-     /MNo   sy+-  -+sys+-  +NNNNNMM.  oMN+  `hy+- .+os/     

""", 'green')
    shell.print_colored("This script  will configure your Raspberry Pi and install requirements.", 'cyan')
    print("\n{} detected.\n".format(pi_model))
    if not shell.is_raspberry_pi():
        shell.bail("Non-Raspberry Pi board detected. This must be run on a Raspberry Pi")
    if shell.get_os() != "Raspbian":
        shell.bail("Sorry. This script currently only runs on Raspberry Pi OS.")
    if not shell.is_python3():
        shell.bail("You must be running Python 3. Older versions have now been deprecated.")
    if default_python_version() < 3:
        shell.warn("WARNING Default System python version is {}. It will be updated to Version 3.".format(default_python_version(False)))
        default_python = 2
        if not shell.prompt("Continue?"):
            shell.exit()

    if not args.db_only:
        sys_update()
        set_raspiconfig()
        update_python()
        update_pip()
        install_blinka()
        install_nursery(home_dir)
        install_server(home_dir)

    install_database(home_dir)

    # Done
    print("""DONE.

Settings take effect on next boot.
""")
    if not shell.prompt("REBOOT NOW?", default="y"):
        print("Exiting without reboot.")
        shell.exit()
    print("Reboot started...")
    os.sync()
    shell.reboot()
    shell.exit()

# Main function
if __name__ == "__main__":
    shell.require_root()
    main()
