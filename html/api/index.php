<?php
function return_value($value)
{
    // return JSON array
    if (!is_array($value)) {
        $value=array($value);
        $value[] = 255;
    }
    exit(json_encode($value));
}
require_once("function.php");
require_once("user.php");
$USER = new User();

$possible_url = array("get_img_list", "get_img", "get_vm_list", "get_net_list", 'get_link_list', "get_vol_list", "get_snapshot_list");

$value = "An error has occurred";

if (!$USER->authenticated) {
    return_value("You need to login before proceed!");
} elseif (isset($_POST["op"]) && isset($_POST["sha1"])) {
    $value = "Welcome Cloudland";
}

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
    switch ($_GET["action"])
    {
        case "get_img_list":
            $value = get_img_list();
            break;
        case "get_img":
            if (isset($_GET["name"]))
                $value = get_img_by_name($_GET["name"]);
            else
                $value = "Missing argument";
            break;
        case "get_vm_list":
            $vm_ID = "";
            if (isset($_GET["vm_ID"])) {
                $vm_ID = $_GET["vm_ID"];
            }
            $value = get_vm_list($vm_ID);
            break;
        case "get_net_list":
            $value = get_net_list();
            break;
        case "get_link_list":
            $value = get_link_list();
            break;
        case "get_vol_list":
            $value = get_vol_list();
            break;
        case "get_snapshot_list":
            $value = get_snapshot_list();
            break;

    }
}

