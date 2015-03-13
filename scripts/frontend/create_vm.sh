#!/bin/bash

cd `dirname $0`
source ../cloudrc

[ $# -lt 2 ] && die "$0 <user> <vm_ID>"

owner=$1
vm_ID=$2

sql_ret=`sqlite3 $db_file "select hyper_name from instance where inst_id='$vm_ID' and owner='$owner' and (status='stopped' or status='error')"`
[ -z "$sql_ret" ] && die "No such stopped VM!"

hyper=`echo $sql_ret | cut -d'|' -f1`
hyper_id=`sqlite3 $db_file "select id from compute where hyper_name='$hyper'"`

if [ -n "$hyper_id" ]; then
    /opt/cloudland/bin/sendmsg "inter $hyper_id" "/opt/cloudland/scripts/backend/`basename $0` $vm_ID"
else
    /opt/cloudland/bin/sendmsg "toall" "/opt/cloudland/scripts/backend/`basename $0` $vm_ID"
fi

echo "$vm_ID|launching"
