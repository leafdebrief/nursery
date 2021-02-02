#!/bin/bash
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

[ "$(whoami)" != 'root' ] && { echo -e "${RED}ERROR:${NC} This script requires root access – try running as ${CYAN}sudo${NC}"; exit 1; }

filename=/boot/config.txt
thekey=''
newvalue=''

# A POSIX variable
while [[ $# -gt 0 ]] && [[ "$1" == "--"* ]] ;
do
    opt="$1";
    shift;
    case "$opt" in
        "--" ) break 2;;
        "--key" )
           thekey="$1"; shift;;
        "--value" )
           newvalue="$1"; shift;;
        *) echo >&2 "Invalid option: $opt"; exit 1;;
   esac
done

[ "$thekey" == "" ] || [ "$newvalue" == "" ] && { echo -e "${RED}ERROR:${NC} Please provide a ${CYAN}--key${NC} and ${CYAN}--value${NC}"; exit 1; }

if ! grep -R "^[#]*\s*${thekey}=.*" $filename > /dev/null; then
  echo "Property '${thekey}' not found; creating and setting to ${newvalue}"
  sed -ir "/^[#]*\s*dtparam=spi=.*/a ${thekey}=${newvalue}" $filename
else
  echo "Property '${thekey}' found; setting to ${newvalue}"
  sed -ir "s/^[#]*\s*${thekey}=.*/$thekey=${newvalue}/" $filename
fi