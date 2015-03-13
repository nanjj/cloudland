#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 2 ] && die "$0 <vm_ID> <status>"

vm_ID=$1
status=$2
sqlite3 $db_file "update instance set status='$status' where inst_id='$vm_ID'"
