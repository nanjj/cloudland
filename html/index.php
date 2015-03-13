<?php
    date_default_timezone_set("Asia/Hong_Kong");
    require_once("api/function.php");
    require_once("api/user.php");
    $USER = new User();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>CloudLand</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<script type="text/javascript" src="js/sha1.js"></script>
<script type="text/javascript" src="js/user.js"></script>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="main">
<!-- header begins -->
<div id="header">
	<div id="logo">CloudLand</div>
    <div id="buttons">
          <div><a href="index.php?request=get_vol_list" class="but_1"  title="">Volume</a></div>
          <div><a href="index.php?request=get_vm_list" class="but_2" title="">Instance</a></div>
          <div><a href="index.php?request=get_img_list"  class="but_3" title="">Image</a></div>
          <div><a href="https://w3-connections.ibm.com/wikis/home?lang=en-us#!/wiki/W2e52de5e48f0_4f00_a00f_5ce1f8e43d09/page/How%20to%20acquire%20nested%20kvm%20VMs"  class="but_4" title="">Document</a></div>
          <div ><a href="index.php?request=get_net_list" class="but_5" title="">network</a></div>
    </div>
</div>
<!-- header ends -->
    <!-- content begins -->
    	<div id="content">
        	<div id="right">
            	<h1>Welcome CloudLand</h1>
                <div class="tit_bot">
                <br />
<?php       if(!$USER->authenticated) { ?>

            <!-- Allow a user to log in -->
            <form class="controlbox" name="log in" id="login" action="index.php" method="POST">
                <input type="hidden" name="op" value="login"/>
                <input type="hidden" name="sha1" value=""/>
                <table>
                    <tr><td><h3>user name </h3></td><td><input type="text" name="username" value="" /></td></tr>
                    <tr><td><h3>password </h3></td><td><input type="password" name="password1" value="" /></td></tr>
                </table>
                <input type="button" name="login1" value="log in" onclick="User.processLogin()"/>
            </form>
<?php       } else { ?>

            <!-- Log out option -->
            <form class="controlbox" name="log out" id="logout" action="index.php" method="POST">
                <input type="hidden" name="op" value="logout"/>
                <input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
                <h2> You are logged in as <?php echo $_SESSION["username"]; ?></h2>
                <h2><input type="submit" value="log out"/></h2>
            </form>
<?php       } ?>
                 </div>   
           </div>  
            <div id="left">
            	<div class="left_top"></div>
              	<div class="left_s">
