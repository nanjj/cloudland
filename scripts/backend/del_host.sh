#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 3 ] && die "$0 <vlan> <mac> <ip>"

vlan=$1
vm_mac=$2
vm_ip=$3

dns_host=$dmasq_dir/vlan$vlan.host
sed -i "/^$vm_mac/d" $dns_host
ip netns exec vlan$vlan dhcp_release ns-$vlan $vm_ip $vm_mac
dns_pid=`ps -ef | grep dnsmasq | grep "\<interface=ns-$vlan\>" | awk '{print $2}'`
[ -n "$dns_pid" ] && kill -HUP $dns_pid
echo "DHCP config for $vm_mac: $vm_ip in vlan $vlan was removed."
