-- 所有的check constraint都不被支持，所以请写trigger
-- hikashop的table都用myisam引擎，所以不能用foreign key

-- t_packages
alter table t_packages
add foreign key fk_sender_id(sender_id) references gzqxc_hikashop_address(address_id);
alter table t_packages
add constraint fk_recipient_id foreign key (recipient_id) references gzqxc_hikashop_address(address_id);
alter table t_packages
add constraint chk_package_stat check(package_stat in ('DDJ','RKK','CKK','YSZ','DQG','QGC','GNP'));
alter table t_packages
add constraint chk_express_mode check(express_mode in ('LAP','BPO','EMS','RAE'));

-- t_orders
alter table t_orders
add constraint fk_package_id foreign key (package_id) references t_packages(package_id);
alter table t_orders
add constraint fk_client_id foreign key (client_id) references gzqxc_users(id);
alter table t_orders
add constraint chk_payment_method check (payment_method in ('CHQ','CAS','CBX','CBL','ALI','PAP','PRE'));
alter table t_orders
add constraint chk_payment_stat check (payment_stat in ('YFK','WFK','OTH'));
alter table t_orders
add constraint chk_media_code check (media_code in ('ONL','FRT'));
alter table t_orders
add constraint chk_express_type check (express_type in ('LNO','ENO', 'ENA'));

-- t_id_cards
alter table t_id_cards
add constraint fk_user_id foreign key (user_id) references gzqxc_users(id);
alter table t_id_cards
add constraint fk_order_id foreign key (order_id) references t_orders(order_id);

-- t_clients_address

-- t_shipping_historic
alter table t_shipping_historic
add constraint fk_shipping_historic_package_id foreign key (package_id) references t_packages(package_id);

-- t_wallets
alter table t_wallets
add constraint fk_wallets_user_id foreign key(user_id) references gzqxc_users(id);

-- t_balance_modifications
alter table t_balance_modifications
add constraint chk_wallet_type check (wallet_type in ('EMS', 'LAP'));

-- t_operation_historic
alter table t_operation_historic
add constraint fk_operation_historic_user_id foreign key (user_id) references gzqxc_users(id);
alter table t_operation_historic
add constraint chk_operation_type check (operation_type in ('ORD', 'BMO','OUP'));