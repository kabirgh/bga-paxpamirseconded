/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * PaxPamirSecondEd implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * paxpamirseconded.js
 *
 * PaxPamirSecondEd user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
  "dojo", "dojo/_base/declare",
  "ebg/core/gamegui",
  "ebg/counter",
  g_gamethemeurl + "modules/js/paxpamir-ui.js"
],
  function (dojo, declare) {
    return declare("bgagame.paxpamirseconded", [ebg.core.gamegui, paxpamir.ui], {
      constructor: function () {
        console.log('paxpamirseconded constructor');

        this.summarySectionSelector = 'paxpamir-summary';
        this.mapSectionSelector = 'paxpamir-map';
        this.marketSectionSelector = 'paxpamir-market';
        this.playerBoardsSectionSelector = 'paxpamir-playerBoards';
      },

      /*
          setup:

          This method must set up the game user interface according to current game situation specified
          in parameters.

          The method is called each time the game interface is displayed to a player, ie:
          _ when the game starts
          _ when a player refreshes the game page (F5)

          "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
      */

      setup: function (gamedatas) {
        console.log("Starting game setup");
        this.inherited(arguments);

        // LAYOUT ACTION BUTTONS ZONE --- START
        const viewSummaryBtn = dojo.byId("view-summary");
        const viewMapBtn = dojo.byId("view-map");
        const viewMarketBtn = dojo.byId("view-market");
        const viewPlayerBoardsBtn = dojo.byId("view-playerBoards");

        viewSummaryBtn.innerHTML = _('View Summary');
        viewMapBtn.innerHTML = _('View Map');
        viewMarketBtn.innerHTML = _('View Market');
        viewPlayerBoardsBtn.innerHTML = _('View Player Boards');

        dojo.addClass(this.summarySectionSelector, 'section-visible');
        dojo.addClass(this.mapSectionSelector, 'section-visible');
        dojo.addClass(this.marketSectionSelector, 'section-visible');
        dojo.addClass(this.playerBoardsSectionSelector, 'section-visible');

        dojo.connect(viewSummaryBtn, "click", this, 'changeLayoutViewMode');
        dojo.connect(viewMapBtn, "click", this, 'changeLayoutViewMode');
        dojo.connect(viewMarketBtn, "click", this, 'changeLayoutViewMode');
        dojo.connect(viewPlayerBoardsBtn, "click", this, 'changeLayoutViewMode');
        // LAYOUT ACTION BUTTONS ZONE --- END

        console.log(gamedatas) // TODO: remove
        dojo.place(this.playerBoardsHtml(gamedatas.players), document.getElementById('paxpamir-playerBoards'));

        // TODO: Set up your game interface here, according to "gamedatas"
        this.setupMarket(gamedatas.market);

        // Setup game notifications to handle (see "setupNotifications" method below)
        this.setupNotifications();

        console.log("Ending game setup");
      },


      playerBoardsHtml: function (players) {
        return Object.entries(players).map(([id, playerData]) => {
          return `<div class="playerBoard playerBoard-${playerData.color}"></div>`
        }).join('')
      },

      setupMarket: function (market) {
        const cards = market.map((data, index) => {
          return `<div class="card" card-id="${data.cardId}"></div>`;
        }).join('');

        dojo.place(cards, document.getElementById('paxpamir-market'));
      },

      ///////////////////////////////////////////////////
      //// Game & client states

      // onEnteringState: this method is called each time we are entering into a new game state.
      //                  You can use this method to perform some user interface changes at this moment.
      //
      onEnteringState: function (stateName, args) {
        console.log('Entering state: ' + stateName);

        switch (stateName) {

          /* Example:

          case 'myGameState':

              // Show some HTML block at this game state
              dojo.style( 'my_html_block_id', 'display', 'block' );

              break;
         */


          case 'dummmy':
            break;
        }
      },

      // onLeavingState: this method is called each time we are leaving a game state.
      //                 You can use this method to perform some user interface changes at this moment.
      //
      onLeavingState: function (stateName) {
        console.log('Leaving state: ' + stateName);

        switch (stateName) {

          /* Example:

          case 'myGameState':

              // Hide the HTML block we are displaying only during this game state
              dojo.style( 'my_html_block_id', 'display', 'none' );

              break;
         */


          case 'dummmy':
            break;
        }
      },

      // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
      //                        action status bar (ie: the HTML links in the status bar).
      //
      onUpdateActionButtons: function (stateName, args) {
        console.log('onUpdateActionButtons: ' + stateName);

        if (this.isCurrentPlayerActive()) {
          switch (stateName) {
            /*
                             Example:

                             case 'myGameState':

                                // Add 3 action buttons in the action status bar:

                                this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' );
                                this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' );
                                this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' );
                                break;
            */
          }
        }
      },

      ///////////////////////////////////////////////////
      //// Utility methods

      /*

          Here, you can defines some utility methods that you can use everywhere in your javascript
          script.

      */




      ///////////////////////////////////////////////////
      //// Player's action

      /*

          Here, you are defining methods to handle player's action (ex: results of mouse click on
          game objects).

          Most of the time, these methods:
          _ check the action is possible at this game state.
          _ make a call to the game server

      */

      /* Example:

      onMyMethodToCall1: function( evt )
      {
          console.log( 'onMyMethodToCall1' );

          // Preventing default browser reaction
          dojo.stopEvent( evt );

          // Check that this action is possible (see "possibleactions" in states.inc.php)
          if( ! this.checkAction( 'myAction' ) )
          {   return; }

          this.ajaxcall( "/paxpamirseconded/paxpamirseconded/myAction.html", {
                                                                  lock: true,
                                                                  myArgument1: arg1,
                                                                  myArgument2: arg2,
                                                                  ...
                                                               },
                       this, function( result ) {

                          // What to do after the server call if it succeeded
                          // (most of the time: nothing)

                       }, function( is_error) {

                          // What to do after the server call in anyway (success or failure)
                          // (most of the time: nothing)

                       } );
      },

      */


      ///////////////////////////////////////////////////
      //// Reaction to cometD notifications

      /*
          setupNotifications:

          In this method, you associate each of your game notifications with your local method to handle it.

          Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                your paxpamirseconded.game.php file.

      */
      setupNotifications: function () {
        console.log('notifications subscriptions setup');

        // TODO: here, associate your game notifications with local methods

        // Example 1: standard notification handling
        // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );

        // Example 2: standard notification handling + tell the user interface to wait
        //            during 3 seconds after calling the method in order to let the players
        //            see what is happening in the game.
        // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
        // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
        //
      },

      // TODO: from this point and below, you can write your game notifications handling methods

      /*
      Example:

      notif_cardPlayed: function( notif )
      {
          console.log( 'notif_cardPlayed' );
          console.log( notif );

          // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

          // TODO: play the card in the user interface.
      },

      */
    });
  });
