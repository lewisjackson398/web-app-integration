var ScheduleApp = angular.module("ScheduleApp", ["ngRoute", "angular.filter"]);

ScheduleApp.config(function ($routeProvider) {
    $routeProvider
        .when('/days', {
            templateUrl: "js/partials/days.html",
            controller: "DaysController"
        })
        .when('/Schedule/:dayid', {
            templateUrl: "js/partials/schedule-days.html",
            controller: "ScheduleController"
        })
        .when('/Schedule/:dayid/slot/:slotid/', {
            templateUrl: "js/partials/session-details.html",
            controller: "SlotsController"
        })
        .otherwise("/", {
        })
});
