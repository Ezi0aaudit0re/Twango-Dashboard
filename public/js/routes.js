
var myModule = angular.module('myApp', ['ui.bootstrap', 'ui.router', 'zingchart-angularjs', 'ngCookies', 'ngFileUpload']);

myModule.config(function($stateProvider, $urlRouterProvider){
    $stateProvider
    .state('allUsers', {
        url: '/allUsers',
        templateUrl: 'views/partials/allUsers.php',
        // controller: 'getUsersController'
    })
    .state('singleUser', {
        url: '/singleUser/:id/:index?/:route?',
        templateUrl: function(url){
            if(url.route){
                return 'views/partials/singleUser/' + url.route + '.php'
            }
            return 'views/partials/singleUser.php'

        },
        // controller: 'singleUserController'
    })

    .state('unapprovedUsers', {
        url: '/unapprovedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })

    .state('getWaitlistedUsers', {
        url: '/getWaitlistedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })
    .state('unverifiedUsers', {
        url: '/unverifiedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })
    .state('blockedUsers', {
        url: 'blockedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })
    .state('reportedUsers', {
        url: '/reportedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })
    .state('rejectedUsers', {
        url: '/rejectedUsers',
        templateUrl: 'views/partials/fieldUsers.php',
        // controller: 'fieldController'
    })
    .state('graphs', {
        url: '/graphs',
        templateUrl: 'views/partials/graphs.php'
    })
    .state('chats', {
        url: '/chats/:type?',
        templateUrl: 'views/partials/chats.php'
    })
    .state('activity', {
        url: '/activity',
        templateUrl: 'views/partials/activity.php'
    })
    .state('notification', {
        url: '/notification',
        templateUrl: 'views/partials/notification/notification.php'
    })
    .state('add', {
        url: '/add',
        templateUrl: 'views/partials/notification/partials/add.php'
    })
    .state('edit', {
        url: '/edit',
        templateUrl: 'views/partials/notification/partials/edit.php'
    })
    .state('matchAssign', {
        url: '/matchAssign',
        templateUrl: 'views/partials/matchAssign.php'
    })
    .state('stack', {
        url: '/stack/:id?',
        templateUrl: 'views/partials/stack.php'
    })



})
