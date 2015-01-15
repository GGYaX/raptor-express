-- trigger 用来自动生成wallet_id
create trigger trigger_insert_after_gzqxc_users
	after insert on gzqxc_users for each row
	insert into t_wallets values (new.id, concat('FR', 10000 + new.id), concat('CN', 10000 + new.id));

-- trigger 当balance被修改的时候，插入到operation historic中
delimiter //
create trigger trigger_insert_after_t_balance_modifications
	after insert on t_balance_modifications for each row
	begin
		declare m_user_id int(11);
		set @m_user_id := (select user_id from t_wallets where laposte_id = new.wallet_id or ems_id = new.wallet_id);
		insert into t_operation_historic (`user_id`, `operation_date`, `operation_type`, `reference_id`) values (@m_user_id, new.date, 'BMO', new.id);
end//

-- 恢复delimiter
delimiter ;

-- trigger 当有package时候，自动插入到t_shipping_historic中
create trigger trigger_insert_after_t_packages
	after insert on t_packages for each row
	insert into t_shipping_historic(package_id, DDJ_DATE) values (new.package_id, now());