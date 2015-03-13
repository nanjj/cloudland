#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 3 ] && die "$0 <user> <vm_ID> <force>"

owner=$1
vm_ID=$2
force=$3
sql_ret=`sqlite3 $db_file "select hyper_name from instance where inst_id='$vm_ID' and owner='$owner' and status='running'"`
[ -z "$sql_ret" ] && die "No such VM!"
hyper=`echo $sql_ret | cut -d'|' -f1`
hyper_id=`sqlite3 $db_file "select id from compute where hyper_name='$hyper'"`
if [ -n "$hyper_id" ]; then
    /opt/cloudland/bin/sendmsg "inter $hyper_id" "/opt/cloudland/scripts/backend/`basename $0` $vm_ID $force"
else
    /opt/cloudland/bin/sendmsg "toall" "/opt/cloudland/scripts/backend/`basename $0` $vm_ID $force"
fi
echo "$vm_ID|stopping"
