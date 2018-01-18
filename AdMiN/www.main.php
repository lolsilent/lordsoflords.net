<?php 
#!/usr/local/bin/php
print 'under maintenance';
exit;
$root_path='/home/lolnet/public_html';
$root_url='https://lordsoflords.net';
$root_url='.';

require_once('AdMiN/www.standard.php');

$server='Net';
$title='Lords of Lords';

$version=5.00;

$html_header='AdMiN/templates/templates.header.php';
$html_footer='AdMiN/templates/templates.footer.php';
$html_style='AdMiN/templates/game.style.php';
$game_header='AdMiN/templates/game.header.php';
$game_footer='AdMiN/templates/game.footer.php';
$clean_header='AdMiN/templates/clean.header.php';
$clean_footer='AdMiN/templates/clean.footer.php';

$inc_functions = 'AdMiN/www.functions.php';
$inc_battle = 'AdMiN/www.battle.php';
$inc_tourney = 'AdMiN/www.tourney.php';

$inc_races= 'AdMiN/arrays/array.races.php';
$inc_monsters= 'AdMiN/arrays/array.monsters.php';
$inc_locations= 'AdMiN/arrays/array.locations.php';
$inc_titles= 'AdMiN/arrays/array.titles.php';

$inc_emotions= 'AdMiN/arrays/array.emotions.php';

$inc_mysql = 'AdMiN/www.mysql.php';

$gamefiles=array('main','paper','duels','graves','steals','matches','','fight','world','challenge','schedule','tourney','','shop','stats','inventory','charms','market','','clan','transfer','messages','stash','steal','','town','nobility','support','politics','save','logout','','ladder','clans','pets');

$items=array('weapon','spell','heal','helm','shield','amulet','ring','armor','belt','pants','hand','feet');
$kinds=array('Normal Duel','Melee Duel','Mystic Duel','Strength Duel','Intelligent Duel');

$operators=array('Admin','Cop','Mod','Support');
$opinactive=array(20,15,10,5);

$game_modes_desc = array(
'dictatorship' => 'The world Admin that created the world will be the only Super Admin.',
'domination' => 'The Admin title can be bought from the support page!.',
'jungle' => 'No controllers or admins do whatever you like.',
);

$game_modes = array_keys($game_modes_desc);

//2-26-2008 01:58:21 DEVLAB ONLY
$item_sub = array('strength', 'dexterity', 'agility', 'intelligence', 'concentration', 'contravention',
'weapon damage', 'attack rating', 'defense', 'attack spell', 'magic rating', 'magic shield', 'heal spell'
);
$item_kinds = array('potion','rune','scroll','element');


$killing_spree_max=3;
$max_player_per_ip=1;
$fp_bonus_max=250;
$chat_timer=9;

$logstart='';
?>