#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 1 ] && die "$0 <vm_ID>"

vm_ID=$1

sqlite3 $db_file "update instance set status='stopped' where inst_id='$vm_ID'"
