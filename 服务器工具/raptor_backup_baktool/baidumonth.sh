#!/bin/bash

# Definition
fName="backup-global-$(date +%Y-%m-%d).tgz"
baidupan_DIR="/bpcs_backup/$(date +%Y-%m-%d)_global"

# Pack the whole system
tar cvpzf $fName --exclude=/proc --exclude=/lost+found --exclude=/$fName --exclude=/raptor_backup --exclude=/mnt --exclude=/sys / | split -d -b 1024m

# upload
cd /raptor_backup_baktool/
/raptor_backup_baktool/uploader/bpcs_uploader.php upload $fName $baidupan_DIR/

# Delete all local backup
rm -f $fName

exit 0
