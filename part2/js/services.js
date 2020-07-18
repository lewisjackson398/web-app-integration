var ScheduleApp = angular.module('ScheduleApp');

ScheduleApp.service('dataService', function ($q, $http) {

    var urlBase = 'http://localhost/wai/part1/api/';
    var metaUrl = "/wai/part2/appInfo.json";

    this.getMeta = function () {
        var defer = $q.defer();
        $http.get(metaUrl, { cache: 1 }).then(
            function (response) {
                defer.resolve(response.data)
            },
            function (error) {
                defer.reject(error);
            }
        );
        return defer.promise;
    };

    this.getLogin = function () {
        var defer = $q.defer(),
            daysUrl = urlBase + 'login/';
        $http.get(daysUrl, { cache: true })
            .then(function (response) {
                defer.resolve({
                    data: response.data.data.Results
                });
            },
                function (err) {
                    defer.reject(err);
                });
        return defer.promise;
    };

    this.getDays = function () {
        var defer = $q.defer(),
            daysUrl = urlBase + 'days/';
        $http.get(daysUrl, { cache: true })
            .then(function (response) {
                defer.resolve({
                    data: response.data.data.Results
                });
            },
                function (err) {
                    defer.reject(err);
                });
        return defer.promise;
    };

    this.getTypes = function () {
        var defer = $q.defer(),
            daysUrl = urlBase + 'types/';
        $http.get(daysUrl, { cache: true })
            .then(function (response) {
                defer.resolve({
                    data: response.data.data.Results
                });
            },
                function (err) {
                    defer.reject(err);
                });
        return defer.promise;
    };

    this.getSchedules = function (day) {
        var defer = $q.defer(),
            scheduleUrl = urlBase + 'schedule/' + day;
        $http.get(scheduleUrl)
            .then(function (response) {
                defer.resolve({
                    data: response.data.data.Results,
                    count: response.data.data.RowCount
                });
            },
                function (err) {
                    defer.reject(err);
                });
        return defer.promise;

    };

    this.getSlotDetails = function (slot) {

        var defer = $q.defer(),
            slotUrl = urlBase + 'slot/' + slot;
        $http.get(slotUrl)
            .then(function (response) {
                defer.resolve({
                    data: response.data.data.Results,
                    count: response.data.data.RowCount
                });
            },
                function (err) {
                    defer.reject(err);
                });
        return defer.promise;
    };


});

