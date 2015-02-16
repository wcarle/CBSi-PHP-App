$(function(){
    //Directory of templates
    var Templates = {
        Game: _.template($("#game-template").html()),
        GameList: _.template($("#games-list-template").html())
    };
    //Game Model
    //Handles CRUD and validation for Games
    var Game = Backbone.Model.extend({
        defaults: {
            name: '',
            platform: '',
            description: ''
        },
        initialize: function(){
            this.on("invalid",function(model,error){
                alert(error);
            });
        },
        validate: function (attr) {
            if (!attr.name) {
                return "Please enter a name.";
            }
        },
        urlRoot: '/backbone/api/'
      });
    //Game View
    //Handles display and user actions for Game models
    var GameView = Backbone.View.extend({
        model: null,
        events: {
            'click button.edit': 'editGame',
            'click button.cancel': 'cancelEditGame',
            'click button.delete': 'deleteGame',
            'click button.save': 'saveGame',
            'mouseover': 'hover',
            'mouseout': 'unhover'

        },
        initialize: function(model){
            _.bindAll(this, 'render', 'editGame');
            this.model = model;
            this.render();
        },
        render: function(){
            if($(".game-row [data-game-id=" + this.model.get('id') + "]").length === 0){
                this.$el = $(Templates.Game({game: this.model}));
                this.setElement(this.$el);
                $(".game-row").append(this.$el);
            }
            else{
                var $newEl = $(Templates.Game({game: this.model}));
                this.$el.replaceWith($newEl);
                this.setElement($newEl);
            }
           
        },
        editGame: function(){
            $(".edit-field").hide();
            $(".view-field").show();
            $(".edit-field", this.$el).show();
            $(".view-field", this.$el).hide();
        },
        cancelEditGame: function(){
            $(".edit-field").hide();
            $(".view-field").show();
            $(".edit-field", this.$el).hide();
            $(".view-field", this.$el).show();
        },
        saveGame: function(){
            var self = this;
            this.model.set({
                name: $(".txt-name", this.$el).val(),
                description: $(".txt-description", this.$el).val(),
                platform: $(".txt-platform", this.$el).val()
            });
            this.model.save({}, {
                success: function (model, respose, options) {
                    self.render();
                },
                error: function (model, xhr, options) {
                    console.log("Error saving Game");
                }
            });
        },
        deleteGame: function(){
            var self = this;
            var doDelete = confirm("Are you sure you want to delete this game?");
            if(doDelete){
                this.model.destroy({
                    success: function (model, respose, options) {
                        self.$el.remove();
                    },
                    error: function (model, xhr, options) {
                        console.log("Error deleting Game");
                    }
                });
            }
        },
        hover: function(){
            $(".controls", this.$el).css("visibility", "visible");
        },
        unhover: function(){
            $(".controls", this.$el).css("visibility", "hidden");
        }
    });
    //GamesList Collection
    //Collection of Game Models, handles fetching and adding of games
    var GamesList = Backbone.Collection.extend({
        model: Game,
        url: '/backbone/api/all',
        comparator: function(collection){
            return collection.get('name').toLowerCase();
        }
    });
    //Games View
    //View for GamesList, handles display and user actions for collection of games
    var GamesView = Backbone.View.extend({
        el: $('.games'),
        events: {
            'click button#addGame': 'addItem'
        },

        initialize: function(){
            var self = this;
            _.bindAll(this, 'render', 'addItem');
            this.collection = new GamesList();

            this.counter = 0;
            this.collection.fetch({
                success: function(model, response){
                    self.render();
                },
                error: function(){
                    console.log("Error fetching Games");
                }
            });
        },
        render: function(){
            var self = this;
            $(this.el).html(Templates.GameList);
            _(this.collection.models).each(function(game){
                new GameView(game);
            }, this);
        },
        addItem: function(){
            var self = this;
            this.counter++;
            var game = new Game();

            game.set({
                name: $("#txt-name").val(),
                platform: $("#txt-platform").val(),
                description: $("#txt-description").val()
            });
            game.save({}, {
                success: function (model, respose, options) {
                    self.collection.add(model);
                    new GameView(model);
                    self.render();
                },
                error: function (model, xhr, options) {
                    console.log("Error adding Game");
                }
            });
        }
    });
    var gamesView = new GamesView();
});