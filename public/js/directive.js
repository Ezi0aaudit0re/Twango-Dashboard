var myModel = angular.module('myApp')

myModel.directive('bxSlider', function(){
    return {
        restrict: 'A',
        controller: singleUserController,
        link: function(scope, elemetchat){
            alert("hello world");
        }
    }
})
