var ScheduleApp = angular.module("ScheduleApp");

ScheduleApp.controller('IndexController', function ($scope, dataService) {
    $scope.title = 'CHI 2019';

    $scope.data = {};

    dataService.getMeta().then(
        function (response) {
            for (var value in response) {
                $scope.data[value] = response[value];
            }
        },
        function (error) {
            console.log(error);
        }
    )
});


ScheduleApp.controller('DaysController', function ($scope, dataService, $location) {
    var getDays = function () {
        dataService.getDays().then(
            function (response) {
                $scope.days = response.data;
            },
            function (err) {
                $scope.status = 'Unable to load data ' + err;
            },
            function (notify) {
                console.log(notify);
            }
        );
    };

    $scope.selectDay = function ($event, day) {
        $scope.selectedDay = day;
        $location.path('/Schedule/' + day.day);
    };

    getDays();
});

ScheduleApp.controller('ScheduleController', function ($scope, dataService, $routeParams, $location, $http) {
    $scope.showMe = false;
    $scope.myFunc = function () {
        $scope.showMe = !$scope.showMe;
    }

    var getSchedules = function (day) {
        dataService.getSchedules(day).then(
            function (response) {
                $scope.ourGrouper = 'time';
                $scope.schedules = response.data;
                $scope.sesscCount = response.count;
            },
            function (err) {
                $scope.status = 'Unable to load data ' + err;
            },
            function (notify) {
                console.log(notify);
            }
        );
    };

    var getTypes = function () {
        dataService.getTypes().then(
            function (response) {
                $scope.types = response.data;
            },
            function (err) {
                $scope.status = 'Unable to load data ' + err;
            },
            function (notify) {
                console.log(notify);
            }
        );
    };
    getTypes();

    if ($routeParams && $routeParams.dayid) {
        getSchedules($routeParams.dayid);
    }

    $scope.selectSlot = function ($event, slot) {
        $scope.selectedSot = slot;
        $location.path('/Schedule/' + $routeParams.dayid + '/slot/' + slot.id);
    };

    $scope.typeSelected = function () {
        console.log($scope.typeSelect);
        $http.get('../part1/api/schedule/' + $routeParams.dayid + '/' + $scope.typeSelect.type).then(function (response) {
            $scope.schedules = response.data.data.Results;
        });
    }
    console.log($scope.schedules);
});

ScheduleApp.controller('SlotsController', function ($scope, dataService, $routeParams) {
    $scope.abstract = false;
    $scope.myAbstract = function () {
        $scope.abstract = !$scope.abstract;
    }

    var getSlotDetails = function (slot) {
        dataService.getSlotDetails(slot).then(
            function (response) {
                $scope.grouper = 'title';
                $scope.paperSlot = response.data;
            },
            function (err) {
                $scope.status = 'Unable to load data ' + err;
            },
            function (notify) {
                console.log(notify);
            }
        );
    };

    if ($routeParams && $routeParams.dayid) {
        getSlotDetails($routeParams.slotid);
    }
});


ScheduleApp.controller('LoginController',
    [
        '$scope',
        '$rootScope',
        'dataService',
        function ($scope, $rootScope, dataService) {

            var getLogin = function () {
                dataService.getLogin().then(
                    function (response) {
                        $scope.login = response.data;
                    },
                    function (err) {
                        $scope.status = 'Unable to load data ' + err;
                    },
                    function (notify) {
                        console.log(notify);
                    }
                );
            };
            getLogin();

            $scope.loggedIn = false;

            $scope.login = function (user) {
                console.log(user);
                $scope.loggedIn = true;
                $rootScope.$broadcast('login', user.name);
            }
            $scope.logout = function () {
                $scope.loggedIn = false;
                $rootScope.$broadcast('logout');
            }


        }
    ]
);