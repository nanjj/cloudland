#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 2 ] && echo "$0 <user> <vm_ID>" && exit -1

owner=$1
vm_ID=$2

num=`sqlite3 $db_file "select count(*) from snapshot where inst_id='$vm_ID' and owner='$owner' and status='created'"`
[ $num -lt 1 ] && die "Snapshot not valid or not VM owner!"
hyper_id=`sqlite3 $db_file "select id from compute where hyper_name=(select hyper_name from instance where inst_id='$vm_ID')"`
[ -n "$hyper_id" ] && /opt/cloudland/bin/sendmsg "inter $hyper_id" "/opt/cloudland/scripts/backend/`basename $0` $vm_ID"
sqlite3 $db_file "update snapshot set deleted=datetime('now'), status='deleted' where inst_id='$vm_ID'"
echo "$vm_ID|deleted"
