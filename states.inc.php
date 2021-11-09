<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * PaxPamirSecondEd implementation : © Kabir K <kabirk@live.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * states.inc.php
 *
 * PaxPamirSecondEd game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with 'game' type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by 'st' (ex: 'stMyGameStateName').
   _ possibleactions: array that specify possible player actions on this step. It allows you to use 'checkAction'
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in 'nextState' PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on 'onEnteringState' or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!


// Constants automatically pulled in from constants.inc.php

$machinestates = [
    // The initial state. Please do not modify.
    ST_GAME_SETUP => [
        'name' => 'gameSetup',
        'description' => '',
        'type' => 'manager',
        'action' => 'stGameSetup',
        'transitions' => ['' => ST_CHOOSE_ACTION],
    ],

    ST_CHOOSE_ACTION => [
        'name' => 'chooseAction',
        'description' => clienttranslate('${actplayer} must play a card or pass'),
        'descriptionmyturn' => clienttranslate('${you} must choose an action or pass'),
        'type' => 'activeplayer',
        // TODO args to highlight possible actions
        'possibleactions' => [
            ACT_PURCHASE,
            ACT_PLAY_CARD,
            ACT_PASS,
            ACT_BUILD,
            ACT_TAX,
            ACT_GIFT,
            ACT_MARCH,
            ACT_BETRAY,
            ACT_BATTLE,
            // TODO add card special actions
        ],
        'transitions' => [
            'trPurchase' => ST_PURCHASE,
            'trPlay' => ST_PLAY_CARD,
            'trPass' => ST_END_TURN,
        ],
    ],

    ST_PURCHASE => [
        'name' => 'purchase',
        'description' => clienttranslate('${actplayer} must purchase a card'),
        'descriptionmyturn' => clienttranslate('${you} must purchase a card'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_PLAY_CARD => [
        'name' => 'playCard',
        'description' => clienttranslate('${actplayer} must play a card'),
        'descriptionmyturn' => clienttranslate('${you} must play a card'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_BUILD => [
        'name' => 'build',
        'description' => clienttranslate('${actplayer} must build armies or roads in a region they rule'),
        'descriptionmyturn' => clienttranslate('${you} must build armies or roads in a region you rule'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_TAX => [
        'name' => 'tax',
        'description' => clienttranslate('${actplayer} must take rupees from the market or players with cards in a region they rule'),
        'descriptionmyturn' => clienttranslate('${you} must take rupees from the market or players with cards in a region you rule'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_GIFT => [
        'name' => 'gift',
        'description' => clienttranslate('${actplayer} must buy a gift'),
        'descriptionmyturn' => clienttranslate('${you} must buy a gift'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_MARCH => [
        'name' => 'march',
        'description' => clienttranslate('${actplayer} must move armies or spies'),
        'descriptionmyturn' => clienttranslate('${you} must move armies or spies'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_BETRAY => [
        'name' => 'betray',
        'description' => clienttranslate('${actplayer} must discard a card where they have a spy'),
        'descriptionmyturn' => clienttranslate('${you} must discard a card where you have a spy'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_TAKE_PRIZE => [
        'name' => 'takePrize',
        'description' => clienttranslate('${actplayer} may take a prize'),
        'descriptionmyturn' => clienttranslate('${you} may take a prize'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_BATTLE => [
        'name' => 'battle',
        'description' => clienttranslate('${actplayer} must battle at a region or card'),
        'descriptionmyturn' => clienttranslate('${you} must battle at a region or card'),
        'type' => 'activeplayer',
        'possibleactions' => ['playCard', 'pass'],
        'transitions' => ['playCard' => 2, 'pass' => 2],
    ],

    ST_CONFIRM_TURN => [
        'name' => 'confirmTurn',
        'description' => clienttranslate('${actplayer} must confirm or restart their turn'),
        'descriptionmyturn' => clienttranslate('${you} must confirm or restart your turn'),
        'type' => 'activeplayer',
        'args' => 'argsConfirmTurn',
        'action' => 'stConfirmTurn',
        'possibleactions' => ['actConfirmTurn', 'actRestart'],
    ],

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    ST_END_GAME => [
        'name' => 'gameEnd',
        'description' => clienttranslate('End of game'),
        'type' => 'manager',
        'action' => 'stGameEnd',
        'args' => 'argGameEnd'
    ],
];
