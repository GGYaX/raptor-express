-- global variable
SET @joomla_prefix = 'gzqxc_';
SET @user_table = CONCAT(@joomla_prefix, 'users');
SET @user_table = CONCAT(@joomla_prefix, 'hikashop_address');

-- t_packages
create table t_packages (
	package_id int(10) unsigned primary key not null,
	sender_id int(10) unsigned foreign key references gzqxc_hikashop_address(address_id),
	recipient_id int(10) unsigned foreign key references gzqxc_hikashop_address(address_id),
	package_stat varchar(3),
	insured_amount decimal(17,5),
	cargo_info varchar(256),
	weight float,
	height float,
	length float,
	wide float,
	stock_in_time datetime,
	stock_out_time datetime,
	comment varchar(256)
);
alter table t_packages
add constraint chk_package_stat check(package_stat in ('DDJ','RKK','CKK','YSZ','DQG','QGC','GNP'));

-- t_orders
create table t_orders (
	order_id int(10) unsigned primary key not null,
	payment_method varchar(3),
	payment_stat varchar(3),
	order_time datetime,
	pay_time datetime,
	payment_amount decimal(17,5),
	media_code varchar(3),
	package_id int(10) unsigned foreign key references t_packages(package_id),
	client_id int(11) foreign key references gzqxc_users(id)
);

alter table t_orders
add constraint chk_payment_method check (payment_method in ('CHQ','CAS','CBX','CBL','ALI','PAP','PRE'));
alter table t_orders
add constraint chk_payment_stat check (payment_stat in ('YFK','WFK','OTH'));
alter table t_orders
add constraint chk_media_code check (media_code in ('ONL','FRT'));