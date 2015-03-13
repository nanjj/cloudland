#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 4 ] && echo "$0 <vlan> <mac> <name> <ip>"

vlan=$1
vm_mac=$2
vm_name=$3
vm_ip=$4

dns_host=$dmasq_dir/vlan$vlan.host
echo "$vm_mac,$vm_name.$cloud_domain,$vm_ip" >> $dns_host
dns_pid=`ps -ef | grep dnsmasq | grep "\<interface=ns-$vlan\>" | awk '{print $2}'`
[ -n "$dns_pid" ] && kill -HUP $dns_pid
echo "DHCP config for $vm_mac: $vm_ip in vlan $vlan was setup."
mkdir -p $mudata_dir/$vlan/$ip
