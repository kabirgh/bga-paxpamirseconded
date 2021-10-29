
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- PaxPamirSecondEd implementation : © Kabir K <kabirk@live.com>
--
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.


ALTER TABLE `player`
  ADD `rupees` int unsigned NOT NULL DEFAULT 0,
  ADD `faction` enum('afghan', 'british', 'russian'),
  ADD `loyalty` int unsigned NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `type` enum('court', 'event'),
  `location` enum('kabul', 'transcaspia', 'punjab', 'kandahar', 'herat'),
  `suit` enum('blue', 'red', 'yellow', 'purple'), -- map to actual names in code
  `rank` int unsigned DEFAULT NULL,
  `patriot` enum('afghan', 'british', 'russian'),
  `prize` enum('afghan', 'british', 'russian'),
  `impact` JSON,
  `card_actions` JSON,
  `event_behavior` JSON,
  `special` varchar(16),
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;
