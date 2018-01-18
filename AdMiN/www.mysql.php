<?php 
#!/usr/local/bin/php
$db_host		='localhost';
$db_user		='lolnet';
$db_password	='12345678*&^%$#@!qwertyYTREWQ';
$db_main='lolnet';

$tbl_aservers='lol_aservers';
$tbl_board='lol_board';
$tbl_charms='lol_charms';
$tbl_clans='lol_clans';
$tbl_councils='lol_councils';
$tbl_credits='lol_credits';
$tbl_duel='lol_duel';
$tbl_history='lol_history';
$tbl_index='lol_index';
$tbl_items='lol_items';
$tbl_market='lol_market';
$tbl_marketxp='lol_marketxp';
$tbl_members='lol_members';
$tbl_messages='lol_messages';
$tbl_papers='lol_papers';
$tbl_paypal='lol_paypal';
$tbl_pets='lol_pets';
$tbl_races='lol_races';
$tbl_runes='lol_runes';
$tbl_save='lol_save';
$tbl_steals='lol_steals';
$tbl_tourney='lol_tourney';
$tbl_tourprice='lol_tourprice';
$tbl_zlogs='lol_zlogs';

$fld_aservers='`id`,`admin_name`,`admin_email`,`world_name`,`world_title`,`world_description`,`max_killing_spree`,`max_player_per_ip`,`reset_days`,`menu_type`,`rules`,`updated`,`timer`';
$fld_board='`id`,`server_id`,`star`,`clan`,`sex`,`charname`,`race`,`level`,`message`,`ip`,`timer`';
$fld_charms='`id`,`charname`,`finder`,`name`,`str`,`dex`,`agi`,`intel`,`conc`,`cont`,`timer`';
$fld_councils='`id`,`sex`,`apply`,`charname`,`admin`,`cop`,`mod`,`support`,`ip`,`timer`';
$fld_clans='`id`,`sex`,`charname`,`password`,`clan`,`name`,`won`,`lost`,`tied`,`points`,`tourney`,`timer`';
$fld_credits='`id`,`username`,`charname`,`credits`';
$fld_duel='`id`,`challenger`,`opponent`,`kind`,`timer`';
$fld_history='`id`,`charname`,`kills`,`deads`,`duelsw`,`duelsl`,`timer`';
$fld_index='`id`,`date`,`fights`,`timer`';
$fld_items='`id`,`mid`,`kind`,`sub`,`value`,`timer`';
$fld_market='`id`,`cid`,`charname`,`gold`,`credits`,`timer`';
$fld_marketxp='`id`,`xp`,`charname`,`credits`,`bids`,`ends`,`timer`';
$fld_members='`id`,`sid`,`server_id`,`username`,`password`,`email`,`clan`,`sex`,`charname`,`race`,`level`,`xp`,`gold`,`stash`,`life`,`str`,`dex`,`agi`,`intel`,`conc`,`cont`,`weapon`,`spell`,`heal`,`helm`,`shield`,`amulet`,`ring`,`armor`,`belt`,`pants`,`hand`,`feet`,`jail`,`stealth`,`twin`,`fp`,`mute`,`vote`,`timer`,`fail`,`friend`,`onoff`,`rounds`,`ip`';
$fld_messages='`id`,`charname`,`receiver`,`message`,`timer`';
$fld_papers='`id`,`server_id`, `pid`,`news`,`timer`';
$fld_paypal='`id`,`server`,`amount`,`day`,`month`,`year`,`ip`';
$fld_save='`id`,`charname`,`level`,`xp`,`gold`,`stash`,`life`,`str`,`dex`,`agi`,`intel`,`conc`,`cont`,`weapon`,`spell`,`heal`,`helm`,`shield`,`amulet`,`ring`,`armor`,`belt`,`pants`,`hand`,`feet`,`timer`';
$fld_steals='`id`,`server_id`, `sex`,`charname`,`item`,`amount`,`timer`';
$fld_tourney='`id`,`clana`,`clanb`,`timer`';
$fld_tourprice='`id`,`clan`,`xp`,`gold`,`timer`';
$fld_zlogs='`id`,`charname`,`logs`,`file`,`ip`,`timer`';

$tbl_contents='forum_contents';
$tbl_topics='forum_topics';

$fld_contents='`id`,`server_id`,`tid`,`mid`,`date`,`body`,`timer`,`see`,`deleted`,`ip`';
$fld_topics='`id`,`server_id`,`fid`,`mid`,`sticky`,`name`,`body`,`replies`,`views`,`last`,`first`,`timer`,`see`,`deleted`,`ip`';

$table_names=array(
$tbl_board,
$tbl_charms,
$tbl_clans,
$tbl_councils,
$tbl_credits,
$tbl_duel,
$tbl_history,
$tbl_index,
$tbl_market,
$tbl_marketxp,
$tbl_members,
$tbl_messages,
$tbl_papers,
$tbl_paypal,
$tbl_save,
$tbl_steals,
$tbl_tourney,
$tbl_tourprice,
$tbl_zlogs,
$tbl_contents,
$tbl_topics,
);

?>
