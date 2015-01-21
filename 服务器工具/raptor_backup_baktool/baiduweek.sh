#!/bin/bash                                                                                                            
                                                                                                                       
# Definition
MYSQL_USER="root"
MYSQL_PASS="\$Jacket"
baidupan_DIR="/bpcs_backup/$(date +%Y-%m-%d)"
BACK_DIR="/raptor_backup_baktool/temp"
# Backup web server and contents
APACHE_DATA="/etc/apache2"
BACKUP_DEFAULT="/var/www"
# Customoze backup file name
mysql_DATA=mysql_$(date +"%Y%m%d").tar.gz
www_DEFAULT=www_$(date +%Y%m%d).tar.gz
apache_CONFIG=apache_$(date +%Y%m%d).tar.gz
# Create local backup folder
if [ ! -d $BACK_DIR ] ;
  then
   mkdir -p "$BACK_DIR"
fi

# Enter backup folder
cd $BACK_DIR

# Backup all databases
# And clean unnecessary bases
mysql -u$MYSQL_USER -p$MYSQL_PASS -B -N -e 'SHOW DATABASES' > databases.db
sed -i '/performance_schema/d' databases.db
sed -i '/information_schema/d' databases.db
sed -i '/mysql/d' databases.db

for db in $(cat databases.db)
 do
   mysqldump -u$MYSQL_USER -p$MYSQL_PASS ${db} | gzip -9 - > ${db}.sql.gz
done

# Pack databases
tar -zcvpf $mysql_DATA *.sql.gz | split -d -b 512m 

# Pack site information
tar -zcvpf $www_DEFAULT $BACKUP_DEFAULT | split -d -b 512m 

# Pack Apache configuration
tar -zcvpf $apache_CONFIG $APACHE_DATA/*.conf | split -d -b 64m 

# upload
#cd ~
/raptor_backup_baktool/uploader/bpcs_uploader.php upload $BACK_DIR/$apache_CONFIG $baidupan_DIR/$apache_CONFIG
/raptor_backup_baktool/uploader/bpcs_uploader.php upload $BACK_DIR/$mysql_DATA $baidupan_DIR/$mysql_DATA
/raptor_backup_baktool/uploader/bpcs_uploader.php upload $BACK_DIR/$www_DEFAULT $baidupan_DIR/$www_DEFAULT

# Delete all local backup
rm -rf $BACK_DIR


exit 0
