-- EMS 单号：EY840347807FR
insert into t_packages values (
	'EY840347807FR',
	889,
	889,
	'GNP',
	'12.3',
	'cargo_info',
	8,
	8,
	8,
	8,
	'2014-01-01 01:01:01',
	'2014-01-01 01:02:01',
	'commentaire'
);

insert into t_orders values (
	'ORDER_EY840347807FR',
	'CHQ',
	'YFK',
	'2013-12-12 12:12:12',
	'2013-12-13 12:12:12',
	12.2,
	'ONL',
	'EY840347807FR',
	'287' -- cadmin
);

insert into t_shipping_historic values (
	'EY840347807FR',
	'2013-12-12 12:12:12',
	null,null,null,null,null,null
);