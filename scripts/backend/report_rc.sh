#!/bin/bash

cd `dirname $0`
source ../cloudrc

function avail_memory()
{
    free -m | awk '/^Mem:/ {print ($4 + $7)}'
}

function avail_disk()
{
    df --block-size=10m /var | awk '/^\// {print $4}'  
}

function avail_cpu()
{
    vmstat | tail -1 | awk '{print $15}' 
}

function current_io()
{
    sar -d 1 3 | tail -1 | awk '{printf "%d\n", (200000 - $4 - $5)}'
}

function current_RXTX()
{
    sar -n DEV 3 3 | grep "$vxlan_interface" | tail -1 | awk '{printf "%d\n", (2000000 - ($5 + $6) * 8)}'
}

while true; do
    let avail_all=`avail_memory`+`avail_disk`+`avail_cpu`*100+`current_io`+`current_RXTX`
    echo $avail_all
    let intval=$RANDOM%5+1
    sleep $intval
done