#if (isset($_POST["action"]) && in_array($_POST["action"], $post_url))
if (isset($_POST["exec"]))
{
    switch ($_POST["exec"])
    {
        case "launch_vm":
            if (!isset($_POST["image"])) {
                $value = "No image specified!";
                return;
            }
            if (!isset($_POST["vlan"])) {
                $value = "No vlan specified!";
                return;
            }
            $name = "";
            if (isset($_POST["name"])) {
                $name = $_POST["name"];
            }
            $ip = "";
            if (isset($_POST["ip"])) {
                $ip = $_POST["ip"];
            }
            $cpu = "";
            if (isset($_POST["cpu"])) {
                $cpu = $_POST["cpu"];
            }
            $memory = "";
            if (isset($_POST["memory"])) {
                $memory = $_POST["memory"];
            }
            $disk_inc = "";
            if (isset($_POST["disk_inc"])) {
                $disk_inc = $_POST["disk_inc"];
            }
            $metadata = "";
            if (isset($_POST["metadata"])) {
                $metadata = $_POST["metadata"];
            }
            $value = launch_vm($_POST["image"], $_POST["vlan"], $name, $ip, $cpu, $memory, $disk_inc, $metadata);
            break;
        case "clear_vm":
            if (!isset($_POST["vm_ID"])) {
                $value = "No vm_ID specified!";
                return;
            }
            $value = clear_vm($_POST["vm_ID"]);
            break;
        case "create_vm":
            if (!isset($_POST["vm_ID"])) {
                $value = "No vm_ID specified!";
                return;
            }
            $value = create_vm($_POST["vm_ID"]);
            break;
        case "destroy_vm":
            if (!isset($_POST["vm_ID"])) {
                $value = "No vm_ID specified!";
                return;
            }
			$force="false";
            if (isset($_POST["force"])) {
				$force=$_POST["force"];
            }
            $value = destroy_vm($_POST["vm_ID"], $force);
            break;
        case "attach_nic":
            if (!isset($_POST["vm_ID"])) {
                $value = "No vm_ID specified!";
                break;
            }
            if (!isset($_POST["vlan"])) {
                $value = "No vlan specified!";
                break;
            }
            $value = attach_nic($_POST["vm_ID"], $_POST["vlan"]);
            break;
        case "create_link":
            if (!isset($_POST["vlan"])) {
                $value = "No vlan specified!";
                break;
            }
            $shared = "";
            if (isset($_POST["shared"])) {
                $shared = $_POST["shared"];
            }
            $link_desc = "";
            if (isset($_POST["link_desc"])) {
                $link_desc = $_POST["link_desc"];
            }
            break;
        case "create_net":
            if (!isset($_POST["vlan"])) {
                $value = "No vlan specified!";
                break;
            }
            if (!isset($_POST["network"])) {
                $value = "No network specified!";
                break;
            }
            if (!isset($_POST["netmask"])) {
                $value = "No netmask specified!";
                break;
            }
            $gateway = "";
            if (isset($_POST["gateway"])) {
                $gateway = $_POST["gateway"];
            }
            $start_ip = "";
            if (isset($_POST["start_ip"])) {
                $start_ip = $_POST["start_ip"];
            }
            $end_ip = "";
            if (isset($_POST["end_ip"])) {
                $end_ip = $_POST["end_ip"];
            }
            $value = create_link($_POST["vlan"], $shared, $link_desc);
            break;
        case "clear_link":
            if (!isset($_POST["vlan"])) {
                $value = "No vlan specified!";
                break;
            }
            $value = clear_link($_POST["vlan"]);
            break;
        case "clear_net":
            if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                $value = "No vlan specified!";
                break;
            }
            if (!isset($_POST["network"]) || ($_POST["network"] == "")) {
                $value = "No network specified!";
                break;
            }
            $value = clear_net($_POST["vlan"], $_POST["network"]);
            break;
        case "upload_img":
            if (!isset($_POST["img_url"])) {
                $value = "No image URL specified";
                break;
            }
            if (!isset($_POST["platform"])) {
                $value = "No image platform specified";
                break;
            }
            $shared = "";
            if (isset($_POST["shared"])) {
                $shared = $_POST["shared"];
            }
            $img_desc = "";
            if (isset($_POST["img_desc"])) {
                $img_desc = $_POST["img_desc"];
            }
            $value = upload_image_from_url($_POST["img_url"], $shared, $img_desc, $_POST["platform"]);
            break;
       case "delete_img":
           if (!isset($_POST["img_name"])) {
               $value = "No image name specified";
               break;
           }
           $value = delete_img($_POST["img_name"]);
           break;
       case "create_vol":
           if (!isset($_POST["vol_size"]) && !isset($_POST["img_name"])) {
             $value="Either image name or size must be set.";
             break;
           }
           $vol_desc = "";
           if (isset($_POST["vol_desc"])) {
             $vol_desc = $_POST["vol_desc"];
           }
           $vol_size = "";
           if (isset($_POST["vol_size"])) {
             $vol_size = $_POST["vol_size"];
           }
           $img_name = "";
           if (isset($_POST["img_name"])) {
             $img_name = $_POST["img_name"];
           }
           $value = create_vol($vol_size, $img_name, $vol_desc);
           break;
       case "delete_vol":
           if (!isset($_POST["vol_name"])) {
             $value="vol_name is not set.";
             break;
           }
           $value = delete_vol($_POST["vol_name"]);
           break;
       case "attach_vol":
           if (!isset($_POST["vol_name"])) {
             $value="vol_name is not set.";
             break;
           }
           if (!isset($_POST["vm_ID"])) {
             $value="vm_ID is not set.";
             break;
           }
           $value = attach_vol($_POST["vm_ID"], $_POST["vol_name"]);
           break;
       case "detach_vol":
           if (!isset($_POST["vol_name"])) {
             $value="vol_name is not set";
             break;
           }
           $value = detach_vol($_POST["vol_name"]);
           break;
       case "create_snapshot":
           if (!isset($_POST["vm_ID"])) {
             $value="vm_ID is not set.";
             break;
           }
           $snap_desc = "";
           if (isset($_POST["snap_desc"])) {
             $snap_desc = $_POST["snap_desc"];
           }
           $value = create_snapshot($_POST["vm_ID"], $snap_desc);
           break;
       case "delete_snapshot":
           if (!isset($_POST["snapshot"])) {
             $value="snapshot is not set.";
             break;
           }
           $value = delete_snapshot($_POST["snapshot"]);
           break;
       case "download_snapshot":
           if (!isset($_POST["snapshot"])) {
             $value="snapshot is not set.";
             break;
           }
           $value = download_snapshot($_POST["snapshot"]);
           break;
    }
}
return_value($value)
?>
