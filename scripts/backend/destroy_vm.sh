#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 1 ] && die "$0 <vm_ID>"

vm_ID=$1
virsh destroy $vm_ID
echo "|:-COMMAND-:| /opt/cloudland/scripts/frontback/`basename $0` $vm_ID"