<?php       if(!$USER->authenticated) { ?>
                    <h2>CloudLand Registration:</h2>
                    Note: do not use same email for different user and password length is at least 8.
                    <div class="col_l">
            <form class="controlbox" name="new user registration" id="registration" action="index.php" method="POST">
                <input type="hidden" name="op" value="register"/>
                <input type="hidden" name="sha1" value=""/>
                <table>
                    <tr><td><h3>user name: </h3></td><td><input type="text" name="username" value="" /></td></tr>
                    <tr><td><h3>email address: </h3></td><td><input type="text" name="email" value="" /></td></tr>
                    <tr><td><h3>password: </h3></td><td><input type="password" name="password1" value="" /></td></tr>
                    <tr><td><h3>password (again): </h3></td><td><input type="password" name="password2" value="" /></td></tr>
                </table>
                <input type="button" value="register" onclick="User.processRegistration()"/>
            </form>
<?php       } else { ?>
                <div class="col_l">
<?php       } 
            if ($USER->authenticated && isset($_GET["request"])) {
                switch ($_GET["request"]) {
                    case "get_vol_list":
                        $vol_list = get_vol_list();
?>
<h2>Create Blank Volume:</h2>
<form id="create_vol" action="index.php" method="POST">
        Volume Size (G): <input type="text" name="vol_size" value="" />
        Description: <input type="text" name="vol_desc" size="40" value="" />
        <input type="hidden" name="execution" value="create_vol"/>
        <input type="submit" value="create"/>
</form>
<br /><h2>Create Volume from Image:</h2>
<form id="create_vol" action="index.php" method="POST">
        <select id="img_name" name="img_name">
            <option value="">Image</option>
<?php 
                        $img_list = get_img_list();
                        foreach ($img_list as $img):
                            if ($img == "")
                                continue;
                            $img_info = explode('|', $img);
                            echo "<option>".$img_info[0]."</option>";
                        endforeach;
?>
        </select>
        Increase size (G): <input type="text" name="vol_size" size="5" value="" />
        Description: <input type="text" name="vol_desc" size="30" value="" />
        <input type="hidden" name="execution" value="create_vol"/>
        <input type="submit" value="create"/>
</form>
<br /><h2>Attach Volume to Instance:</h2>
<form id="attach_vol" action="index.php" method="POST">
        <select id="vol_name" name="vol_name">
            <option value="">Volume</option>
<?php 
                        foreach ($vol_list as $vol):
                            if ($vol == "")
                                continue;
                            $vol_info = explode('|', $vol);
                            echo "<option>".$vol_info[0]."</option>";
                        endforeach;
?>
        </select>
    <select id="vm_ID" name="vm_ID">
            <option value="">Instance</option>
<?php
                        $inst_list = get_vm_list();
                        foreach ($inst_list as $inst):
                            if ($inst == "")
                                continue;
                            $vinfo = explode('|', $inst);
                            echo "<option> ".$vinfo[0]."</option>";
                        endforeach;
?>
    </select>
    <input type="hidden" name="execution" value="attach_vol"/>
    <input type="submit" value="attach"/>
</form>
<br /><h2>Volume Information:</h2>
<?php 
                        echo "<table><tr><td width='20%'><h3>Name</h3></td><td width='5%'><h3>Size</h3></td><td width='15%'><h3>Description</h3></td><td width='10%'><h3>Instance</h3></td><td width='10%'><h3>Device</h3></td><td><h3>Bootable</h3></td><td><h3>Action</h3></td></tr>";
                        foreach ($vol_list as $vol):
                            if ($vol == "")
                                continue;
                            $vol_info = explode('|', $vol);
                            echo "<tr><td>".$vol_info[0]."</td><td>".$vol_info[1]."</td><td>".$vol_info["2"]."</td><td>".$vol_info["3"]."</td><td>".$vol_info[4]."</td><td>".$vol_info[5]."</td><td>"; 
                        if ($vol_info[3] == '') { 
?>
                <form onsubmit="return confirm('Are you sure to delete?')" id="operate_vol" action="index.php" method="POST">
                    <input type="hidden" name="vol_name" value="<?php echo $vol_info[0]?>"/>
                    <input type="hidden" name="execution" value="delete_vol"/>
                    <input type="submit" value="delete"/>
                </form>
<?php                   } else { ?>
                <form id="detach_vol" action="index.php" method="POST">
                    <input type="hidden" name="vol_name" value="<?php echo $vol_info[0]?>"/>
                    <input type="hidden" name="execution" value="detach_vol"/>
                    <input type="submit" value="detach"/>
                </form>

<?php                        }
                            echo "</td></tr>";
                        endforeach;
                        echo "</table>";
                        break;
                    case "get_vm_list":
?>
<h2>Launch Instance:</h2>
<form id="launch_vm" action="index.php" method="POST">
        <select id="image" name="image">
            <option value="">Image</option>
<?php 
                        $img_list = get_img_list();
                        foreach ($img_list as $img):
                            if ($img == "")
                                continue;
                            $img_info = explode('|', $img);
                            echo "<option>".$img_info[0]."</option>";
                        endforeach;
                        $vol_list = get_vol_list();
                        foreach ($vol_list as $vol):
                            if ($vol == "")
                                continue;
                            $vol_info = explode('|', $vol);
                            if ($vol_info[5] == "true" && $vol_info[3] == "") {
                                echo "<option>".$vol_info[0]."</option>";
                            }
                        endforeach;
?>
        </select>
        <select id="memory" name="memory">
            <option value="256">Memory</option>
            <option value="256">256M</option>
            <option value="512">512M</option>
            <option value="1024">1G</option>
            <option value="2048">2G</option>
            <option value="4096">4G</option>
            <option value="6144">6G</option>
            <option value="8192">8G</option>
            <option value="10240">10G</option>
            <option value="16384">16G</option>
            <option value="20480">20G</option>
            <option value="32768">32G</option>
        </select>
        <select id="cpu" name="cpu">
            <option value='1'>CPU Number</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
        </select>
        <select id="vlan" name="vlan">
            <option value="">Vlan</option>
<?php 
                        $link_list = get_link_list();
                        foreach ($link_list as $link):
                            $vinfo = explode('|', $link);
                            echo "<option>".$vinfo[0]."</option>";
                        endforeach;
?>
        </select>
        Hostname: <input type="text" name="hname" value="" />
        <br />Increase disk size (G): <input type="text" name="disk_inc" size="10" value="" />
        <input type="hidden" name="execution" value="launch_vm"/>
        <input type="submit" value="launch"/>
</form>
<br /><h2>Create Snapshot: </h2>
<form id="create_snapshot" action="index.php" method="POST">
    <input type="hidden" name="execution" value="create_snapshot"/>
    <select id="vm_ID" name="vm_ID">
            <option value="">Instance</option>
<?php
                        $inst_list = get_vm_list();
                        foreach ($inst_list as $inst):
                            if ($inst == "")
                                continue;
                            $vinfo = explode('|', $inst);
                            echo "<option> ".$vinfo[0]."</option>";
                        endforeach;
?>
    </select>
    Description: <input type="text" name="snap_desc" size="40" value="" />
    <input type="submit" value="create"/>
</form>
<br /><h2>Instance Information: </h2><a href="index.php?request=get_vm_list">Refresh</a>
<?php 
                        $inst_list = get_vm_list();
                        echo "<table><tr><td><h3>Instance</h3></td><td width=10%><h3>Image</h3></td><td><h3>Address</h3></td><td><h3>Hostname</h3></td><td><h3>Status</h3></td><td><h3>VNC</h3></td><td><h3>Action</h3></td></tr>";
                        foreach ($inst_list as $inst):
                            if ($inst == "")
                                continue;
                            list($inst_id, $img, $ip, $hname, $vlan, $status, $vnc) = split('[|]', $inst);
                            echo "<tr><td>".$inst_id."</td><td>".$img."</td><td>".$ip."</td><td>".$hname."</td><td>".$status."</td><td>".$vnc."</td><td>" ?>
                <form onsubmit="return confirm('Are you sure to delete?')" id="operate_vm" action="index.php" method="POST">
                    <input type="hidden" name="vm_ID" value="<?php echo $inst_id?>"/>
                    <input type="hidden" name="execution" value="clear_vm"/>
                    <input type="submit" value="delete"/>
                </form>
<?php                       echo "</td></tr>";
                        endforeach;
                        echo "</table>";
                        break;
                    case "get_img_list": 
?>
<h2>Upload Image:</h2>
<h4>Note: this operation is executed asynchronously, please check the result later.</h4>
<form name="upload_img" id="upload_img" action="index.php" method="POST">
    <input type="hidden" name="execution" value="upload_img"/>
    Image URL: <input type="text" name="img_url" size="85" value="" />
    <br />Description: <input type="text" name="img_desc" size="40" value="" />
    <select id="platform" name="platform">
        <option value="">Platform</option>
        <option value="Linux">Linux</option>
        <option value="Windows">Windows</option>
    </select>
    <select id="shared" name="shared">
        <option value="true">Shared</option>
        <option value="true">Yes</option>
        <option value="false">No</option>
    </select>
    <input type="submit" value="Upload"/>
</form>
<br /><h2>Image Information:</h2>
<?php
                        $img_list = get_img_list();
                        echo "<table><tr><td width='20%'><h3>Name</h3></td><td width='15%'><h3>Size</h3></td><td width='25%'><h3>Description</h3></td><td width='15%'><h3>Platform</h3></td><td><h3>Action</h3></td></tr>";
                        foreach ($img_list as $img):
                            if ($img == "")
                                continue;
                            $img_info = explode('|', $img);
                            echo "<tr><td>".$img_info[0]."</td><td>".$img_info[1]."</td><td>".$img_info["3"]."</td><td>".$img_info["2"]."</td><td>"; 
                        if ($img_info[4] == $_SESSION["username"]) { 
?>
                <form onsubmit="return confirm('Are you sure to delete?')" id="operate_img" action="index.php" method="POST">
                    <input type="hidden" name="img_name" value="<?php echo $img_info[0]?>"/>
                    <input type="hidden" name="execution" value="delete_img"/>
                    <input type="submit" value="delete"/>
                </form>
<?php
                        }
                           echo "</td></tr>";
                        endforeach;
                        echo "</table>";
                        break;
                    case "get_net_list": 
?>
<h2>Attach Interface to Instance:</h2>
<form id="attach_nic" action="index.php" method="POST">
    <input type="hidden" name="execution" value="attach_nic"/>
    <select id="vm_ID" name="vm_ID">
            <option value="">Instance</option>
<?php
                        $inst_list = get_vm_list();
                        foreach ($inst_list as $inst):
                            if ($inst == "")
                                continue;
                            $vinfo = explode('|', $inst);
                            echo "<option> ".$vinfo[0]."</option>";
                        endforeach;
?>
    </select>
    <select id="vlan" name="vlan">
            <option value="">Vlan</option>
<?php 
                        $link_list = get_link_list();
                        foreach ($link_list as $link):
                            $vinfo = explode('|', $link);
                            echo "<option>".$vinfo[0]."</option>";
                        endforeach;
?>
    </select>
    <input type="submit" value="attach"/>
</form>
<br/><h2>Create Vlan:</h2>
<form id="create_link" action="index.php" method="POST">
        Vlan Number: <input type="text" name="vlan" value="" />
        Description: <input type="text" name="link_desc" size="30" value="" />
        <select id="shared" name="shared">
            <option value="false">Shared</option>
            <option value="true">Yes</option>
            <option value="false">No</option>
        </select>
        <input type="hidden" name="execution" value="create_link"/>
        <input type="submit" value="create"/>
</form>
<br/><h2>Vlan Information:</h2>
<?php
                        echo "<table><tr><td width='30%'><h3>Vlan</h3></td><td width='50%'><h3>Description</h3></td><td><h3>Action</h3></td></tr>";
                        $link_list = get_link_list();
                        foreach ($link_list as $link):
                            $vinfo = explode('|', $link);
                            echo "<tr><td>$vinfo[0]</td><td>$vinfo[1]</td><td>"; 
                            if ($vinfo[2] == $_SESSION["username"]) {
?>
                <form onsubmit="return confirm('Are you sure to delete?')" id="operate_link" action="index.php" method="POST">
                    <input type="hidden" name="vlan" value="<?php echo $vinfo[0]?>"/>
                    <input type="hidden" name="execution" value="clear_link"/>
                    <input type="submit" value="delete"/>
                </form></td></tr>
<?php                       }
                        endforeach;
                        echo "</table>";
?>
<br/><h2>Create Network:</h2>
<h4>Note: this operation may take some time, be patient.</h4>
<form id="create_net" action="index.php" method="POST">
    <input type="hidden" name="execution" value="create_net"/>
        <select id="vlan" name="vlan">
            <option value="">Vlan</option>
<?php 
                        $link_list = get_link_list();
                        foreach ($link_list as $link):
                            $vinfo = explode('|', $link);
                            echo "<option>".$vinfo[0]."</option>";
                        endforeach;
?>
        </select>
    Network: <input type="text" name="network" value="" />
    Netmask: <input type="text" name="netmask" value="" />
    Gateway: <input type="text" name="gateway" value="nogateway" />
    <br />Start IP: <input type="text" name="start_ip" value="" />
    End IP: <input type="text" name="end_ip" value="" />
    <input type="submit" value="create"/>
</form>
<br /><h2>Network Information:</h2>
<?php
                        echo "<table><tr><td width='5%'><h3>Vlan</h3></td><td width='10%'><h3>Network</h3></td><td width='10%'><h3>Netmask</h3></td><td width='10%'><h3>Gateway</h3></td><td width='10%'><h3>Start_IP</h3></td><td width='10%'><h3>End_IP</h3></td><td width='10%'><h3>Action</h3></td></tr>";
                        $net_list = get_net_list();
                        foreach ($net_list as $net):
                            $vinfo = explode('|', $net);
                            echo "<tr><td>$vinfo[5]</td><td>$vinfo[0]</td><td>$vinfo[1]</td><td>$vinfo[2]</td><td>$vinfo[3]</td><td>$vinfo[4]</td><td>"; 
                            foreach ($link_list as $link):
                                $link_info = explode('|', $link);
                                if (($link_info[2] == $_SESSION["username"]) && ($vinfo[5] == $link_info[0])) {
?>
                <form onsubmit="return confirm('Are you sure to delete?')" id="operate_net" action="index.php" method="POST">
                    <input type="hidden" name="vlan" value="<?php echo $vinfo[5]?>"/>
                    <input type="hidden" name="network" value="<?php echo $vinfo[0]?>"/>
                    <input type="hidden" name="execution" value="clear_net"/>
                    <input type="submit" value="delete"/>
                </form>
<?php                               break;
                                }
                            endforeach;
                            echo "</td></tr>";
                        endforeach;
                        echo "</table>";
                        
                        break;
                }
            } else if ($USER->authenticated && isset($_POST["execution"])) {
                switch ($_POST["execution"]) {
                    case "create_vol":
                        if (!isset($_POST["vol_size"]) && !isset($_POST["img_name"])) {
                            echo "Either image name or size must be set.";
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
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vol_list><h3>Back</h3></a>";
                        break;
                    case "delete_vol":
                        if (!isset($_POST["vol_name"])) {
                            echo "<h2>No Volume Name Specified</h2>";
                            break;
                        }
                        $value = delete_vol($_POST["vol_name"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vol_list><h3>Back</h3></a>";
                        break;
                    case "create_snapshot":
                        if (!isset($_POST["vm_ID"])) {
                            echo "<h2>No vm_ID Specified</h2>";
                            break;
                        }
                        $snap_desc = "";
                        if (isset($_POST["snap_desc"])) {
                            $snap_desc = $_POST["snap_desc"];
                        }
                        $value = create_snapshot($_POST["vm_ID"], $snap_desc);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vm_list><h3>Back</h3></a>";
                        break;
                    case "attach_nic":
                        if (!isset($_POST["vm_ID"])) {
                            echo "<h2>No vm_ID Specified</h2>";
                            break;
                        }
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan Specified!";
                            break;
                        }
                        $value = attach_nic($_POST["vm_ID"], $_POST["vlan"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_net_list><h3>Back</h3></a>";
                        break;
                    case "create_link":
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan Specified!";
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
                        $value = create_link($_POST["vlan"], $shared, $link_desc);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_net_list><h3>Back</h3></a>";
                        break;
                    case "create_net":
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan Specified!";
                            break;
                        }
                        $network = "";
                        if (isset($_POST["network"])) {
                            $network = $_POST["network"];
                        }
                        $netmask = "";
                        if (isset($_POST["netmask"])) {
                            $netmask = $_POST["netmask"];
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
                        $value = create_net($_POST["vlan"], $network, $netmask, $gateway, $start_ip, $end_ip);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_net_list><h3>Back</h3></a>";
                        break;
                    case "clear_link":
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan specified!";
                            break;
                        }
                        $value = clear_link($_POST["vlan"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_net_list><h3>Back</h3></a>";
                        break;
                    case "clear_net":
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan specified!";
                            break;
                        }
                        if (!isset($_POST["network"]) || ($_POST["network"] == "")) {
                            echo "No network specified!";
                            break;
                        }
                        $value = clear_net($_POST["vlan"], $_POST["network"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_net_list><h3>Back</h3></a>";
                        break;
                    case "launch_vm":
                        if (!isset($_POST["image"]) || ($_POST["image"] == "")) {
                            echo "No image specified!";
                            break;
                        }
                        if (!isset($_POST["vlan"]) || ($_POST["vlan"] == "")) {
                            echo "No vlan specified!";
                            break;
                        }
                        $hname = "";
                        if (isset($_POST["hname"])) {
                            $hname = $_POST["hname"];
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
                        $value = launch_vm($_POST["image"], $_POST["vlan"], $hname, $ip, $cpu, $memory, $disk_inc);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vm_list><h3>Back</h3></a>";
                        break;
                    case "clear_vm":
                        if (!isset($_POST["vm_ID"])) {
                            echo "<h2>No vm_ID Specified</h2>";
                            break;
                        }
                        $value = clear_vm($_POST["vm_ID"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vm_list><h3>Back</h3></a>";
                        break;
                    case "upload_img":
                        if (!isset($_POST["img_url"])) {
                            echo "<h2>No Image URL Specified</h2>";
                            break;
                        }
                        if (!isset($_POST["platform"])) {
                            echo "<h2>No Image Platform Specified</h2>";
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
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_img_list><h3>Back</h3></a>";
                        break;
                    case "delete_img":
                        if (!isset($_POST["img_name"])) {
                            echo "<h2>No Image name Specified</h2>";
                            break;
                        }
                        $value = delete_img($_POST["img_name"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_img_list><h3>Back</h3></a>";
                        break;
                    case "attach_vol":
                        if (!isset($_POST["vol_name"])) {
                            echo "<h2>No Volume Specified</h2>";
                            break;
                        }
                        if (!isset($_POST["vm_ID"])) {
                            echo "<h2>No vm_ID Specified</h2>";
                            break;
                        }
                        $value = attach_vol($_POST["vm_ID"], $_POST["vol_name"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vol_list><h3>Back</h3></a>";
                        break;
                    case "detach_vol":
                        if (!isset($_POST["vol_name"])) {
                            echo "<h2>No Volume Specified</h2>";
                            break;
                        }
                        $value = detach_vol($_POST["vol_name"]);
                        echo "<h2>$value[0]</h2><a href=index.php?request=get_vol_list><h3>Back</h3></a>";
                        break;
                }
            } else if ($USER->authenticated) { 
?>
                      <h2>CloudLand Navigation</h2>
                      <img src="images/lian.jpg" width="124" height="94" class="img" alt="" />
                        <span>Gates of Dawn</span><br />
The wheels of life keep turning, spinning without control. The wheels of the heart keep yearning, for the sound of the singing soul.<br />

And nights are full with weeping, for sins of the past we've sown. But tomorrow is ours for the keeping, tomorrow the future's shown.<br />

Lift your eyes and see the glory, where the circle of life is drawn. See the never-ending story, come with me to the gates of dawn.<br />

And whose is the hand who rises, the sun from the heaving sea? The power that ever amazes, we look, but never will see.<br />

Who scattered the seeds so life could be, who coloured the fields of corn? Who formed the mould that made me - me, before the world was born?<br />

Lift your eyes and see the glory, where the circle of life is drawn. See the never-ending story, come with me to the gates of dawn.<br />
<?php       } ?>
                  	</div><br />
                    <div style="clear: both"><img src="images/spaser.gif" alt="" width="1" height="1" /></div><br />
              </div>
              	<div class="left_bot"></div>
            </div> 
            <div style="clear: both"><img src="images/spaser.gif" alt="" width="1" height="1" /></div>
     		</div>
    <!-- content ends -->
    <!-- footer begins -->
</div>
</body>
</html>
