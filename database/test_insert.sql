-- EMS 单号：EY840347807FR
insert into t_packages (
	package_id,
	sender_id,
	recipient_id,
	package_stat,
	insured_amount,
	express_id,
	express_mode,
	cargo_info,
	weight,
	height,
	length,
	wide,
	stock_in_time,
	stock_out_time,
	comment	
) values (
	1111,
	889,
	889,
	'GNP',
	'12.3',
	'EY840347807FR',
	'EMS',
	'cargo_info',
	8,
	8,
	8,
	8,
	'2014-01-01 01:01:01',
	'2014-01-01 01:02:01',
	'commentaire'
);

insert into t_orders (
	order_id,
	payment_method,
	payment_stat,
	order_time,
	pay_time,
	payment_amount,
	media_code,
	package_id,
	client_id
) values (
	1111,
	'CHQ',
	'YFK',
	'2013-12-12 12:12:12',
	'2013-12-13 12:12:12',
	12.2,
	'ONL',
	1111,
	'287' -- cadmin
);

insert into t_shipping_historic values (
	1111,
	'2013-12-12 12:12:12',
	null,null,null,null,null,null
);

insert into t_orders (
	order_id,
	payment_method,
	payment_stat,
	order_time,
	pay_time,
	payment_amount,
	media_code,
	package_id,
	express_type,
	client_id
) values (
	11111,
	'CHQ',
	'YFK',
	'2013-12-12 12:12:12',
	'2013-12-13 12:12:12',
	12.2,
	'ONL',
	1111,
	'LNO',
	'287' -- cadmin
);

insert into t_wallets (
	user_id,
	laposte_id,
	ems_id
) values (
	287,
	'L10287',
	'E10287'
);