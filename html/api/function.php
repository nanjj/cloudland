<?php

function exec_cmd($cmd, $dm_info)
{
    exec($cmd, $dm_info, $dm_code);
    $dm_info[] = $dm_code;
    return $dm_info;
}
function write_file($contents)
{
    $filename="/opt/cloudland/cache/tmp/" . sha1(mt_rand() . $contents) . ".json";
    file_put_contents($filename, $contents);
    return $filename;
}
function launch_vm($image, $vlan, $name, $ip, $cpu, $memory, $disk_inc, $metadata)
{
    $username = $_SESSION["username"];
    $metadata = write_file($metadata);
    return exec_cmd("/opt/cloudland/scripts/frontend/launch_vm.sh '$username' '$image' '$vlan' '' '$name' '$ip' '$cpu' '$memory' '$disk_inc' '$metadata'");
}

function clear_vm($vm_ID)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/clear_vm.sh $username $vm_ID");
}

function create_vm($vm_ID)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/create_vm.sh '$username' '$vm_ID'");
}

function destroy_vm($vm_ID)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/destroy_vm.sh $username $vm_ID");
}

function attach_nic($vm_ID, $vlan)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/attach_nic.sh $username $vm_ID $vlan");
}

function attach_vol($vm_ID, $volume)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/attach_vol.sh $username $vm_ID $volume");
}

function detach_vol($volume)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/detach_vol.sh $username $volume");
}

function create_net($vlan, $network, $netmask, $gateway, $start_ip, $end_ip)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/create_net.sh '$username' '$vlan' '$network' '$netmask' '$gateway' '$start_ip' '$end_ip'");
}

function create_link($vlan, $shared, $desc)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/create_link.sh '$username' '$vlan' '$shared' '$desc'");
}

function clear_net($vlan, $network)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/clear_net.sh $username $vlan $network");
}

function clear_link($vlan)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/clear_link.sh $username $vlan");
}

function create_snapshot($vm_ID, $desc)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/create_snapshot.sh '$username' '$vm_ID' '$desc'");
}

function delete_snapshot($vm_ID)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/delete_snapshot.sh '$username' '$vm_ID'");
}

function download_snapshot($inst_id)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/download_snapshot.sh '$username' '$inst_id'");
}

function create_vol($size, $image, $desc)
{
    $username = $_SESSION["username"];
    if ($image != "") {
        return exec_cmd("/opt/cloudland/scripts/frontend/create_volume_from_image.sh '$username' '$image' '$size' '$desc'");
    } else if ($size != "") {
        return exec_cmd("/opt/cloudland/scripts/frontend/create_volume.sh '$username' '$size' '$desc'");
    }
}

function delete_vol($name)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/delete_volume.sh $username $name");
}

function get_vol_list()
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_volume.sh $username");
}

function get_img_by_name($name)
{
    $image_info = array();
    $info = array();
    exec("/usr/bin/swift -A http://9.110.51.245:8080/auth/v1.0 -U system:root -K testpass stat images $name", $info);
    $image_info = array("img_name" => $info[2], "img_size" => $info[4], "img_version" =>$info[5]);

    return $image_info;
}

function upload_image_from_url($url, $shared, $desc, $platform)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/upload_image_from_url.sh '$username' '$url' '$shared' '$desc' '$platform'");
}

function upload_image($file)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/upload_image.sh $username $file");
}

function get_vm_list($vm_ID)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_vm.sh $username $vm_ID");
}

function get_img_list()
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_image.sh $username");
}

function get_snapshot_list()
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_snapshot.sh $username");
}

function delete_img($img_name)
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/delete_image.sh $username $img_name");
}

function get_net_list()
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_net.sh $username");
}

function get_link_list()
{
    $username = $_SESSION["username"];
    return exec_cmd("/opt/cloudland/scripts/frontend/list_link.sh $username");
}

?>

