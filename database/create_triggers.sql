-- trigger 用来自动生成wallet_id
create trigger trigger_insert_after_gzqxc_users
	after insert on gzqxc_users for each row
	insert into t_wallets values (new.id, concat('L', 10000 + new.id), concat('L', 10000 + new.id));