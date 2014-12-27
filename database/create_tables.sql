-- global variable
SET @joomla_prefix = 'gzqxc_';
SET @user_table = CONCAT(@joomla_prefix, 'users');
SET @user_table = CONCAT(@joomla_prefix, 'hikashop_address');

-- t_packages
create table t_packages (
	package_id varchar(20) primary key not null,
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
	order_id varchar(20) primary key not null,
	payment_method varchar(3),
	payment_stat varchar(3),
	order_time datetime,
	pay_time datetime,
	payment_amount decimal(17,5),
	media_code varchar(3),
	package_id varchar(20),
	client_id int(11)
) ENGINE=InnoDB;

-- t_id_cards
create table t_id_cards(
	id_card_id int(10) unsigned primary key	not null auto_increment,
	user_id int(11) not null,
	filename_recto text,
	filename_verso text
);

-- t_clients_address
create table t_clients_address(
	fk_client_id int(11),
	fk_address_id int(0) unsigned,
	primary key(fk_client_id, fk_address_id)
);

-- t_shipping_historic
create table t_shipping_historic(
	package_id varchar(20) not null primary key,
	DDJ_DATE datetime,
	RKK_DATE datetime,
	CKK_DATE datetime,
	YSZ_DATE datetime,
	DQG_DATE datetime,
	QGC_DATE datetime,
	GNP_DATE datetime
) ENGINE=InnoDB;