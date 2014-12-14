-- 所有的check constraint都不被支持，所以请写trigger
-- hikashop的table都用myisam引擎，所以不能用foreign key

-- t_packages
alter table t_packages
add foreign key fk_sender_id(sender_id) references gzqxc_hikashop_address(address_id);
alter table t_packages
add constraint fk_recipient_id foreign key (recipient_id) references gzqxc_hikashop_address(address_id);
alter table t_packages
add constraint chk_package_stat check(package_stat in ('DDJ','RKK','CKK','YSZ','DQG','QGC','GNP'));

-- t_orders
alter table t_orders
add constraint chk_payment_method check (payment_method in ('CHQ','CAS','CBX','CBL','ALI','PAP','PRE'));
alter table t_orders
add constraint chk_payment_stat check (payment_stat in ('YFK','WFK','OTH'));
alter table t_orders
add constraint chk_media_code check (media_code in ('ONL','FRT'));