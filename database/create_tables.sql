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
	express_id varchar(20),
	express_mode varchar(3),
	cargo_info text,
	weight float,
	height float,
	length float,
	wide float,
	stock_in_time datetime,
	stock_out_time datetime,
	comment text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- t_orders
create table t_orders (
	order_id int(10) unsigned primary key not null auto_increment,
	payment_method varchar(3),
	payment_stat varchar(3),
	order_time datetime,
	pay_time datetime,
	payment_amount decimal(17,5),
	express_type varchar(3) not null,
	media_code varchar(3),
	package_id int(10) unsigned,
	client_id int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- t_id_cards
create table t_id_cards(
	id_card_id int(10) unsigned primary key	not null auto_increment,
	user_id int(11) not null,
	order_id int(10) unsigned not null,
	filename_recto text,
	filename_verso text
) DEFAULT CHARSET=utf8;

-- t_clients_address
create table t_clients_address(
	fk_client_id int(11),
	fk_address_id int(0) unsigned,
	primary key(fk_client_id, fk_address_id)
) DEFAULT CHARSET=utf8;

-- t_shipping_historic
create table t_shipping_historic(
	package_id int(10) unsigned not null primary key,
	DDJ_DATE datetime,
	RKK_DATE datetime,
	CKK_DATE datetime,
	YSZ_DATE datetime,
	DQG_DATE datetime,
	QGC_DATE datetime,
	GNP_DATE datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 20150104
-- t_wallets
create table t_wallets(
	user_id int(11) primary key,
	laposte_id varchar(12) unique, -- 'L' + (10000 + user_id)
	ems_id varchar(12) unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- t_balance_modifications
create table t_balance_modifications(
	id int(10) unsigned primary key not null auto_increment,
	amount decimal(17,5) not null,
	wallet_id varchar(12) not null,
	wallet_type varchar(3), -- in EMS/LAP
	date datetime,
	comment text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- t_operation_historic
create table t_operation_historic(
	id int(12) unsigned primary key not null auto_increment,
	user_id int(11) not null, -- references to joomla.user.id and index
	operation_date datetime not null,
	operation_type varchar(3) not null, -- in ORD/BMO
	reference_id int(10) unsigned not null -- reference to t_balance_modifications or t_orders
) ENGINE=InnoDB DEFAULT CHARSET=utf8;