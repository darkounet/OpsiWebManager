#!/bin/bash
install=false
uninstall=false
update=false
once=false
always=false
custom=false
logon=false

while [ -n "$1" ]; do

case $1 in 

	--pkg)
		echo "Type : $2"
		pkg_type=$2
	;;
	--pid)
		echo "ProductId : $2"
		pkg_pid=$2
	;;
	--pname)
		echo "ProductName : $2"
		pkg_pname=$2
	;;
	--desc)
		echo "Description : $2"
		pkg_desc=$2
	;;
	--advice)
		echo "Advice : $2"
		pkg_advice=$2
	;;
	--prodversion)
		echo "ProductVersion : $2"
		pkg_prodversion=$2
	;;
	--packversion)
		echo "PackageVersion : $2"
		pkg_packversion=$2
	;;
	--licence)
		echo "Licence : $2"
		pkg_licence=$2
	;;
	--priority)
		echo "Priority: $2"
		pkg_priority=$2
	;;
	--install)
	install=true
	;;
	--uninstall)
	uninstall=true
	;;
	--update)
	update=true
	;;
	--once)
	once=true
	;;
	--always)
	always=true
	;;
	--custom)
	custom=true
	;;
	--logon)
	logon=true
	;;
esac
shift 
done
echo $pkg_type $pkg_pid $pkg_pname $pkg_desc $pkg_advice $pkg_prodversion $pkg_packversion $pkg_licence $pkg_priority

mkdir "/home/opsiproducts/sandbox/$pkg_pid"
mkdir "/home/opsiproducts/sandbox/$pkg_pid/OPSI/"
mkdir "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/"
mkdir "/home/opsiproducts/sandbox/$pkg_pid/SERVER_DATA/"
touch "/home/opsiproducts/sandbox/$pkg_pid/OPSI/control"

if [ $install == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/install.ins"
fi
if [ $uninstall == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/uninstall.ins"
fi
if [ $update == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/update.ins"
fi
if [ $once == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/once.ins"
fi
if [ $always == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/always.ins"
fi
if [ $custom == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/custom.ins"
fi
if [ $logon == "true" ]; then
touch "/home/opsiproducts/sandbox/$pkg_pid/CLIENT_DATA/logon.ins"
fi

cat <<EOF > "/home/opsiproducts/sandbox/$pkg_pid/OPSI/control"
[Package]
version: $pkg_packversion
depends: 
incremental: False

[Product]
type: $pkg_type
id: $pkg_pid
name: $pkg_pname
description: $pkg_desc
advice: $pkg_advice
version: $pkg_prodversion
priority: 0
licenseRequired: $pkg_licence
productClasses: 
setupScript: 
uninstallScript: 
updateScript: 
alwaysScript: 
onceScript: 
customScript: 
userLoginScript:

EOF

