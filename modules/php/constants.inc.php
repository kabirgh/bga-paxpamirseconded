<?php

/* --- STATES --- */
// BGA framework states
const ST_GAME_SETUP = 1;
const ST_END_GAME = 99;
// Helper actions
const ST_CHOOSE_ACTION = 10;
const ST_CONFIRM_TURN = 11;
const ST_END_TURN = 12;
// Core actions
const ST_PURCHASE = 20;
const ST_PLAY_CARD = 21;
// Special core actions
const ST_PURCHASE_EVENT = 22;
// Card-based actions
const ST_BUILD = 30;
const ST_TAX = 31;
const ST_GIFT = 32;
const ST_MARCH = 33;
const ST_BETRAY = 34;
const ST_TAKE_PRIZE = 35; // automatic action after betray
const ST_BATTLE = 36;
// Misc states
const ST_DISCARD = 40;
const ST_DISCARD_EVENT = 41;
const ST_DOMINANCE_CHECK = 42; // from ST_DISCARD or ST_PURCHASE

/* --- ACTIONS --- */
// Core actions
const ACT_PURCHASE = 'actPurchase';
const ACT_PLAY_CARD = 'actPlayCard';
const ACT_PASS = 'actPass';
// Card-based actions
const ACT_BUILD = 'actBuild';
const ACT_TAX = 'actTax';
const ACT_GIFT = 'actGift';
const ACT_MARCH = 'actMarch';
const ACT_BETRAY = 'actBetray';
const ACT_BATTLE = 'actBattle';
