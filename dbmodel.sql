
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

-- IMPT: no inline comments allowed!

ALTER TABLE `player`
  ADD `court_cards` JSON DEFAULT NULL,
  ADD `event_cards` JSON DEFAULT NULL,
  ADD `cylinders` int unsigned NOT NULL,
  ADD `rupees` int unsigned NOT NULL DEFAULT 0,
  ADD `faction` enum('Afghan', 'British', 'Russian'),
  ADD `loyalty` int unsigned NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `card` (
  `id` int unsigned NOT NULL,
  `deck_pos` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;
