-- global variable
SET @joomla_prefix = 'gzqxc_';
SET @user_table = CONCAT(@joomla_prefix, 'users');
SET @user_table = CONCAT(@joomla_prefix, 'hikashop_address');

-- t_packages
create table t_packages (
	package_id int(10) unsigned primary key not null auto_increment,
	sender_id int(10) unsigned not null,
	recipient_id int(10) unsigned not null,
	package_stat varchar(3),
	insured_amount decimal(17,5),
	cargo_info text,
	weight float,
	height float,
	length float,
	wide float,
	stock_in_time datetime,
	stock_out_time datetime,
	comment text
) ENGINE=InnoDB;

-- t_orders
create table t_orders (
	order_id int(10) unsigned primary key not null auto_increment,
	payment_method varchar(3),
	payment_stat varchar(3),
	order_time datetime,
	pay_time datetime,
	payment_amount decimal(17,5),
	media_code varchar(3),
	package_id int(10) unsigned,
	client_id int(11)
) ENGINE=InnoDB;
alter table t_orders
add constraint fk_package_id foreign key (package_id) references t_packages(package_id);
alter table t_orders
add constraint fk_client_id foreign key (client_id) references gzqxc_users(id);