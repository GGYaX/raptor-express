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

-- trigger 当生成一个order时候，需要放进t_operation_historic中
DELIMITER //
DROP TRIGGER IF EXISTS exp_insert_trigger//
CREATE TRIGGER exp_insert_trigger
    AFTER INSERT ON `t_orders`
    FOR EACH ROW
BEGIN
	INSERT INTO t_operation_historic(user_id, operation_date, operation_type, reference_id) values(NEW.client_id, NOW(), 'ORD', NEW.order_id);
END//
DELIMITER ;

-- trigger 每次更新订单，需重新更新历史记录
DELIMITER //
DROP TRIGGER IF EXISTS exp_update_trigger//
CREATE TRIGGER exp_update_trigger
    AFTER UPDATE ON `t_orders`
    FOR EACH ROW
BEGIN
	INSERT INTO t_operation_historic(user_id, operation_date, operation_type, reference_id) values(NEW.client_id, NOW(), 'OUP', NEW.order_id);
	set @package_stat := (select package_stat from t_packages where package_id = NEW.package_id);
	if @package_stat = 'DDJ' then
		UPDATE t_shipping_historic set DDJ_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'RKK' then
		UPDATE t_shipping_historic set RKK_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'CKK' then
		UPDATE t_shipping_historic set CKK_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'YSZ' then
		UPDATE t_shipping_historic set YSZ_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'DQG' then
		UPDATE t_shipping_historic set DQG_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'QGC' then
		UPDATE t_shipping_historic set QGC_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
	if @package_stat = 'GNP' then
		UPDATE t_shipping_historic set GNP_DATE = NOW() WHERE package_id = NEW.package_id;
	end if;
END//
DELIMITER ;