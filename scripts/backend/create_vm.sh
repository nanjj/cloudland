#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 1 ] && die "$0 <vm_ID>"

vm_ID=$1
vm_xml=$xml_dir/$vm_ID.xml
[ -f $vm_xml ] || die "No such vm definition"

virsh create $vm_xml
virsh dumpxml $vm_ID > $vm_xml
[ $? -eq 0 ] || die "failed to create vm"
vm_stat=running
echo "|:-COMMAND-:| /opt/cloudland/scripts/frontback/`basename $0` '$vm_ID' '$vm_stat'"
